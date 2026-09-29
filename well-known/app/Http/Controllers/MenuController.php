<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use DB;
use App\Helpers\Common_helpers;

class MenuController extends Controller
{   
    function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:menu-list|menu-create|menu-edit|menu-delete', ['only' => ['index','show']]);
        $this->middleware('permission:menu-create', ['only' => ['create','store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $menus = Menu::latest();
        if(!empty($request->input('search')))
        {
            $search = $request->input('search');
            $menus->where('menu_title', 'LIKE', "%$search%");
        }
        $menus = $menus->orderBy('id','DESC')->paginate(10);
        return view('menus.index',compact('menus'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("menus.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'menu_title' => 'required|regex:/^[a-z A-Z]+$/u|max:255',
            'urls' => 'required',
        ],
        [
            'menu_title.required' => 'Name can’t be empty',
            'menu_title.regex' => 'Invalid name format',
            'urls.required' => 'Email can’t be empty',
        ]);
    
        //Menu::create($request->all());
        $menu = new Menu;
        $menu->menu_title = $request->menu_title;
        $menu->urls = $request->urls;
        if(empty($request->menu_id))
        {
            if($menu->save()){
                $urls = explode(',', $request->urls);
                $data = array();
                foreach($urls as $url){
                    $node = array(
                        'menu_id' => $menu->id,
                        'name' => $url,
                        'guard_name' => 'web',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    array_push($data, $node);
                }
                DB::table('permissions')->insert($data);
            }
            $msg = "created";
        }
        else
        {
            $insert_array['updated_at']=current_datetime();
            $insert_array['updated_by']=loggedin_admin('id');
            $insert_array['menu_title']=$menu->menu_title;
            $insert_array['urls']=$menu->urls;
            
            // $menu = Menu::find($request->menu_id);
            // $menu->update(array($insert_array));
            DB::table('menus')->where('id',$request->menu_id)->update($insert_array);
            $msg = "updated";
        }
            return redirect()->route('menus.index')->with('success','Menu '.$msg.' successfully.');
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
    public function edit(Menu $menu)
    {
        //
        return view('menus.create',compact('menu'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //

    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')
                ->with('success','Menu deleted successfully');
    }
    public function menu_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Menu::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_at'=>current_datetime(),'status'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'0');
                Menu::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
}
