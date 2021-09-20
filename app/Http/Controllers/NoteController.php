<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Models\Percentage;
use App\Models\Project;
use App\Models\Event;
use App\Models\NoteSetting;

use DB;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function indexNoteBackOffice($id)
    {
        try {
            $user = Auth::user();
            if($user->is_super_admin) {
                $projects = Project::where(['status' => 1])
                ->whereNotIn('id',Project::where(['status' => 1])
                ->whereHas('backOfficeNotes', function ($q) use($id) {
                    $q->where('percentage_id',$id);
                })->pluck('id'))->get();
                $events = Event::where(['status' => 1])->get();
            } else {
                $projects = Project::where(['status' => 1, 'country' => $user->country])
                ->whereNotIn('id',Project::where(['status' => 1, 'country' => $user->country])
                ->whereHas('backOfficeNotes', function ($q) use($id) {
                    $q->where('percentage_id',$id);
                })->pluck('id'))->get();
                $events = Event::where(['status' => 1, 'country' => $user->country])->get();
            }

            $settings = NoteSetting::first() ? NoteSetting::first() : null;
        	$enable_add_note = count($projects) > 0 ? true : false;
            $percentage =  Percentage::findOrFail($id);
            return view("pages.notes.index", compact("percentage","projects","enable_add_note","settings", "events"));
        } catch (\Exception $e) {
           return view("pages.error");
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function multipleNoteBackOffice(Request $request)
    {
        try {
            $this->validate($request, [
                'projectId' => 'required|integer|exists:projects,id'
            ]);
            $project = Project::findOrFail($request->get('projectId'));
            // Get list of percentages
            $percentages = Percentage::where([
                'event_id' => $project->event_id,
                'status'  => 1,
                'type'    => 0
            ])->get();

            $currentNotes = [];
            foreach($project->backOfficeNotes as $noteBO) {
                $currentNotes[$noteBO->pivot->percentage_id] = $noteBO->pivot->note;
            }

            // Get the Event details
            $event = Event::findOrFail($project->event_id);
            return view("pages.notes.multiple", compact("percentages","project", "event", "currentNotes"));
        } catch (\Exception $e) {
           die($e->getMessage());
            return view("pages.error");
        }

    }


        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeNoteBackOffice(Request $request)
    {
        $this->validate($request,array(
            'project_id' => 'required|integer|exists:projects,id'
        ));

        try {

	    	$project = Project::findOrFail($request->input('project_id'));
            foreach($request->all() as $patameter => $value) {
                if(preg_match('/(^note)/', $patameter)) {
                    $percentageId = str_replace('note', '', $patameter);
                    $project->backOfficeNotes()->wherePivot('percentage_id',$percentageId)->detach();
                    $project->backOfficeNotes()->attach([$percentageId=>['note'=>$value]]);
                }
            }

			return redirect()->back()
	                ->with('success','La note a été ajouté avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la note n\'était pas ajoutée. Merci de réessayer.');
        }
	}


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatableNoteBackOffice(Request $request)
    {
        $user = Auth::user();
        $percentage_id = $request->input('percentage_id');
        if($user->is_super_admin)
            $projects = Project::where(['status' => 1])->whereHas('backOfficeNotes', function ($q) use($percentage_id) {
                    $q->where('percentage_id',$percentage_id);
            })->get();
        else
            $projects = Project::where(['status' => 1, 'country' => $user->country])->whereHas('backOfficeNotes', function ($q) use($percentage_id) {
                $q->where('percentage_id',$percentage_id);
            })->get();

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
			->editColumn('note', function($project) use($percentage_id) {
                $note = $project->backOfficeNotes()->where(['percentage_id'=>$percentage_id,'project_id'=>$project->id])->first();
                $settings = NoteSetting::first() ? NoteSetting::first() : null;
                if($note && $settings){
                	// return $note['pivot']['note'];
                        $style="";
                        $width = ($note['pivot']['note'] / $settings->somme) * 100;
                        if($width >= 0 && $width <= 25)
                            $bg = 'danger';
                        if($width > 25  && $width <= 50)
                            $bg = 'warning';
                        if($width > 50  && $width <= 75)
                            $bg = 'info';
                        if($width > 75)
                            $bg = 'success';

                        if($width<10)
                            $style="style='color:#212121'";
                    return '<div class="progress "><div class="progress-bar bg-'.$bg.' wow animated progress-animated" style="width: '.$width.'%; height:15px;" role="progressbar"> <b '.$style.'>'.floatval($note['pivot']['note']).' / '.$settings->somme.' </b> </div> </div>';
                }else{
                	return '--';
                }

            })
            ->addColumn('actions', function($project) use($percentage_id) {
                return '<center>
                        <a href="javascript:;" onClick="remove(\'projectnote\','.$project->id.','.$percentage_id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce criteria">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center>';
	    })
	    ->rawColumns(['actions', 'note', 'logo'])
            ->make(true);
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        try {
            $settings = NoteSetting::first() ? NoteSetting::first() : new NoteSetting();
            return view("pages.notes.settings", compact("settings"));
        } catch (\Exception $e) {
           return view("pages.error");
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings_submit(Request $request)
    {

        $this->validate($request,array(
            'somme' => 'required',
        ));


        try {
            if(Project::where('status',1)->has('backOfficeNotes')->count() ==  0 && Project::where('status',1)->has('votes')->count() ==  0 ){
             $settings = NoteSetting::first() ? NoteSetting::first() : new NoteSetting();
             $settings->somme = $request->input('somme');
             $settings->save();
                return redirect()->back()
                    ->with('success','les sommes des votes ont été modifié avec succès.');
              }else{
                return redirect()->back()
                ->with('error','Un erreur s\'est produite, la somme des votes n\'ont était pas modifé. Il ya des projets qui sont déja voté. Merci de vider les deux tables <b>notes_mobile</b> et <b>notes_back_office</b>');
              }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la somme des votes n\'ont était pas modifé. Merci de réessayer.');
        }



    }


    /**
     * Change the email of the admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_somme(Request $request){

        try {
            $settings = NoteSetting::firstOrFail();
            if($request->input('note') <= $settings->somme){
                return response()->json(true);
            }else{
                return response()->json(false);
            }
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
    public function destroy(Request $request)
    {
        try {
            $project = Project::findOrFail($request->input('id'));
            $project->backOfficeNotes()->detach($request->input('id_percentage'));
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }



}
