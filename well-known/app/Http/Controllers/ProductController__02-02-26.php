<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create|product-edit', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $logged_user_id = loggedin_admin('id');
        $roleName = Auth::user()->getRoleNames()->first();        
        
        $data = Product::with('category')->where("deleted", "0");
        if($roleName =='Seller')
            $data->where("created_by", $logged_user_id);

        if (!empty($search)) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('desc', 'LIKE', "%$search%")
                    ->orWhere('company', 'LIKE', "%$search%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        $data = $data->orderBy('id','DESC')->paginate(25);

        return view('products.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 25);
    }
    public function create()
    {
        $categories = Category::whereNotIn('id', [1,2,3])->pluck('name', 'id'); // id => name
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $isEdit = $request->filled('product_id'); // check if this is an edit

        $rules = [
            'name'        => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            // Image is required only on create
            'image'       => ($isEdit ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png,webp|max:2048',
            'total_price' => 'required|numeric',
            'tax'         => 'required',
            'offer_price' => 'required|numeric',
        ];

        $messages = [
            'name.required'         => 'Name can’t be empty',
            'category_id.required'  => 'Please select a category',
            'category_id.exists'    => 'Invalid category',
            'image.required'        => 'Image is required',          // used only on create
            'image.image'           => 'File must be an image',
            'image.mimes'           => 'Only JPG, PNG, and WEBP formats are allowed',
            'image.max'             => 'Image must not exceed 2MB',
            'total_price.required'  => 'Total price is required',
            'total_price.numeric'   => 'Total price must be a valid amount',
            'tax.required'  => 'Tax is required',
            'offer_price.required'  => 'Offer price is required',
            'offer_price.numeric'   => 'Offer price must be a valid amount',
        ];

        $this->validate($request, $rules, $messages);
        // print_r($_REQUEST);die;
        $logged_user_id = loggedin_admin('id');
        $input = $request->all();

        // Calculate final price
        $offerPrice = isset($input['offer_price']) ? (float)$input['offer_price'] : 0;
        $tax = isset($input['tax']) ? (float)$input['tax'] : 0;
        $finalPrice = $offerPrice + ($offerPrice * $tax / 100);

        $insert = [
            'name'        => $input['name'],
            'total_price' => $input['total_price'],
            'tax'         => $input['tax'],
            'offer_price' => $input['offer_price'],
            'final_price' => $finalPrice,
            'desc'        => $input['desc'],
            'category_id' => $request->input('category_id'),
            'company'     => $request->input('company'),
        ];

        // Handle image upload (optional on edit)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $filename);
            $insert['image'] = $filename;
        }

        if (!$isEdit) {
            // Create
            $insert['status']     = 0;
            $insert['deleted']    = 0;
            $insert['created_by'] = $logged_user_id;
            $insert['created_at'] = current_datetime();
            Product::create($insert);
            $msg = 'created';
        } else {
            // Update
            $insert['updated_by'] = $logged_user_id;
            $insert['updated_at'] = current_datetime();
            $product = Product::findOrFail($input['product_id']);

            // Replace old image if new uploaded
            if ($request->hasFile('image') && $product->image) {
                $oldPath = public_path('uploads/products/'.$product->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $product->update($insert);
            $msg = 'updated';
        }

        return redirect()->route('products.index')
                        ->with('success', 'Product '.$msg.' successfully');
    }    

    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::whereNotIn('id', [1,2,3])->pluck('name', 'id');        
        return view('products.create', compact('product', 'categories'));
    }
    public function destroy($id)
    {
        $logged_user_id = loggedin_admin('id');
        $update_array = array('updated_at'=>current_datetime(),'deleted'=>'1','updated_by'=>$logged_user_id);
        Product::where("id",$id)->update($update_array);

        OrderItem::where('product_id', $id)->delete();

        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
    public function product_status_change(Request $request)
    {
        if(!empty($_POST['fieldid']))
        {
            $menus = Product::find($_POST['fieldid']);
            if(!empty($menus))
            {
                $logged_user_id = loggedin_admin('id');
                $update_array = ($menus->status==0)?array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'1'):array('updated_by'=>$logged_user_id,'updated_at'=>current_datetime(),'status'=>'0');
                Product::where("id",$_POST['fieldid'])->update($update_array);
                $msg = ($menus->status==0)?1:0;
                echo $msg;
            }
        }
    }
}
