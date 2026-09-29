<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Helpers\Common_helpers;

class RoleController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $roles = Role::query();
        if(!empty($request->input('search')))
        {
            $search = $request->input('search');
            $roles->where('name', 'LIKE', "%$search%");
        }
        $roles = $roles->orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $permission = Permission::query()->orderBy('name', 'ASC')->get();

        // echo "<pre>";
        // print_r($permission->toArray());
        // $permission->toArray();
        // die;

        return view('roles.create',compact('permission'));
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
        $this->validate($request, [
            'name' => 'required|regex:/^[a-z A-Z]+$/u|unique:roles,name',
            'permission' => 'required',
        ],
        [
            'name.required' => 'Name can’t be empty',
            'name.regex' => 'Invalid Name Format',
            'permission.required' => 'Permission can’t be empty',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
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
        //
        //print_r($id);die;
        $role = Role::find($id);
        $permission = Permission::query()->orderBy('name', 'ASC')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions'));
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
        //
        $this->validate($request, [
            'name' => 'required|regex:/^[a-z A-Z]+$/u|unique:roles,name,'.$id,
            'permission' => 'required',
        ],
        [
            'name.required' => 'Name can’t be empty',
            'name.regex' => 'Invalid Name Format',
            'permission.required' => 'Permission can’t be empty',
        ]);
    
        $role = Role::find($id);
        
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //DB::table("roles")->where('id',$id)->delete();
        $logged_user_id = loggedin_admin('id');
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        DB::table('roles')->where('id',$id)->update($update_array);
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
