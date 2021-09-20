<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;

use App\Models\User;

class AdminController extends Controller
{
   
    /**
     * Show the form for update admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        try {
            $admin = Auth::user();
            return view("pages.account", compact('admin'));
        } catch (\Exception $e) {
            return view("pages.error");
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.admins.create");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->is_super_admin == false)
            return redirect()->route('admin.dashboard');
        return view("pages.admins.index");
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
            if(Auth::user()->is_super_admin == false)
                return redirect()->route('admin.dashboard');
                
            $admin =  User::findOrFail($id); 
            return view("pages.admins.edit", compact("admin"));
        } catch (\Exception $e) {
           return view("pages.error");
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        if(Auth::user()->is_super_admin == false)
            return redirect()->route('admin.dashboard');

        if($id){
            $msg_ok = 'L\'admin a été modifié avec succès. Retournez a la liste <a href="'.route('admins.index').'">Ici</a>.';
            $msg_ko = 'l\'admin';
        }else{
            $id = Auth::user()->id;
            $msg_ok = 'Votre profil a été modifié avec succès.';
            $msg_ko = 'votre profil';
        }

        $rules = array(
            'avatar' => 'mimes:jpeg,jpg,png',
            'nom' => 'required',
            'prenom'  => 'required',
            'email'  => 'required|unique:users,email,'.$id
        );

        if($request->input('password')){
            $rules["password"] = "required|between:6,20|confirmed";
            $rules["password_confirmation"] = "between:6,20";
        }

        $this->validate($request, $rules);

        try {
            $admin = User::where('is_admin',1)->findOrFail($id);
            /*** if file exist in the request ***/
            if($request->file('avatar')) {
                $file = $request->file('avatar');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/admins/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70, 70);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/admins');
                $file->move($destinationPath, $filename);

                // unlink old images //
                File::delete(public_path() . '/assets/uploads/admins/'.$admin->avatar);
                File::delete(public_path() . '/assets/uploads/admins/thumbs/'.$admin->avatar);

                // affect image to user object
                $admin->avatar = $filename;
            }

            $admin->name = $request->input('nom');
            $admin->prenom = $request->input('prenom');
            $admin->email = $request->input('email');
            if($request->filled('country'))
                $admin->country = $request->input('country');
            
            if($request->input('password'))
                $admin->password = Hash::make($request->input('password'));
            $admin->save();

            return redirect()->back()
                ->with('success',$msg_ok.' '); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, '.$msg_ko.' n\'était pas modifiée. Merci de réessayer.');  
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
        if(Auth::user()->is_super_admin == false)
            return redirect()->route('admin.dashboard');

        $this->validate($request, array(
            'avatar' => 'mimes:jpeg,jpg,png',
            'nom' => 'required',
            'prenom'  => 'required',
            'email'  => 'required|unique:users,email',
            'password'  => 'required|between:6,20|confirmed',
            'password_confirmation'  => 'between:6,20',
            'country' => 'required'
        ));

        try {
            $admin = new User();
            /*** if file exist in the request ***/
            if($request->file('avatar')) {
                $file = $request->file('avatar');
                $filename = time().'.'.$file->getClientOriginalExtension();

                // thumbs image //
                $destinationPath = public_path('/assets/uploads/admins/thumbs');
                $thumb_img = Image::make($file->getRealPath())->resize(70, 70);
                $thumb_img->save($destinationPath.'/'.$filename,80);

                // real image //
                $destinationPath = public_path('/assets/uploads/admins');
                $file->move($destinationPath, $filename);

                // affect image to user object
                $admin->avatar = $filename;
            }

            $admin->name = $request->input('nom');
            $admin->prenom = $request->input('prenom'); 
            $admin->email = $request->input('email');
            if($request->filled('country'))
                $admin->country = $request->input('country');
            $admin->password = Hash::make($request->input('password'));
            $admin->is_admin = 1;
            $admin->save();

            return redirect()->back()
                ->with('success','L\'admin a été ajouté avec succès. Retournez a la liste <a href="'.route('admins.index').'">Ici</a>.'); 

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Un erreur s\'est produite, L\'admin n\'était pas ajouté. Merci de réessayer.'.$e->getMessage());  
        } 
    }





    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
        if(Auth::user()->is_super_admin == false)
            return redirect()->route('admin.dashboard');
            
        $admins = User::where('id','!=', Auth::user()->id)->where('is_admin',1)->get();
        //$admins = User::where('is_admin',1)->get();
        return Datatables::of($admins)
            ->editColumn('country', function($admin) {
                if($admin->country != '') {
                    return '<span style ="font-size:xxx-large;" class="flag-icon flag-icon-'.$admin->country.'"></span>';
                } else return '<span class="flag-icon flag-icon-all"></span>';
            })
            ->editColumn('avatar', function($admin) {
                if ($admin->avatar != '' && File::exists(public_path().'/assets/uploads/admins/'.$admin->avatar))
                {
                    return '<img src="'.asset('/assets/uploads/admins/thumbs/'.$admin->avatar).'" style="width:70px; height:70px"/>';
                }else{
                    return '<img src="'.asset('/assets/uploads/avatar.png').'" class="img-circle" style="width:70px; height:70px"/>';
                }
            })
            ->editColumn('status', function($admin) {
                $status = ($admin->status == 1) ? 'checked' : '';
                return '<input type="checkbox" '.$status.' data-height="23" data-id="'.$admin->id.'" class="make-switch" data-on="success"  data-on-text="Active" data-off-text="Inactive" data-on-color="success" data-off-color="default">';
            })
            ->addColumn('actions', function($admin) {
                return '<center><a href="'.route('admins.edit',array('id' => $admin->id)).'">
                            <button type="button" class="tooltips btn btn-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Modifier ce admin">
                               <i class="fa fa-edit"></i>
                            </button>
                        </a>
                        <a href="javascript:;" onClick="remove(\'admins\','.$admin->id.')">
                            <button type="button" class="tooltips btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" data-original-title="Suprrimer ce admin">
                              <i class="fa fa-times"></i>
                            </button>
                        </a></center>';
            })
            ->editColumn('created_at', function($event) {
            	if($event->created_at != ''){
            		return $event->created_at->format('d-m-Y H:i');	
            	}else{
            		return '--';
            	}
            })
	        ->rawColumns(['country', 'actions', 'status', 'avatar'])
            ->make(true);
    }



    /**
     * Change the status of the admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        try {
            $admin = User::where('is_admin', 1)->findOrFail($request->input('id'));
            if($admin->status ==  0){
                $admin->status = 1;
            }else{
                $admin->status = 0;
            }
            $admin->save();
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
            $admin = User::where('is_admin', 1)->findOrFail($id);
            // unlink old images //
            File::delete(public_path() . '/_admin/pages/uploads/admins/'.$admin->avatar);
            File::delete(public_path() . '/_admin/pages/uploads/admins/thumbs/'.$admin->avatar);
            $admin->delete();
            return response()->json(true);
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }


    /**
     * Change the email of the admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_email(Request $request){

        try {
            if($id = $request->input('id_admin')){
                $count = User::where("id","!=",$id)->where('email', $request->input('email'))->count();
            }else{
                $count = User::where('email', $request->input('email'))->count();
            }
            if($count > 0 ){
                return response()->json(false);
            }else{
                return response()->json(true);
            }
        } catch (\Exception $e) {
             return response()->json(false);
        }
    }




}
