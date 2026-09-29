<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','show']]);
        $this->middleware('permission:category-create|category-edit', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $data = Category::where("deleted","0");
        if(!empty($request->input('search')))
        {
            $search = $request->input('search');
            $data->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
                if (Schema::hasColumn('categories', 'desc')) {
                    $query->orWhere('desc', 'LIKE', "%$search%");
                }
            });
        }
        $data = $data->orderBy('id','DESC')->paginate(25);
        return view('categories.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        return view('categories.create');
    }
    public function store(Request $request)
    {
        $categoryId = $request->input('category_id'); // edit mode check
        $rules = [
            'name' => [
                'required',
                'max:255',
                // unique in `categories` table, ignore soft deleted, ignore current id on update
                \Illuminate\Validation\Rule::unique('categories', 'name')->ignore($categoryId)->where(function ($query) {
                    $query->where('deleted', 0); // If you are using a 'deleted' flag
                }),
            ],
        ];

        $messages = [
            'name.required' => 'Name can’t be empty',
            'name.unique'   => 'This name is already taken',
        ];

        $this->validate($request, $rules, $messages);

        $logged_user_id = loggedin_admin('id');
        $input = $request->all();
        $insert = [
            'name' => $input['name'],
        ];

        if (Schema::hasColumn('categories', 'desc')) {
            $insert['desc'] = $input['desc'] ?? null;
        }

        if (empty($categoryId)) {
            // Add mode
            $insert['status'] = 0;
            $insert['deleted'] = 0;
            if (Schema::hasColumn('categories', 'created_by')) {
                $insert['created_by'] = $logged_user_id;
            }
            if (Schema::hasColumn('categories', 'created_at')) {
                $insert['created_at'] = current_datetime();
            }
            Category::create($insert);
            $msg = 'created';
        } else {
            // Edit mode
            if (Schema::hasColumn('categories', 'updated_by')) {
                $insert['updated_by'] = $logged_user_id;
            }
            if (Schema::hasColumn('categories', 'updated_at')) {
                $insert['updated_at'] = current_datetime();
            }
            $category = Category::find($categoryId);
            $category->update($insert);
            $msg = 'updated';
        }

        return redirect()->route('categories.index')
                        ->with('success', 'Category ' . $msg . ' successfully');
    }
    // public function store(Request $request)
    // {
    //     //
    //     if(empty($request->input('category_id')))
    //     {
    //         ///// for add mode
    //         $this->validate($request, [
    //             'name' => 'required|max:255',
    //         ],
    //         [
    //             'name.required' => 'Name can’t be empty',
    //         ]);
    //     }
    //     else
    //     {
    //         //////for edit mode
    //         $this->validate($request, [
    //             'name' => 'required|max:255',
    //         ],
    //         [
    //             'name.required' => 'Name can’t be empty',
    //         ]);
    //     }
    //     $logged_user_id = loggedin_admin('id');
    //     $input = $request->all();
    //     $insert['name'] = $input['name'];
    //     $insert['desc'] = $input['desc'];
    //     if(empty($request->input('category_id')))
    //     {
    //         $insert['status'] = 0;
    //         $insert['deleted'] = 0;
    //         $insert['created_by'] = $logged_user_id;
    //         $insert['created_at'] = current_datetime();
    //         $category = Category::create($insert);
    //         $msg = 'created';
    //     }
    //     else
    //     {
    //         $insert['updated_by'] = $logged_user_id;
    //         $insert['updated_at'] = current_datetime();
    //         $category = Category::find($input['category_id']);
    //         $category->update($insert);
    //         $msg = 'updated';
    //     }
    //     return redirect()->route('categories.index')
    //                     ->with('success','Category '.$msg.' successfully');
    // }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
        $category = Category::find($id);
        return view('categories.create',compact('category'));
    }
    public function destroy($id)
    {
        $logged_user_id = loggedin_admin('id');
        $update_array = array('deleted' => '1');
        if (Schema::hasColumn('categories', 'updated_at')) {
            $update_array['updated_at'] = current_datetime();
        }
        if (Schema::hasColumn('categories', 'updated_by')) {
            $update_array['updated_by'] = $logged_user_id;
        }
        Category::where("id",$id)->update($update_array);
        //$result = User::where("id","=",$id)->delete();
        // var_dump($result);die;
        //dd(\DB::getQueryLog());
        // echo $id ;die;
        return redirect()->route('categories.index')
                        ->with('success','Category deleted successfully');
    }
    public function category_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Category::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = array('status' => ($menus->status == 0) ? '1' : '0');
                if (Schema::hasColumn('categories', 'updated_at')) {
                    $update_array['updated_at'] = current_datetime();
                }
                if (Schema::hasColumn('categories', 'updated_by')) {
                    $update_array['updated_by'] = $logged_user_id;
                }
                Category::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
}
