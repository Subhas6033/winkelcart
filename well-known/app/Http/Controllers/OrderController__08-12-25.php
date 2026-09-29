<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index','show']]);
        $this->middleware('permission:order-create|order-edit', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = Order::with(['buyer.user_info', 'items.product.seller'])
                    ->where('deleted', 0);

        // If seller login → only orders that include this seller’s products
        if ($user->hasRole('Seller')) {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Optional buyer search
        if (!empty($search)) {
            $query->whereHas('buyer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('id', 'DESC')->paginate(25);

        // If seller, compute seller-specific totals
        if ($user->hasRole('Seller')) {
            foreach ($data as $order) {
                $order->seller_total = $order->items
                    ->filter(fn($item) => $item->product->created_by == $user->id)
                    ->sum(fn($item) => $item->price * $item->quantity);
            }
        }
        // print_r($data->toarray());die;
        return view('orders.index', compact('data'))
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
            'desc' => $input['desc'],
        ];

        if (empty($categoryId)) {
            // Add mode
            $insert['status'] = 0;
            $insert['deleted'] = 0;
            $insert['created_by'] = $logged_user_id;
            $insert['created_at'] = current_datetime();
            Category::create($insert);
            $msg = 'created';
        } else {
            // Edit mode
            $insert['updated_by'] = $logged_user_id;
            $insert['updated_at'] = current_datetime();
            $category = Category::find($categoryId);
            $category->update($insert);
            $msg = 'updated';
        }

        return redirect()->route('categories.index')
                        ->with('success', 'Category ' . $msg . ' successfully');
    }    
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
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        Category::where("id",$id)->update($update_array);
        //$result = User::where("id","=",$id)->delete();
        // var_dump($result);die;
        //dd(\DB::getQueryLog());
        // echo $id ;die;
        return redirect()->route('categories.index')
                        ->with('success','Category deleted successfully');
    }
    public function order_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Order::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_at'=>current_datetime(),'status'=>'1'):array('updated_at'=>current_datetime(),'status'=>'0');
                Order::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
    public function payment_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Order::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->order_status=='Done')?array('updated_at'=>current_datetime(),'order_status'=>'Pending'):array('updated_at'=>current_datetime(),'order_status'=>'Done');
                Order::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->order_status=='Done')?'Pending':'Done';
                echo $msg;
            }
        }
    }
    public function updateDeliveryDate(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'delivery_date' => 'nullable|date',
        ]);

        $item = OrderItem::findOrFail($request->item_id);

        // ✅ Only seller of the product can update
        if (Auth::user()->hasRole('Seller') && $item->product->created_by != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $item->delivery_date = $request->delivery_date;
        $item->save();

        return response()->json(['success' => true]);
    }
    public function updateSellerPayment(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $item = \App\Models\OrderItem::find($request->item_id);
        $item->admin_paid_amount = $request->amount;
        $item->save();

        return response()->json(['success' => true]);
    }
}
