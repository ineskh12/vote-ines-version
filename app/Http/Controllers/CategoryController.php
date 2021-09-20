<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("pages.categories.index");
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->is_super_admin)
            $events =  Event::where(['status'=>1])->get();
        else
            $events =  Event::where(['status'=>1,'country'=>$user->country])->get();
        return view("pages.categories.create", compact("events"));
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
            $user = Auth::user();
            $category =  Category::findOrFail($id);
            if($user->is_super_admin)
                $events =  Event::where(['status'=>1])->get();
            else
                $events =  Event::where(['status'=>1,'country'=>$user->country])->get();
            return view("pages.categories.edit", compact("category","events"));
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
                $categories = Category::where(['event_id' => $request->input('event_id')])->get();
            else
                $categories = Category::where(['event_id' => $request->input('event_id'), 'country' => $user->country])->get();
        } else {
            if($user->is_super_admin)
                $categories = Category::where(['status' => 1])->get();
            else
                $categories = Category::where(['status' => 1, 'country' => $user->country])->get();
        }

        return Datatables::of($categories)
            ->editColumn('event_id', function($category) {
                if($category->event->lang != ''){
                    $eventTitle = '';
                    if($category->event->lang == 'fr')
                        $eventTitle = $category->event->title_fr;
                    if($category->event->lang == 'ar')
                        $eventTitle = $category->event->title_ar;
                    if($category->event->lang == 'en')
                        $eventTitle = $category->event->title_en;
                    return Str::limit($eventTitle, $limit = 40, $end = '...');
                }else{
                    return '--';
                }
            })
            ->addColumn('titre', function($category) {
                if($category->event->lang != ''){
                    $title = '';
                    if($category->event->lang == 'fr')
                        $title = $category->titre_fr;
                    if($category->event->lang == 'ar')
                        $title = $category->titre_ar;
                    if($category->event->lang == 'en')
                        $title = $category->titre_en;
                    return Str::limit($title, $limit = 40, $end = '...');
                }else{
                    return $category->titre_fr;
                }
            })
            ->editColumn('status', function($category) {
                $status = ($category->status == 1) ? 'checked' : '';
                return '<center><input type="checkbox" '.$status.' data-height="23" data-id="'.$category->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default"></center>';
            })
            ->editColumn('created_at', function($event) {
            	if($event->created_at != ''){
            		return $event->created_at->format('d-m-Y');
            	}else{
            		return '--';
            	}
            })
            ->addColumn('actions', function($category) {
                return '<center><a href="'.route('categories.edit',array('id' => $category->id)).'">
                            <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce criteria">
                               <i class="fa fa-edit"></i>
                            </button>
                        </a>
                        <a href="javascript:;" onClick="remove(\'categories\','.$category->id.')">
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
            'event_id' => 'required|integer|exists:events,id',
            'coefficient' => 'required|integer'
        ));


        try {

            $category = new Category();
            if($request->filled('titre_en'))
                $category->titre_en = $request->input('titre_en');
            if($request->filled('titre_fr'))
                $category->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $category->titre_ar = $request->input('titre_ar');

            $category->coefficient = $request->input('coefficient');
            $category->scale = $request->input('scale');
            $category->event_id = $request->input('event_id');
            $event = Event::findOrFail($request->input('event_id'));
            $category->country = $event->country;
            $category->save();


            return redirect()->back()
                ->with('success','La catégorie a été ajoutée avec succès. Retournez a la liste <a href="'.route('categories.index').'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la catégorie n\'était pas ajoutée. Merci de réessayer.'.$e->getMessage());
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
            'event_id' => 'required|integer|exists:events,id',
            'coefficient' => 'required|integer'
        ));

        try {

            $category = Category::findOrFail($id);
            if($request->filled('titre_en'))
                $category->titre_en = $request->input('titre_en');
            if($request->filled('titre_fr'))
                $category->titre_fr = $request->input('titre_fr');
            if($request->filled('titre_ar'))
                $category->titre_ar = $request->input('titre_ar');

            $category->coefficient = $request->input('coefficient');
            //$category->scale = $request->input('scale');
            $category->event_id = $request->input('event_id');
            $category->save();


            return redirect()->back()
                ->with('success','La catégorie a été modifiée avec succès. Retournez a la liste <a href="'.route('categories.index').'">Ici</a>.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, la catégorie n\'était pas modifiée. Merci de réessayer.');
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
            $category = Category::findOrFail($request->input('id'));
            if($category->status ==  0){
                $category->status = 1;
            }else{
                $category->status = 0;
            }
            $category->save();
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
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }


}
