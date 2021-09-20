<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Models\Criteria;
use App\Models\Percentage;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("pages.criterias.index");
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->is_super_admin){
            $percentages = Percentage::where(['status'=>1])->get();
            $categories = Category::where(['status' => 1])->get();
        } else {
            $percentages = Percentage::where(['status'=>1,'country' => $user->country])->get();
            $categories = Category::where(['status' => 1, 'country' => $user->country])->get();
        }
        foreach ($categories as &$category) {
            $eventTitle = $category->titre_fr;
            switch ($category->event->lang) {
                case 'fr':
                    $eventTitle = $category->event->title_fr.' - '.$category->titre_fr;
                    break;
                case 'en':
                    $eventTitle = $category->event->title_en.' - '.$category->titre_en;
                    break;
                case 'ar':
                    $eventTitle = $category->event->title_ar.' - '.$category->titre_ar;
                    break;
                default:
                    $eventTitle = $category->titre_fr;
                    break;
            }
            $category['name'] = $eventTitle;
        }
        return view("pages.criterias.create", compact("percentages", "categories"));
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
            $criteria =  Criteria::findOrFail($id);
            $user = Auth::user();
            if($user->is_super_admin){
                $percentages = Percentage::where(['status'=>1,'type'=>1])->get();
                $categories = Category::where(['status' => 1])->get();
            } else {
                $percentages = Percentage::where(['status'=>1,'type'=>1, 'country' => $user->country])->get();
                $categories = Category::where(['status' => 1, 'country' => $user->country])->get();
            }
            
            foreach ($categories as &$category) {
                $eventTitle = $category->titre_fr;
                switch ($category->event->lang) {
                    case 'fr':
                        $eventTitle = $category->event->title_fr.' - '.$category->titre_fr;
                        break;
                    case 'en':
                        $eventTitle = $category->event->title_en.' - '.$category->titre_en;
                        break;
                    case 'ar':
                        $eventTitle = $category->event->title_ar.' - '.$category->titre_ar;
                        break;
                    default:
                        $eventTitle = $category->titre_fr;
                        break;
                }
                $category['name'] = $eventTitle;
            }
            return view("pages.criterias.edit", compact("criteria","percentages","categories"));
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
        if($request->filled('event_id')){
            if($user->is_super_admin)
                $criterias = Criteria::where(['event_id' => $request->input('event_id')])->get();
            else
                $criterias = Criteria::where(['event_id' => $request->input('event_id'), 'country' => $user->country])->get();
        } else {
            if($user->is_super_admin)
                $criterias = Criteria::all();
            else
                $criterias = Criteria::where(['country' => $user->country])->get();
        }

        return Datatables::of($criterias)
            ->editColumn('event_id', function($criteria) {
                if($criteria->event->lang != ''){
                    $eventTitle = '';
                    if($criteria->event->lang == 'fr')
                        $eventTitle = $criteria->event->title_fr;
                    if($criteria->event->lang == 'ar')
                        $eventTitle = $criteria->event->title_ar;
                    if($criteria->event->lang == 'en')
                        $eventTitle = $criteria->event->title_en;
                    return Str::limit($eventTitle, $limit = 40, $end = '...');	
                }else{
                    return '--';
                }
            })
            ->addColumn('titre', function($criteria) {
                if($criteria->event->lang != ''){
                    $title = '';
                    if($criteria->event->lang == 'fr')
                        $title = $criteria->titre_fr;
                    if($criteria->event->lang == 'ar')
                        $title = $criteria->titre_ar;
                    if($criteria->event->lang == 'en')
                        $title = $criteria->titre_en;
                    return Str::limit($title, $limit = 40, $end = '...');	
                }else{
                    return $criteria->titre_fr;
                }
            })
            ->editColumn('status', function($criteria) {
                $status = ($criteria->status == 1) ? 'checked' : '';
                return '<center><input type="checkbox" '.$status.' data-height="23" data-id="'.$criteria->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default"></center>';
            })
            ->editColumn('percentage_id', function($criteria) {
                if($percentage = Percentage::withTrashed()->find($criteria->percentage_id)){
                    return $percentage->titre_fr;
                }else{
                    return '--';
                }
            })
            ->addColumn('category', function($criteria) {
                if($criteria->event->lang != ''){
                    $title = '';
                    if($criteria->event->lang == 'fr')
                        $title = $criteria->category->titre_fr;
                    if($criteria->event->lang == 'ar')
                        $title = $criteria->category->titre_ar;
                    if($criteria->event->lang == 'en')
                        $title = $criteria->category->titre_en;
                    return Str::limit($title, $limit = 40, $end = '...');	
                }else{
                    return '--';
                }
            })
            ->addColumn('actions', function($criteria) {
                return '<center><a href="'.route('criterias.edit',array('id' => $criteria->id)).'">
                            <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce criteria">
                               <i class="fa fa-edit"></i>
                            </button>
                        </a>
                        <a href="javascript:;" onClick="remove(\'criterias\','.$criteria->id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce criteria">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center>';
	    })
	    ->rawColumns(['actions', 'status'])
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
            'percentage_id' => 'required|exists:percentages,id',
            'coefficient' => 'required',
            'category_id' => 'required'
        ));

        
        try {

            $criteria = new Criteria();
            if($request->filled('titre_fr'))
                $criteria->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $criteria->titre_ar = $request->input('titre_ar');
            if($request->filled('titre_en'))
                $criteria->titre_en = $request->input('titre_en');

            $categoryId = $request->input('category_id');

            $criteria->coefficient = $request->input('coefficient');
            $criteria->percentage_id = $request->input('percentage_id');
            $criteria->category_id = explode('-', $categoryId)[0];
            $criteria->event_id = explode('-', $categoryId)[1];
            $event = Event::findOrFail($criteria->event_id);
            $criteria->country = $event->country;
            $criteria->save();


            return redirect()->back()
                ->with('success','La critère a été ajoutée avec succès. Retournez a la liste <a href="'.route('criterias.index').'">Ici</a>.'); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la critère n\'était pas ajoutée. Merci de réessayer.'.$e->getMessage());  
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
            'percentage_id' => 'required|exists:percentages,id',
            'coefficient' => 'required',
            'category_id' => 'required'
        ));

        
        try {

            $criteria = Criteria::findOrFail($id);
            if($request->filled('titre_fr'))
                $criteria->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $criteria->titre_ar = $request->input('titre_ar');
            if($request->filled('titre_en'))
                $criteria->titre_en = $request->input('titre_en');

            $categoryId = $request->input('category_id');

            $criteria->coefficient = $request->input('coefficient');
            $criteria->percentage_id = $request->input('percentage_id');
            $criteria->category_id = explode('-', $categoryId)[0];
            $criteria->event_id = explode('-', $categoryId)[1];
            $criteria->save();


            return redirect()->back()
                ->with('success','La critère a été modifiée avec succès. Retournez a la liste <a href="'.route('criterias.index').'">Ici</a>.'); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la critère n\'était pas modifiée. Merci de réessayer.');  
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
            $criteria = Criteria::findOrFail($request->input('id'));
            if($criteria->status ==  0){
                $criteria->status = 1;
            }else{
                $criteria->status = 0;
            }
            $criteria->save();
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
            $criteria = Criteria::findOrFail($id);
            $criteria->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }


}
