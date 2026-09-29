<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Loan;
use App\Models\Bank;
use App\Models\Product;
use DB;
use Hash;
use Illuminate\Support\Arr;

class LoanController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:loan-list|loan-create|loan-edit|loan-delete', ['only' => ['index','show']]);
        $this->middleware('permission:loan-create|loan-edit', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:loan-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $data = Loan::where("deleted","0");
        if(!empty($request->input('search')))
        {
            $search = $request->input('search');
            // $data->where('name', 'LIKE', "%$search%");
            // $data->orwhere('desc', 'LIKE', "%$search%");
        }
        $data = $data->orderBy('id','DESC')->paginate(25);
        return view('loans.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        $bank=Bank::where('status',0)->where('deleted',0)->get();
        $product=Product::where('status',0)->where('deleted',0)->get();
        return view('loans.create',compact('bank','product'));
    }
    public function store(Request $request)
    {
        //
        if(empty($request->input('bank_id')))
        {
            ///// for add mode
            $this->validate($request, [
                'name' => 'required|regex:/^[a-z A-Z1-9 0]+$/u|max:255',
            ],
            [
                'name.required' => 'Name can’t be empty',
                'name.regex' => 'Invalid Name Format',
            ]);
        }
        else
        {
            //////for edit mode
            $this->validate($request, [
                'name' => 'required|regex:/^[a-z A-Z1-9 0]+$/u|max:255',
            ],
            [
                'name.required' => 'Name can’t be empty',
                'name.regex' => 'Invalid Name Format',
            ]);
        }
        $logged_user_id = loggedin_admin('id');
        $input = $request->all();
        $insert['name'] = $input['name'];
        $insert['desc'] = $input['desc'];
        if(empty($request->input('bank_id')))
        {
            $insert['status'] = 0;
            $insert['deleted'] = 0;
            $insert['created_by'] = $logged_user_id;
            $insert['created_at'] = current_datetime();
            $bank = Bank::create($insert);
            $msg = 'created';
        }
        else
        {
            $insert['updated_by'] = $logged_user_id;
            $insert['updated_at'] = current_datetime();
            $bank = Bank::find($input['bank_id']);
            $bank->update($insert);
            $msg = 'updated';
        }
        return redirect()->route('banks.index')
                        ->with('success','Bank '.$msg.' successfully');
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
        $bank = Bank::find($id);    
        return view('banks.create',compact('bank'));
    }
    public function destroy($id)
    {
        $logged_user_id = loggedin_admin('id');
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        Bank::where("id",$id)->update($update_array);
        //$result = User::where("id","=",$id)->delete();
        // var_dump($result);die;
        //dd(\DB::getQueryLog());
        // echo $id ;die;
        return redirect()->route('banks.index')
                        ->with('success','Bank deleted successfully');
    }
    public function bank_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Bank::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'0');
                Bank::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
}
