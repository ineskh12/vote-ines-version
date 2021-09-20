<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $eventTitle['title'] = '';
        if($request->filled('event_id')) {
            $event = Event::findOrFail($request->get('event_id'));
            if($event->lang == 'fr')
                $eventTitle['title'] = $event->title_fr;
            if($event->lang == 'ar')
                $eventTitle['title'] = $event->title_ar;
            if($event->lang == 'en')
                $eventTitle['title'] = $event->title_en;
        }

        return view("pages.projects.index", compact('eventTitle'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $events = Event::where(['country' => $user->country, 'status' => 1])->get();
        return view("pages.projects.create", compact('events'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $project = Project::findOrFail($id);
            $user = Auth::user();
            $events = Event::where(['country' => $user->country, 'status' => 1])->get();
            return view("pages.projects.edit", compact("project", "events"));
        } catch (\Exception $e) {
           return view("pages.error");
        }
        
    }


        /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $user = Auth::user();
        if($request->filled('event_id'))
            $projects = Project::where(['event_id' => $request->input('event_id'), 'country' => $user->country])->get();
        else
            $projects = Project::where(['country' => $user->country])->get();
        return Datatables::of($projects)
            ->editColumn('event_id', function($project) {
                if($project->event->lang != ''){
                    $eventTitle = '';
                    if($project->event->lang == 'fr')
                        $eventTitle = $project->event->title_fr;
                    if($project->event->lang == 'ar')
                        $eventTitle = $project->event->title_ar;
                    if($project->event->lang == 'en')
                        $eventTitle = $project->event->title_en;
                    return Str::limit($eventTitle, $limit = 40, $end = '...');	
                }else{
                    return '--';
                }
            })
            ->editColumn('logo', function($project) {
                if ($project->logo != '' && File::exists(public_path().'/assets/uploads/projects/'.$project->logo))
	                {
                    return '<img src="'.asset('/assets/uploads/projects/thumbs/'.$project->logo).'" style="width:70px; height:70px"/>';
                }else{
                    return '<img src="'.asset('/assets/uploads/no_image.jpg').'" class="img-circle" style="width:70px; height:70px"/>';
                }
            })
            ->editColumn('titre', function($project) {
                if($project->event->lang != ''){
                    $projectTitle = '';
                    if($project->event->lang == 'fr')
                        $projectTitle = $project->titre_fr;
                    if($project->event->lang == 'ar')
                        $projectTitle = $project->titre_ar;
                    if($project->event->lang == 'en')
                        $projectTitle = $project->titre_en;
                    return Str::limit($projectTitle, $limit = 40, $end = '...');	
                }else{
                    return '--';
                }
            })
            ->editColumn('status', function($project) {
                $status = ($project->status == 1) ? 'checked' : '';
                return '<center><input type="checkbox" '.$status.' data-height="23" data-id="'.$project->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default"></center>';
            })
            ->editColumn('created_at', function($event) {
            	if($event->created_at != ''){
            		return $event->created_at->format('d-m-Y H:i');	
            	}else{
            		return '--';
            	}
            })
            ->addColumn('actions', function($project) {
                return '<center>
                        <a href="'.route('notes.multiple', array('projectId' => $project->id)).'">
                            <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Noter ce project">
                            <i class="fa fa-star"></i>
                            </button>
                        </a>
                        <a href="'.route('projects.edit',array('id' => $project->id)).'">
                            <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce project">
                               <i class="fa fa-edit"></i>
                            </button>
                        </a>
                        <a href="javascript:;" onClick="remove(\'projects\','.$project->id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce project">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center>';
	    })
	    ->rawColumns(['actions', 'status', 'logo', 'color'])
            ->make(true);
    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request,array(
            'logo' => 'mimes:jpeg,jpg,png'
        ));

        
        try {

            $project = Project::findOrFail($id);
            /*** if file exist in the request ***/
            if($request->file('logo')) {
                $file = $request->file('logo');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/projects/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/projects');
                $file->move($destinationPath, $filename);

                // unlink old images //
                File::delete(public_path() . '/assets/uploads/projects/'.$project->logo);
                File::delete(public_path() . '/assets/uploads/projects/thumbs/'.$project->logo);

                // affect image to user object
                $project->logo = $filename;
            }

            if($request->filled('titre_fr'))
                $project->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_en'))
                $project->titre_en = $request->input('titre_en');
            if($request->filled('titre_ar'))
                $project->titre_ar = $request->input('titre_ar');

            $project->save();


            return redirect()->back()
                ->with('success','Le projet a été modifié avec succès. Retournez a la liste <a href="'.route('projects.index').'">Ici</a>.'); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, le projet n\'était pas modifiée. Merci de réessayer.');  
        } 
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request,array(
            'logo' => 'mimes:jpeg,jpg,png',
            'event_id' => 'required|exists:events,id'
        ));

        try {
            $project = new Project();
            /*** if file exist in the request ***/
            if($request->file('logo')) {
                $file = $request->file('logo');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/projects/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/projects');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $project->logo = $filename;
            }

            if($request->filled('titre_fr'))
                $project->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_en'))
                $project->titre_en = $request->input('titre_en');
            if($request->filled('titre_ar'))
                $project->titre_ar = $request->input('titre_ar');

            $project->event_id = $request->input('event_id');
            $event = Event::findOrFail($request->input('event_id'));
            $project->country = $event->country;

            $project->save();

            return redirect()->back()
                ->with('success','Le projet a été ajouté avec succès. Retournez a la liste <a href="'.route('projects.index').'">Ici</a>.'); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, le projet n\'était pas ajouté. Merci de réessayer.'.$e->getMessage());  
        } 
    }


    /**
     * Change the status of the project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request){
        try {
            $project = Project::findOrFail($request->input('id'));
            if($project->status ==  0){
                $project->status = 1;
            }else{
                $project->status = 0;
            }
            $project->save();
            return response()->json(true);
        } catch (\Exception $e) {
            return response()->json(false);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json(true);
        } catch (\Exception $e) {
            return response()->json(false);
        }
    }


    /**
     * Api part - Get projects list
     */
    public function getList(Request $request)
    {
        try{
            $this->validate($request, [
                'event_id' => 'required|integer|exists:events,id'
            ]);
            $eventId = $request->get('event_id');
            $projects = Project::where(['status' => 1, 'event_id' => $eventId])->get();

            return response()->json($projects);

        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

}
