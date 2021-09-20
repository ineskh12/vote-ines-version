<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;
use App\Models\Percentage;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class PercentageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("pages.percentages.index");
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
        return view("pages.percentages.create", compact("events"));
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
            $percentage = Percentage::findOrFail($id);
            $user = Auth::user();
            $events = Event::where(['country' => $user->country, 'status' => 1])->get();
            return view("pages.percentages.edit", compact("percentage", "events"));
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
            $percentages = Percentage::where(['event_id' => $request->input('event_id'), 'country' => $user->country])->get();
        else
            $percentages = Percentage::where(['country' => $user->country])->get();
        return Datatables::of($percentages)
            ->editColumn('event_id', function($percentage) {
                if($percentage->event->lang != ''){
                    $eventTitle = '';
                    if($percentage->event->lang == 'fr')
                        $eventTitle = $percentage->event->title_fr;
                    if($percentage->event->lang == 'ar')
                        $eventTitle = $percentage->event->title_ar;
                    if($percentage->event->lang == 'en')
                        $eventTitle = $percentage->event->title_en;
                    return Str::limit($eventTitle, $limit = 40, $end = '...');	
                }else{
                    return '--';
                }
            })
            ->addColumn('titre', function($percentage) {
                if($percentage->event->lang != ''){
                    $title = '';
                    if($percentage->event->lang == 'fr')
                        $title = $percentage->titre_fr;
                    if($percentage->event->lang == 'ar')
                        $title = $percentage->titre_ar;
                    if($percentage->event->lang == 'en')
                        $title = $percentage->titre_en;
                    return Str::limit($title, $limit = 40, $end = '...');	
                }else{
                    return $percentage->titre_fr;
                }
            })
            ->editColumn('percentage', function($percentage) {
                return $percentage->percentage.' %';
            })
            ->editColumn('status', function($percentage) {
                $status = ($percentage->status == 1) ? 'checked' : '';
                return '<center><input type="checkbox" '.$status.' data-height="23" data-for="percentages" data-id="'.$percentage->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default"></center>';
            })
            ->editColumn('type', function($percentage) {
                $type = ($percentage->type == 1) ? '<span class="label label-info">Mobile</span>' : '<span class="label label-success">Back-office</span>';
                return $type;
            })
            ->addColumn('actions', function($percentage) {
                return '<center><a href="'.route('percentages.edit',array('id' => $percentage->id)).'">
                            <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce percentage">
                               <i class="fa fa-edit"></i>
                            </button>
                        </a>
                        <a href="javascript:;" onClick="remove(\'percentages\','.$percentage->id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light"  data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce percentage">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center>';
	    })
	    ->rawColumns(['actions', 'status', 'type'])
        ->make(true);
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
            'type' => 'required',
            'percentage' => 'required',
            'event_id' => 'required|exists:events,id'
        ));
        
        try {
            $eventId = $request->input('event_id');
            $somme = Percentage::where(['status' => 1, 'event_id' => $eventId])->sum('percentage') != null ? Percentage::where(['status' => 1, 'event_id' => $eventId])->sum('percentage') : 0;

        	if($request->input('percentage')<= (100 - $somme)){
                $percentage = new Percentage();
	            $percentage->titre_en = $request->input('titre_en');
	            $percentage->titre_fr = $request->input('titre_fr');
                $percentage->titre_ar = $request->input('titre_ar');
                $percentage->event_id = $eventId;
                $event = Event::findOrFail($eventId);
                $percentage->country = $event->country;
	            $percentage->type = $request->input('type');
	            $percentage->percentage = $request->input('percentage');
	            $percentage->save();

	            return redirect()->back()
	                ->with('success','La pourcentage a été ajouté avec succès. Retournez a la liste <a href="'.route('percentages.index').'">Ici</a>.'); 
        	}else{
    			return redirect()->back()
                ->with('error','Un erreur s\'est produite, la  valeur de pourcentage ne peut pas déppaser <b>'.(100 - $somme).'% </b>. Merci de réessayer.');  
        	}


        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la pourcentage n\'était pas ajoutée. Merci de réessayer.'.$e->getMessage());  
        } 
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,array(
            'percentage' => 'required',
            'event_id' => 'required|exists:events,id'
        ));

        
        try {

            $somme = Percentage::where("id","!=",$id)->where('status', 1)->sum('percentage') != null ? Percentage::where("id","!=",$id)->where('status', 1)->sum('percentage') : 0;


            if($request->input('percentage')<= (100 - $somme)){
                $percentage = Percentage::findOrFail($id);
                $percentage->titre_en = $request->input('titre_en');
                $percentage->titre_fr = $request->input('titre_fr');
                $percentage->titre_ar = $request->input('titre_ar');
                // $percentage->type = $request->input('type');
                $percentage->percentage = $request->input('percentage');
                $percentage->save();

                return redirect()->back()
                    ->with('success','La pourcentage a été ajouté avec succès. Retournez a la liste <a href="'.route('percentages.index').'">Ici</a>.'); 
            }else{
                return redirect()->back()
                ->with('error','Un erreur s\'est produite, la  valeur de pourcentage ne peut pas déppaser <b>'.(100 - $somme).'% </b>. Merci de réessayer.');  
            }


        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la pourcentage n\'était pas ajoutée. Merci de réessayer.');  
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
            $percentage = Percentage::findOrFail($request->input('id'));
            if($percentage->status ==  0){
                $somme = Percentage::where('status', 1)->sum('percentage') != null ? Percentage::where('status', 1)->sum('percentage') : 0;
            	if($percentage->percentage <= (100 - $somme)){
            		$percentage->status = 1;
            		$percentage->save();
                    $percentage->criterias()->update(['status'=>1]);
                    return response()->json(array('status'=>true,"somme"=>($percentage->percentage+$somme)));
            	}else{
                    return response()->json(array('status'=>false,"somme"=>(100-$somme)));
            	}
            }else{
                $percentage->status = 0;
                $percentage->save();

                $percentage->criterias()->update(['status'=>0]);

                $somme = Percentage::where('status', 1)->sum('percentage') != null ? Percentage::where('status', 1)->sum('percentage') : 0;
                return response()->json(array('status'=>true,"somme"=>$somme));
            }
        } catch (\Exception $e) {
            return response()->json(array('status'=>'erreur'));
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
            $percentage = Percentage::findOrFail($id);
            $percentage->delete();

            $percentage->criterias()->delete();

            $somme = Percentage::where('status', 1)->sum('percentage') != null ? Percentage::where('status', 1)->sum('percentage') : 0;
            return response()->json(array('status'=>true,"somme"=>$somme));
        } catch (\Exception $e) {
            return response()->json(array('status'=>false));
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
            $user = Auth::user();
            $eventId = $request->input('event_id');

            if($id = $request->input('id_percentage')){
                $somme = Percentage::where("id","!=",$id)->where(['status' => 1, 'event_id' => $eventId])->sum('percentage') != null ? Percentage::where("id","!=",$id)->where(['status' => 1, 'event_id' => $eventId])->sum('percentage') : 0;
            }else{
                $somme = Percentage::where(['status' => 1, 'event_id' => $eventId])->sum('percentage') != null ? Percentage::where(['status' => 1, 'event_id' => $eventId])->sum('percentage') : 0;
            }

            if($request->input('percentage') <= (100 - $somme) ){
                return response()->json(true);
            }else{
                return response()->json("La valeur de persontage ne doit pas dépaser ".(100 - $somme).'% .');
            }
        } catch (\Exception $e) {
             return response()->json("La valeur de persontage ne doit pas dépaser ".(100 - $somme).'% .');
        }
    }

}
