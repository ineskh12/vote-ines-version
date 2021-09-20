<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;
use App\Models\Event;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("pages.events.index");
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.events.create");
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
            $event =  Event::findOrFail($id);
            $event->date_from = $event->date_from->format('Y-m-d');
            $event->date_to = $event->date_to->format('Y-m-d');
            return view("pages.events.edit", compact("event"));
        } catch (\Exception $e) {
           return view("pages.error");
        }

    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        $user = Auth::user();
        if($user->is_super_admin)
            $events = Event::all();
        else
            $events = Event::where(['country' => $user->country])->get();
        return Datatables::of($events)
            ->editColumn('country', function($event) {
                if($event->country != '') {
                    return '<span style ="font-size:xxx-large;" class="flag-icon flag-icon-'.$event->country.'"></span>';
                } else return '<span class="flag-icon flag-icon-all"></span>';
            })
            ->editColumn('logo', function($event) {
                if ($event->logo != '' && File::exists(public_path().'/assets/uploads/events/'.$event->logo))
	            {
                    return '<img src="'.asset('/assets/uploads/events/thumbs/'.$event->logo).'" style="width:70px; height:70px"/>';
                }else{
                    return '<img src="'.asset('/assets/uploads/no_image.jpg').'" class="img-circle" style="width:70px; height:70px"/>';
                }
            })
            ->addColumn('titre', function($event) {
                switch ($event->lang) {
                    case 'en':
                        return Str::limit($event->title_en, $limit = 40, $end = '...');
                        break;

                    case 'fr':
                        return Str::limit($event->title_fr, $limit = 40, $end = '...');
                        break;

                    case 'ar':
                        return Str::limit($event->title_ar, $limit = 40, $end = '...');
                        break;

                    default:
                        return '--';
                        break;
                }
            })
            ->editColumn('lang', function($event) {
            	switch ($event->lang) {
                    case 'en':
                        return 'Anglais';
                        break;

                    case 'fr':
                        return 'Français';
                        break;

                    case 'ar':
                        return 'Arabe';
                        break;

                    default:
                        return '--';
                        break;
                }
            })
            ->editColumn('auth', function($event) {
            	switch ($event->auth_type) {
                    case 'mail':
                        return 'Email';
                        break;

                    case 'code':
                        return 'QR Code';
                        break;

                    default:
                        return '--';
                        break;
                }
            })
            ->editColumn('date_from', function($event) {
            	if($event->date_from != ''){
            		return $event->date_from->format('d-m-Y');
            	}else{
            		return '--';
            	}
            })
            ->editColumn('date_to', function($event) {
            	if($event->date_to != ''){
            		return $event->date_to->format('d-m-Y');
            	}else{
            		return '--';
            	}
            })
            ->editColumn('projects', function($event) {
                $nbProjects = count($event->projects);
            	if($nbProjects == 0){
                    return '<center>
                        <a href="'.route('projects.create',array('event_id' => $event->id)).'">
                            (0) projets! Créer un ? <i class="fa fa-external-link"></i>
                        </a>
                    </center>';
            	}else{
            		return '<center>
                        <a href="'.route('projects.index',array('event_id' => $event->id)).'">
                            ('.$nbProjects.') projets <i class="fa fa-external-link"></i>
                        </a>
                    </center>';
            	}
            })
            ->editColumn('status', function($event) {
                $status = ($event->status == 1) ? 'checked' : '';
                return '<center><input type="checkbox" '.$status.' data-height="23" data-id="'.$event->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default"></center>';
            })
            ->addColumn('actions', function($event) {
                return '<center>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-tools"></i> Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="'.route('judges.index',array('event_id' => $event->id)).'" class="dropdown-item">
                                    <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Gérer les jurys">
                                        <i class="fa fa-gavel"></i> Jurys
                                    </button>
                                </a>
                                <a href="'.route('criterias.index',array('event_id' => $event->id)).'" class="dropdown-item">
                                    <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Gérer les critères">
                                        <i class="mdi mdi-comment-check-outline"></i> Critères
                                    </button>
                                </a>
                                <a href="'.route('percentages.index',array('event_id' => $event->id)).'" class="dropdown-item">
                                    <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Gérer les pourcentages">
                                    <i class="mdi mdi-percent"></i> Pourcentages
                                    </button>
                                </a>
                                <div class="dropdown-divider"></div>
                                <div class="pl-3 d-flex justify-content-around">
                                    <a href="'.route('events.edit',array('id' => $event->id)).'" class="dropdown-item">
                                        <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier cet évènement">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                    <a href="javascript:;" onClick="remove(\'events\','.$event->id.')" class="dropdown-item">
                                        <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer cet évènement">
                                        <i class="fa fa-times"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        </center>';
	    })
	    ->rawColumns(['actions', 'status', 'logo', 'projects', 'country'])
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
            'logo' => 'mimes:jpeg,jpg,png',
            'splash' => 'mimes:jpeg,jpg,png',
            'icon' => 'mimes:jpeg,jpg,png',
            'date_from' => 'required|date|date_format:d-m-Y',
            'date_to' => 'required|date|date_format:d-m-Y',
            'scale' => 'required|integer',
            'country' => 'required|in:tn,sn,et,cm,ci',
            'lang' => 'required|in:ar,en,fr',
            'auth' => 'required|in:mail,code'
        ));

        try {
            // According to the default language, a title must be provided
            $lang = $request->get('lang');
            if($lang == 'fr' && !$request->has('titre_fr'))
                throw new ValidationException('XXXXXXX');

            $event = Event::findOrFail($id);
            /*** if file exist in the request ***/
            if($request->file('logo')) {
                $file = $request->file('logo');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $event->logo = $filename;
            }

            if($request->file('icon')) {
                $file = $request->file('icon');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $event->icon = $filename;
            }

            if($request->file('splash')) {
                $file = $request->file('splash');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $event->splash = $filename;
            }

            if($request->filled('titre_fr'))
                $event->title_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $event->title_ar = $request->input('titre_ar');
            if($request->filled('titre_en'))
                $event->title_en = $request->input('titre_en');

            $event->date_from = DateTime::createFromFormat('d-m-Y', $request->input('date_from'))->format('Y-m-d');
            $event->date_to = DateTime::createFromFormat('d-m-Y', $request->input('date_to'))->format('Y-m-d');
            $event->scale = $request->input('scale');
            $event->country = $request->input('country');
            $event->lang = $request->input('lang');
            $event->auth_type = $request->input('auth');

            $event->save();
            return redirect()->back()
                ->with('success','L\'évènement a été modifié avec succès. Retournez a la liste <a href="'.route('events.index').'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, l\'évènement n\'était pas modifié. Merci de réessayer.'.$e->getMessage());
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
            'splash' => 'mimes:jpeg,jpg,png',
            'icon' => 'mimes:jpeg,jpg,png',
            'date_from' => 'required|date|date_format:d-m-Y',
            'date_to' => 'required|date|date_format:d-m-Y',
            'scale' => 'required|integer',
            'country' => 'required|in:tn,sn,et,cm,ci',
            'lang' => 'required|in:ar,en,fr',
            'auth' => 'required|in:mail,code'
        ));

        try {
            // According to the default language, a title must be provided
            $lang = $request->get('lang');
            if($lang == 'fr' && !$request->has('titre_fr'))
                throw new ValidationException('XXXXXXX');
            $event = new Event();
            /*** if file exist in the request ***/
            if($request->file('logo')) {
                $file = $request->file('logo');
                $filename = "logo".time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(180,180);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $event->logo = $filename;
            }

            if($request->file('icon')) {
                $file = $request->file('icon');
                $filename1 = "icon".time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename1,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename1);

                // affect image to user object
                $event->icon = $filename1;
            }

            if($request->file('splash')) {
                $file = $request->file('splash');
                $filename2 = "splash".time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/events/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70,null);
                $thumb_img->save($destinationPath.'/'.$filename2,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/events');
                $file->move($destinationPath, $filename2);

                // affect image to user object
                $event->splash = $filename2;
            }



            if($request->filled('titre_fr'))
                $event->title_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $event->title_ar = $request->input('titre_ar');
            if($request->filled('titre_en'))
                $event->title_en = $request->input('titre_en');

            $event->date_from = DateTime::createFromFormat('d-m-Y', $request->input('date_from'))->format('Y-m-d');
            $event->date_to = DateTime::createFromFormat('d-m-Y', $request->input('date_to'))->format('Y-m-d');
            $event->scale = $request->input('scale');
            $event->country = $request->input('country');
            $event->lang = $request->input('lang');
            $event->auth_type = $request->input('auth');

            $event->save();

            return redirect()->back()
                ->with('success','L\'évènement a été ajouté avec succès. Retournez a la liste <a href="'.route('events.index').'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, l\'évènement n\'était pas ajouté. Merci de réessayer.'.$e->getMessage());
        }
    }


    /**
     * Change the status of the event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request){
        try {
            $event = Event::findOrFail($request->input('id'));
            if($event->status ==  0){
                $event->status = 1;
            }else{
                $event->status = 0;
            }
            $event->save();
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
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }

    // The API group

    /**
     * @return \Illuminate\Http\Response
     */
    public function getList()
    {
        try{
            $event = Event::where([
                ['status', '=', true],
                ['country', '=', 'tn'],
                ['date_from', '<=', date('Y-m-d')],
                ['date_to', '>=', date('Y-m-d')]
            ])->first([
                'id',
                'lang',
                DB::raw('(CASE WHEN title_fr IS NULL THEN \'\' ELSE title_fr END) AS title_fr '),
                DB::raw('(CASE WHEN title_en IS NULL THEN \'\' ELSE title_en END) AS title_en '),
                DB::raw('(CASE WHEN title_ar IS NULL THEN \'\' ELSE title_ar END) AS title_ar '),
                'splash', 'auth_type', 'scale']);

            $event['splash'] = asset('/assets/uploads/events/'.$event['splash']);

            $response = [
                'success' => true,
                'status' => 200,
                'event' => $event,
                'errors' => []
            ];

            return response()->json($response, 200);
        } catch(\Exception $e) {
            $response = [
                'success' => false,
                'status' => $e->getCode(),
                'event' => [],
                'errors' => [$e->getMessage()]
            ];
            return response()->json($response, 200);
        }
    }


}
