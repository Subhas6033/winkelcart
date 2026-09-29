<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserInfo;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use App\Helpers\Common_helpers;

class UserController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
        $this->middleware('permission:user-create|user-edit', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $roleFilter = $request->input('role');
        $status     = $request->input('status');
        $approved   = $request->input('approved');

        $data = User::with('user_info')->where('deleted', '0');

        if (!empty($search)) {
            $data->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%")
                  ->orWhere('mobile', 'LIKE', "%$search%");
            });
        }

        if (!empty($roleFilter)) {
            $data->role($roleFilter);
        }

        if ($status !== null && $status !== '') {
            $data->where('status', $status);
        }

        if ($approved !== null && $approved !== '') {
            $data->where('is_seller', $approved);
        }

        $roles = \Spatie\Permission\Models\Role::whereNotIn('id', [15,16,17])->pluck('name', 'id');

        $data = $data->orderBy('id', 'DESC')->paginate(20);

        return view('users.index', compact('data', 'roles', 'search', 'roleFilter', 'status', 'approved'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereNotIn('id', [15,16,17])->pluck('name','id');
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(empty($request->input('user_id')))
        {
            ///// for add mode
            $this->validate($request, [
                'name' => 'required|regex:/^[a-z A-Z1-9 0]+$/u|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'roles' => 'required'
            ],
            [
                'name.required' => 'Name can’t be empty',
                'name.regex' => 'Invalid Name Format',
                'email.required' => 'Email can’t be empty',
                'email.email' => 'Entered an invalid email address',
                'email.unique' => 'Email already in use',
                'password.required' => 'Password can’t be empty',
                'password.same' => 'Passwords does not match',
                'roles.required' => 'Please select role',
            ]);
        }
        else
        {
            //////for edit mode
            $this->validate($request, [
                'name' => 'required|regex:/^[a-z A-Z1-9 0]+$/u|max:255',
                'email' => 'required|email|unique:users,email,'.$request->input('user_id'),
                'confirm-password' => 'required_with:password|same:password',
                'roles' => 'required'
            ],
            [
                'name.required' => 'Name can’t be empty',
                'name.regex' => 'Invalid Name Format',
                'email.required' => 'Email can’t be empty',
                'email.email' => 'Entered an invalid email address',
                'email.unique' => 'Email already in use',
                'confirm-password.required_with' => 'Confirm password can’t be empty',
                'confirm-password.same' => 'Passwords does not match',
                'roles.required' => 'Please select role',
            ]);
        }
        // $logged_user_id = loggedin_admin('id');
        //echo "<pre>";print_r($_POST);die;
        $input = $request->all();
        if(empty($request->input('user_id')))
        {
            $input['password'] = Hash::make($input['password']);            
            $user = User::create($input);
            $msg = 'created';
        }
        else
        {
            if(!empty($input['password'])){ 
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));    
            }
            $user = User::find($input['user_id']);
            $user->update($input);
            
            $user_info = UserInfo::where('user_id', $input['user_id'])->first();
            if ($user_info) {
                $user_info->update([
                    'address' => $request->input('address'),
                ]);
            } else {
                UserInfo::create([
                    'user_id' => $input['user_id'],
                    'address' => $request->input('address'),
                ]);
            }

            DB::table('model_has_roles')->where('model_id',$input['user_id'])->delete();
            $msg = 'updated';
        }
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
                        ->with('success','User '.$msg.' successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('user_info')->findOrFail($id);        
        $roles = Role::whereNotIn('id', [15,16,17])->pluck('name','id');
        $userRole = $user->roles->pluck('id','name')->all();
        // print_r($user->toArray());die;
        return view('users.create',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'name' => "required|regex:/^[a-zA-Z]+$/u|max:255",
    //         'email' => 'required|email|unique:users,email,'.$id,
    //         'password' => 'required|same:confirm-password',
    //         'roles' => 'required'
    //     ],
    //     [
    //         'name.required' => 'Name can’t be empty',
    //         'name.regex' => 'Invalid Name',
    //         'email.required' => 'Email can’t be empty',
    //         'email.email' => 'Entered an invalid email address',
    //         'email.unique' => 'Email already in use',
    //         'password.required' => 'Password can’t be empty',
    //         'password.same' => 'Password does not match',
    //         'roles.required' => 'Please select role',
    //     ]);
    
    //     $input = $request->all();
    //     if(!empty($input['password'])){ 
    //         $input['password'] = Hash::make($input['password']);
    //     }else{
    //         $input = Arr::except($input,array('password'));    
    //     }
    
    //     $user = User::find($id);
    //     $user->update($input);
    //     DB::table('model_has_roles')->where('model_id',$id)->delete();
    
    //     $user->assignRole($request->input('roles'));
    
    //     return redirect()->route('users.index')
    //                     ->with('success','User updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $logged_user_id = loggedin_admin('id');
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        User::where("id",$id)->update($update_array);
        //$result = User::where("id","=",$id)->delete();
        // var_dump($result);die;
        //dd(\DB::getQueryLog());
        // echo $id ;die;
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
    public function user_unique_email_check(Request $request)
    {
        $count_val=0;
        if(!empty($_POST['email']))
        {
            $email = $_POST['email'];
            $is_exists = User::where("email",$email);
            if(!empty($_POST['asp_user_id']))
            {
                $is_exists->where("id",'!=',$_POST['asp_user_id']);
            }
            $res=$is_exists->get();
            $count_val = count($res);
        }
        //echo "<pre>";print_r($res);
        echo $count_val;

    }
    public function user_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = User::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'0');
                User::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }

    public function user_seller_approval(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = User::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->is_seller==0)?array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'is_seller'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'is_seller'=>'0');
                User::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->is_seller==0)?1:0;
                echo $msg;
            }
        }
    }
}
