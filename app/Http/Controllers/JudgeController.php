<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Models\Judge;
use App\Models\Voter;
use Illuminate\Support\Facades\Auth;

class JudgeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $eventTitle['title'] = '';
        $eventTitle['auth_type'] = 'mail';
        if($request->filled('event_id')) {
            $event = Event::findOrFail($request->get('event_id'));
            if($event->lang == 'fr')
                $eventTitle['title'] = $event->title_fr;
            if($event->lang == 'ar')
                $eventTitle['title'] = $event->title_ar;
            if($event->lang == 'en')
                $eventTitle['title'] = $event->title_en;

            $eventTitle['auth_type'] = $event->auth_type;
            if($event->auth_type == 'code')
                $eventTitle['nbVoters'] = Voter::where('event_id', $event->id)->count();
        }
        return view("pages.judges.index", compact('eventTitle'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $events = [];
        $event = null;
        if($request->filled('event_id')){
            if($user->is_super_admin)
                $event = Event::where(['id' => $request->get('event_id'), 'status' => 1])->first();
            else
                $event = Event::where(['id' => $request->get('event_id'), 'status' => 1, 'country' => $user->country])->first();
        }

        if(!isset($event->id))
            $event = null;

        if($user->is_super_admin)
            $events = Event::where(['status' => 1])->get();
        else
            $events = Event::where(['status' => 1, 'country' => $user->country])->get();


        return view("pages.judges.create", compact('event', 'events'));
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
            $judge =  Judge::findOrFail($id);
            $user = Auth::user();
            if($user->is_super_admin)
                $events = Event::all();
            else
                $events = Event::where(['country' => $user->country])->get();

            return view("pages.judges.edit", compact("judge", "events"));
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
        try {
            $user = Auth::user();
            if($request->filled('event_id'))
                $judges = Judge::where(['event_id' => $request->get('event_id')])->get();
            else {
                if($user->is_super_admin)
                    $judges = Judge::all();
                else
                    $judges = Judge::where(['country' => $user->country])->get();
            }
            return Datatables::of($judges)
                ->editColumn('country', function($judge) {
                    if($judge->country != '') {
                        return '<span style ="font-size:xxx-large;" class="flag-icon flag-icon-'.$judge->country.'"></span>';
                    } else return '<span class="flag-icon flag-icon-all"></span>';
                })
                ->addColumn('event', function($judge) {
                    if($judge->event->id > 0){
                        switch ($judge->event->lang) {
                            case 'fr':
                                return Str::limit($judge->event->title_fr, $limit = 40, $end = '...');
                                break;
                            case 'en':
                                return Str::limit($judge->event->title_en, $limit = 40, $end = '...');
                                break;
                            case 'ar':
                                return Str::limit($judge->event->title_ar, $limit = 40, $end = '...');
                                break;
                            default:
                                return Str::limit($judge->event->title_fr, $limit = 40, $end = '...');
                                break;
                        }
                        return Str::limit($judge->nom, $limit = 40, $end = '...');
                    }else{
                        return '--';
                    }
                })
                ->editColumn('nom', function($judge) {
                    if($judge->nom != ''){
                        return Str::limit($judge->nom, $limit = 40, $end = '...');
                    }else{
                        return '--';
                    }
                })
                ->editColumn('prenom', function($judge) {
                    if($judge->prenom != ''){
                        return Str::limit($judge->prenom, $limit = 40, $end = '...');
                    }else{
                        return '--';
                    }
                })
                ->editColumn('email', function($judge) {
                    if($judge->email != ''){
                        return Str::limit($judge->email, $limit = 40, $end = '...');
                    }else{
                        return '--';
                    }
                })
                ->addColumn('actions', function($judge) {
                    return '<center>
                            <a href="'.route('judges.edit',array('id' => $judge->id)).'">
                                <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce juge">
                                <i class="fa fa-edit"></i>
                                </button>
                            </a>
                            <a href="javascript:;" onClick="remove(\'judges\','.$judge->id.')">
                                <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce juge">
                                <i class="fa fa-times"></i>
                                </button>
                            </a></center>';
            })
            ->rawColumns(['country', 'actions'])
            ->make(true);
        } catch (\Exception $e) {
            die($e->getMessage());
            //return view("pages.error", ["code"=>400, "message" => $e->getMessage()]);
        }
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
            'event_id' => 'required|integer|exists:events,id',
            'nom' => 'sometimes',
            'prenom'  => 'sometimes',
            'email'  => 'sometimes|unique:judges,email,'.$id
        ));

        try {
            if($request->filled('password')) {
                $this->validate($request, [
                    'password'  => 'sometimes|between:6,20|confirmed',
                    'password_confirmation'  => 'between:6,20'
                ]);
            }
            $judge = Judge::findOrFail($id);

            if($request->filled('event_id'))
                $judge->event_id = $request->input('event_id');

            if($request->filled('nom'))
                $judge->nom = $request->input('nom');

            if($request->filled('prenom'))
                $judge->prenom = $request->input('prenom');

            if($request->filled('email'))
                $judge->email = $request->input('email');

            if($request->filled('country'))
                $judge->country = $request->input('country');

            if($request->filled('password'))
                $judge->password = Hash::make($request->input('password'));
            $judge->save();

            return redirect()->back()
                ->with('success','Le jury a été modifié avec succès. Retournez a la liste <a href="'.route('judges.index', $request->input('event_id')).'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, le jury n\'était pas modifiée. Merci de réessayer.');
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
        try {
            if($request->get('auth_type') == 'mail') {
                $this->validate($request,array(
                    'nom' => 'required',
                    'prenom'  => 'required',

                    'password'  => 'required|between:6,20|confirmed',
                    'password_confirmation'  => 'between:6,20',
                    'auth_type' => 'required',
                    'nb_voters' => 'integer'
                ));

                $judge = new Judge();

                $judge->nom = $request->input('nom');
                $judge->prenom = $request->input('prenom');
                $judge->email = $request->input('email');
                if($request->filled('country'))
                    $judge->country = $request->input('country');
                $judge->event_id = $request->input('event_id');
                $judge->password = Hash::make($request->input('password'));
                $judge->save();
            } else {
                $this->generate($request);
            }
            return redirect()->back()
                    ->with('success','Le jury a été ajouté avec succès. Retournez a la liste <a href="'.route('judges.index', $request->input('event_id')).'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, Le jury n\'était pas ajouté. Merci de réessayer.'.$e->getMessage());
        }
    }

    public function generate(Request $request)
    {
        $nbVoters = $request->get('nb_voters');
        $eventId = $request->get('event_id');
        // Delete old voters
        Voter::where(['event_id' => $eventId])->delete();
        $voters = [];
        for ($i=1; $i <= $nbVoters; $i++) {
            $voters[] = [
                'email' => 'guest'.$i.'_'.$eventId.'@orange.com',
                'event_id' => $eventId
            ];
        }
        Voter::insert($voters);
    }


    /**
     * Change the status of the Judge.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request){
        try {
            $judge = Judge::findOrFail($request->input('id'));
            if($judge->status ==  0){
                $judge->status = 1;
            }else{
                $judge->status = 0;
            }
            $judge->save();
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
            $judge = Judge::findOrFail($id);
            $judge->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }


}
