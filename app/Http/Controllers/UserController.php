<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Voter;
use App\Models\Event;
use App\Models\Judge;
use App\Models\Project;
use App\Models\Criteria;
use App\Models\Category;
use App\Models\NoteSetting;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    
    public function login_qr_ws(Request $request){
        $this->validate($request, [
            'code' => 'required|integer|exists:voters,id',
            'eventId' => 'required|string'
        ]);

        try{
            $voter = Voter::where([
                'id' => $request->get('code'),
                'event_id' => $request->get('eventId'),
                'status' => true
            ])->first();

            if(isset($voter->id)){
                $event = Event::find($request->get('eventId'));
                // Get project list
                //get all available project with votes
                $categories = Category::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->get();

                //Get categories
                $criterias = Criteria::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->get();

                //get all available project with votes
                $projects = Project::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->with('votes')->get();

                foreach ($projects as $key => $value) {
                    //get all votes project to the user 
                    $votes = $value->votes()->where(['user_id'=>$voter->id])->get();
                    //check if project voted or not
                    $value->logo = asset('/assets/uploads/projects/'.$value->logo);
                    if(count($votes) >0){
                        $note_criteria =  0; $somme_coefficient = 0;
                        foreach ($votes as $vote) {
                             //get criteria of each note
                            if($criteria = Criteria::find($vote->pivot->criteria_id)){
                                $note = $vote->pivot->note ? $vote->pivot->note : 0;
                                $note_criteria = $note_criteria + ($note  * $criteria->coefficient);
                                $somme_coefficient =  $somme_coefficient + $criteria->coefficient;
                            };
                        }
                        $value->vote = ($somme_coefficient > 0) ? $note_criteria / $somme_coefficient : 0;
                    }else{
                        $value->vote  = -1;
                    }
                }

                //$note_setting = NoteSetting::first() ? NoteSetting::first()->pluck('somme')->get(0) : -1;

                return response()
                    ->json([
                        'status' => true, 
                        'data' => [
                            'userId' => $voter->id,
                            'total'=>$event->scale,
                            'projects'=>$projects->makeHidden(['color','created_at','updated_at','deleted_at','status','votes','country']),
                            'categories' => $categories->makeHidden(['created_at','updated_at','deleted_at','status','country','scale']),
                            'criterias'=>$criterias->makeHidden(['created_at','updated_at','deleted_at','status','percentage_id', 'country'])
                        ]
                    ],200, 
                    [], 
                    JSON_NUMERIC_CHECK
                );
                
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to authenticate Judge',
                    'data' => null,
                    'error' => []
                ], 200, 
                [], 
                JSON_NUMERIC_CHECK
                );
            }

        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to authenticate Judge',
                'data' => null,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 200);
        }
    }

    public function login_mail_ws(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|exists:judges,email',
            'password' => 'required|string',
            'eventId' => 'required|string'
        ]);

        try{
            $judge = Judge::where([
                'event_id' => $request->get('eventId'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'status' => true
            ])->first();

            if(isset($judge->id)){
                $event = Event::find($request->get('eventId'));
                // Get project list
                //get all available project with votes
                $categories = Category::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->get();

                //Get categories
                $criterias = Criteria::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->get();

                //get all available project with votes
                $projects = Project::where([
                    'status' => 1,
                    'event_id' => $request->get('eventId')
                ])->with('votes')->get();

                foreach ($projects as $key => $value) {
                    //get all votes project to the user 
                    $votes = $value->votes()->where(['user_id'=>$judge->id])->get();
                    //check if project voted or not
                    $value->logo = asset('/assets/uploads/projects/'.$value->logo);
                    if(count($votes) >0){
                        $note_criteria =  0; $somme_coefficient = 0;
                        foreach ($votes as $vote) {
                             //get criteria of each note
                            if($criteria = Criteria::find($vote->pivot->criteria_id)){
                                $note = $vote->pivot->note ? $vote->pivot->note : 0;
                                $note_criteria = $note_criteria + ($note  * $criteria->coefficient);
                                $somme_coefficient =  $somme_coefficient + $criteria->coefficient;
                            };
                        }
                        $value->vote = ($somme_coefficient > 0) ? $note_criteria / $somme_coefficient : 0;
                    }else{
                        $value->vote  = -1;
                    }
                }

                //$note_setting = NoteSetting::first() ? NoteSetting::first()->pluck('somme')->get(0) : -1;

                return response()
                    ->json([
                        'status' => true, 
                        'data' => [
                            'userId' => $judge->id,
                            'total'=>$event->scale,
                            'projects'=>$projects->makeHidden(['color','created_at','updated_at','deleted_at','status','votes','country']),
                            'categories' => $categories->makeHidden(['created_at','updated_at','deleted_at','status','country','scale']),
                            'criterias'=>$criterias->makeHidden(['created_at','updated_at','deleted_at','status','percentage_id','country'])
                        ]
                    ],200, 
                    [], 
                    JSON_NUMERIC_CHECK
                );
                
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to authenticate Judge',
                    'data' => null,
                    'error' => []
                ], 200, 
                [], 
                JSON_NUMERIC_CHECK
                );
            }

        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to authenticate Judge',
                'data' => null,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 200);
        }
    }

    public function save_vote_ws(Request $request){
        try {
            $this->validate($request, [
                'notes' => 'required',
                'userId' => 'required',
                'eventId' => 'required|integer|exists:events,id',
                'projectId' => 'required|integer|exists:projects,id',
            ]);

            $event = Event::findOrFail($request->get('eventId'));
            if(!isset($event->id))
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Evènement introuvable.')],203);

            if($event->auth_type == 'code')
                $user = Voter::findOrFail($request->get('userId'));
            else
                $user = Judge::findOrFail($request->get('userId'));

            $project = Project::where(['id' => $request->get('projectId'), 'event_id' => $event->id])->first();
            if(!isset($project->id))
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Projet introuvable.')],203);

            $projectId = $project->id;
 
            if(isset($user->id)){
                if($user->status == 1){
                    $user->votes()->detach();
                    foreach ($request->input('notes') as $id_criteria => $value) {
                        if($criteria = Criteria::find($id_criteria)){
                            if($value >= 0){
                                $user->votes()->attach([
                                    $projectId => [
                                        'note' => $value,
                                        'criteria_id' => $id_criteria
                                    ]
                                ]);
                            }
                        }else{
                            return response()
                            ->json(['status' => false, 'data' => array('error'=>'Critére introuvable.')],203);
                        }
                    }
                    return response()
                    ->json(['status' => true],200);
                }else{
                    return response()
                    ->json(['status' => false, 'data' => array('error'=>'Compte bloqué.')],203);
                }
            } else {
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Utilisateur introuvable.')],203);
            }

        }catch( \Illuminate\Validation\ValidationException $e ){
            return response()
            ->json(['status' => false, 'data' => array('error'=>'Un paramètre manquant.')],203);
        } catch (\Exception $e) {
            return response()
            ->json(['status' => false, 'data' => array('error'=>'Une erreur s\'est produite.'.$e->getMessage()())],500);
        } 
    }


    public function save_question(Request $request){

        try {
            $this->validate($request, [
               'question'=> 'required|string',
               'eventId' => 'required|integer|exists:events,id',
               'projectId'=> 'required|integer|exists:projects,id',
               'userId'=> 'required|integer'
            ]);  
            $event = Event::findOrFail($request->get('eventId'));
            if(!isset($event->id))
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Evènement introuvable.')],203);

            if($event->auth_type == 'code')
                $user = Voter::findOrFail($request->get('userId'));
            else
                $user = Judge::findOrFail($request->get('userId'));

            $project = Project::where(['id' => $request->get('projectId'), 'event_id' => $event->id])->first();
            if(!isset($project->id))
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Projet introuvable.')],203);

            $projectId = $project->id; 

            if(isset($user->id)){
                if($user->status == 1){
                    $user->questions()->attach([request('projectId') =>['question'=>request('question'), 'event_id' => request('eventId')]]);
                    return response()
                    ->json(['status' => true],200);
                }else{
                    return response()
                    ->json(['status' => false, 'data' => array('error'=>'Compte bloqué.')],203);
                }
            }else{
                return response()
                ->json(['status' => false, 'data' => array('error'=>'Utilisateur introuvable.')],203);
            }

        }catch( \Illuminate\Validation\ValidationException $e ){
            return response()
            ->json(['status' => false, 'data' => array('error'=>'Un paramétre manquant.'.$e->getMessage())],203);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
