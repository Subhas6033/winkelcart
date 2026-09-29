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
use Auth;

class LoanController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:loan-list|loan-create|loan-edit|loan-delete', ['only' => ['index','show']]);
        $this->middleware('permission:loan-create|loan-edit', ['only' => ['create','store']]);
        $this->middleware('permission:loan-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $data = Loan::with('product', 'bank', 'bank_emi')->where("deleted","0");
        if(!empty($request->input('search')))
        {
            $search = $request->input('search');
            $data->where('borrower_name', 'LIKE', "%$search%")
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('bank', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                })
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                });
            
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
        $validated = $request->validate([
            'bank_id' => 'required',
            'sanction_date' => 'required',
            'borrower_name' => 'required',
            'coborrower_name' => 'required',
            'product_id' => 'required',
            'product_id' => 'required',
            'ammount_sanction' => 'required',
            'tenour' => 'required',
            'tenour' => 'required',
            'repo' => 'required',
            'mclr' => 'required',
            'base' => 'required',
            'plr' => 'required',
            'spread_rate' => 'required',
            'calculate_amount' => 'required',
            'amount_emi' => 'required',
            'emi_date' => 'required',
            'emi_bank_id' => 'required',
            'emi_bank_acc' => 'required',
            'distribution_date' => 'required',
        ]);
        $user_id = Auth::user()->id;
        if(!empty($request->loan_id)){            
            $loan = Loan::find($request->loan_id);
            $loan->updated_at = date('Y-m-d H:i:s');
            $loan->updated_by = $user_id;
            $msg = 'updated';
        }else{
            $loan = new Loan;            
            $loan->created_at = date('Y-m-d H:i:s');
            $loan->created_by = $user_id;
            $msg = 'created';
        }
        
        $loan->bank_id = $request->bank_id;
        $loan->product_id = $request->product_id;
        $loan->sanction_date = date('Y-m-d', strtotime($request->sanction_date));
        $loan->borrower_name = $request->borrower_name;
        $loan->co_borrower_name = $request->coborrower_name;
        $loan->amount_sactioin = $request->ammount_sanction;
        $loan->tenour = $request->tenour;
        $loan->repo = $request->repo;
        $loan->mclr = $request->mclr;
        $loan->base = $request->base;
        $loan->plr = $request->plr;
        $loan->spread_rate = $request->spread_rate;
        $loan->calculate_rate = $request->calculate_amount;
        $loan->amount_emi = $request->amount_emi;
        $loan->security = $request->security;
        $loan->closure_charge = $request->closure_charge;
        $loan->any_special_condition = $request->any_special_condition;
        $loan->emi_date = date('Y-m-d', strtotime($request->emi_date));
        $loan->emi_bank = $request->emi_bank_id;
        $loan->emi_acc_no = $request->emi_bank_acc;
        $loan->distribution_date = date('Y-m-d', strtotime($request->distribution_date));
        
        if($loan->save()){
            return redirect()->route('loans.index')->with('success','Loan '.$msg.' successfully');
        }else{
            return redirect()->route('loans.index')->with('error','Error');
        }
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $loan = Loan::find($id);
        $bank=Bank::where('status',0)->where('deleted',0)->get();
        $product=Product::where('status',0)->where('deleted',0)->get();
        return view('loans.create',compact('loan', 'bank', 'product'));
    }
    public function destroy($id)
    {
        $logged_user_id = loggedin_admin('id');
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        Loan::where("id",$id)->update($update_array);
        return redirect()->route('loans.index')->with('success','Loan deleted successfully');
    }
    public function loan_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Loan::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'0');
                Loan::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
}
