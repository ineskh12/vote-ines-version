<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Project;
use App\Models\Percentage;
use App\Models\NoteSetting;
use App\Models\Criteria ;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = Auth::user();
		$events = Event::where([
			['country','=', $user->country], 
			['status', '=', 1],
			['date_from', '<=', date('Y-m-d')],
			['date_to', '>=', date('Y-m-d')]
		])->get();
        return view('pages.dashboard.index', compact('events'));
    }

    public function victorys(Request $request){
		$this->validate($request, [
			'eventId' => 'required|integer|exists:events,id'
		]);

		if($request->filled('eventId')) {
			$event = Event::findOrFail($request->get('eventId'));
			$projects = Project::inRandomOrder()->where(['status' => 1, 'event_id' => $event->id])->get();//->with(['votes','backOfficeNotes'])->get();
		} else
			$projects = Project::inRandomOrder()->where('status',1)->with(['votes','backOfficeNotes'])->get();

    	$settings = NoteSetting::first() ? NoteSetting::first() : null;

    	$total = array();

    	foreach ($projects as $project) {
    		$somme = 0;
			$res = array();

    	 	$notes = $project->backOfficeNotes()
    	 	->select(DB::raw('percentage_id'), DB::raw('sum(note) / count(note) as note'))
    	 	->groupBy('percentage_id')->get()->toArray();


    	 	foreach ($notes as $key => $note) {
    	 		$percentage = Percentage::where(['status'=>1,'id'=>$note['percentage_id']])->first();
				if($percentage){
					$notes[$key]['note'] = ($note['note'] * $percentage->percentage) / $settings->somme;
					$somme += $notes[$key]['note'];
 				}
    	 	}

			if($event->auth_type == 'code') {
				//Voters
				$votes_per_criteria = $project->votes()
				->select(DB::raw('criteria_id'), DB::raw('sum(note) / count(note) as note '), DB::raw('count(note) as count '))
				->groupBy('criteria_id')->get();
			} else
				$votes_per_criteria = $project->judges()
				->select(DB::raw('criteria_id'), DB::raw('sum(note) / count(note) as note '), DB::raw('count(note) as count '))
				->groupBy('criteria_id')->get();
    	 	
 			foreach ($votes_per_criteria as $votes_p_criteria) {
 				$criteria = Criteria::where(['status'=>1,'id'=>$votes_p_criteria->criteria_id])->first();
 				if($criteria){
					if(isset($res[$criteria->percentage_id]["note"])){
	 					$res[$criteria->percentage_id]["note"] += $votes_p_criteria->note * ($criteria->coefficient * $votes_p_criteria->count);
	 					$res[$criteria->percentage_id]["coefficient"] += $criteria->coefficient * $votes_p_criteria->count;
	 				}else{
	 					$res[$criteria->percentage_id]["note"] = $votes_p_criteria->note * ($criteria->coefficient * $votes_p_criteria->count);
	 					$res[$criteria->percentage_id]["coefficient"] = $criteria->coefficient * $votes_p_criteria->count;
	 				}
 				}	
 			}
	
 			foreach ($res as $key => $value) {
 				$percentage = Percentage::where(['status'=>1,'id'=>$key])->first();
 				if($percentage){
 					$notes[]= array('percentage_id'=>$key,'note'=> (($value['note'] / $value['coefficient']) * $percentage->percentage) / $settings->somme);
 					$somme +=(($value['note'] / $value['coefficient']) * $percentage->percentage) / $settings->somme;
 				}
			 }
			
			switch ($event->lang) {
				case 'fr':
					$eventName = $event->title_fr;
					$projectTitle = $project->titre_fr;
					break;
				
				case 'en':
					$eventName = $event->title_en;
					$projectTitle = $project->titre_en;
					break;

				case 'ar':
					$eventName = $event->title_ar;
					$projectTitle = $project->titre_ar;
					break;
				
				default:
					$eventName = $event->title_fr;
					$projectTitle = $project->titre_fr;
					break;
			}

 			$total[] = array('eventName'=>$eventName, 'titre'=>$projectTitle,'color'=>$project->color,'logo'=>asset('/assets/uploads/projects/'.$project->logo),'note'=>floatval(number_format($somme, 2, '.', ' ')));
    	}

        // usort($total, create_function('$a, $b', '
        //     $a = $a["note"];
        //     $b = $b["note"];

        //     if ($a == $b) return 0;
        //     return ($a > $b) ? -1 : 1;
		// '));
		
		return response()->json($total,200, [], JSON_NUMERIC_CHECK);

    }

    public function show_chart(){
        return view('pages.dashboard.chart');
    }

    public function resultat(){
        return view('pages.resultats.index');
    }
}
