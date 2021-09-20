<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;

use App\Http\Requests;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{

    public function index()
    {
        $test='<span>okkkkkkkkkkkkkkkkkkk</span>';


        return view("pages.questions.index",['test'=>$test]);
    }






    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        $user = Auth::user();
        $projects = Project::where(['country' => $user->country, 'status' => 1])->with('questions')->get()->pluck('questions')->flatten();
        return Datatables::of($projects)
            ->editColumn('title_fr', function($project) {
                return $project->titre_fr;
            })
            ->editColumn('question', function($project) {
            	return  $project->pivot->question;

            })
            ->editColumn('status', function($project) {

     return '<span>ok</span>';

            })
            // ->editColumn('status', function($project) {
            //     // $s = ($project->pivot->status == 0) ? "danger" : "info";
            //     // $v = ($project->pivot->status == 0) ? "No valider" : "Valider";
            //     // return ' jjjj<label class="label label-'.$s.'">'.$v.'</label>';

            //     $s = ($project->pivot->status == 0) ? "danger" : "info";
            //     $v = ($project->pivot->status == 0) ? "No valider" : "Valider";

            //     return  '<label class="label label-'.$s.'">'.$v.'</label>' ;
            // })


            ->editColumn('created_at', function($event) {
            	if($event->created_at != ''){
            		return $event->created_at->format('d-m-Y H:i');
            	}else{
            		return '--';
            	}
            })

            ->addColumn('actions', function($project) {

                $validate ='';

                if($project->pivot->status == 0){
                    $validate ='<a href="'.route('questions.validate',array('id' => $project->pivot->id)).'">
                                <button type="button" class="tooltips btn btn-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="valider ce question">
                                   <i class="fa fa-check"></i>
                                </button>
                            </a> ';
                }


                return '<center>'.$validate.'
                        <a href="javascript:;" onClick="remove(\'questions\','.$project->pivot->id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce question">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center> kjhzdk';
            })
            ->make(true);
    }


    public function destroy($id)
    {
        try {
            DB::table('questions')->where('id', $id)->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }

    public function validateQuestion($id){
        DB::table('questions')->where('id', $id)->update(['status' => 1]);
        return back();
    }
    public function invalidateQuestion($id){
        DB::table('questions')->where('id', $id)->update(['status' => 0]);
        return back();
    }

}
