<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\UserInfo;
use App\Models\ProductImage;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function deleteImage($productId, $imageId)
    {
        $image = \App\Models\ProductImage::findOrFail($imageId);
        if ($image->product_id == $productId) {
            $imgPath = public_path('uploads/products/'.$image->image);
            if (file_exists($imgPath)) {
                unlink($imgPath);
            }
            $image->delete();
        }
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Image deleted successfully.');
    }
    function __construct(){
        
        $this->middleware('auth');
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create|product-edit', ['only' => ['create','store']]);
        $this->middleware('seller.kyc.verified', ['only' => ['create', 'store', 'edit', 'update', 'product_status_change']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
        $this->middleware('permission:product-edit', ['only' => ['updateTopDeal']]);
    }
    public function index(Request $request)
    {
        $search        = $request->input('search');
        $companyFilter = $request->input('company');
        $categoryFilter = $request->input('category_id');
        $statusFilter  = $request->input('status');
        $logged_user_id = loggedin_admin('id');
        $roleName = Auth::user()->getRoleNames()->first();

        $data = Product::with('category')->where('deleted', '0');

        if ($roleName === 'Seller') {
            $data->where('created_by', $logged_user_id);
        }

        if (!empty($search)) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('desc', 'LIKE', "%$search%")
                    ->orWhere('company', 'LIKE', "%$search%");
            });
        }

        if (!empty($companyFilter)) {
            $data->where('company', $companyFilter);
        }

        if (!empty($categoryFilter)) {
            $data->where('category_id', $categoryFilter);
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $data->where('status', $statusFilter);
        }

        // Get unique company names for filter dropdown
        $companyNames = Product::where('deleted', '0')
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $data = $data->orderBy('id', 'DESC')->paginate(25);

        return view('products.index', compact('data', 'companyNames', 'search', 'companyFilter', 'categoryFilter', 'statusFilter'))
            ->with('i', ($request->input('page', 1) - 1) * 25);
    }
    /**
     * Returns allowed categories based on the current user's role.
     * Admin: all 5 fixed business categories (hardcoded, mirrors registration page).
     * Seller: only the single category matching their registered business type.
     */
    private const FIXED_CATEGORIES = [
        1 => 'Electronics',
        2 => 'Mobiles',
        3 => 'Fashion',
        5 => 'Appliances',
        4 => 'Hotels Resorts',
    ];

    private function getAllowedCategories(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        if ($user->hasRole('Seller')) {
            $userInfo = UserInfo::where('user_id', $user->id)->first();
            $sellerCatId = $userInfo ? (int) $userInfo->business_category_id : null;
            if ($sellerCatId && isset(self::FIXED_CATEGORIES[$sellerCatId])) {
                return collect([self::FIXED_CATEGORIES[$sellerCatId]])
                    ->mapWithKeys(fn($name) => [$sellerCatId => $name]);
            }
        }

        // Admin (or fallback): all 5 fixed categories
        return collect(self::FIXED_CATEGORIES);
    }

    public function create()
    {
        $categories = $this->getAllowedCategories();
        $isSeller = Auth::user()->hasRole('Seller');
        // Pre-load sub-categories for the selected category (sellers have a fixed one)
        $preselectedCatId = $categories->keys()->first();
        $subCategories = $preselectedCatId
            ? \App\Models\SubCategory::where('deleted', 0)->where('status', 0)->where('category_id', $preselectedCatId)->orderBy('name')->get()
            : collect();

        // Subscription plan info for sellers
        $isPro = false;
        $sellerProductCount = 0;
        if ($isSeller) {
            $sellerUserInfo = UserInfo::where('user_id', Auth::id())->first();
            $isPro = optional($sellerUserInfo)->subscription_plan === 'paid';
            $sellerProductCount = \App\Models\Product::where('created_by', Auth::id())->where('deleted', 0)->count();
        }

        return view('products.create', compact('categories', 'isSeller', 'subCategories', 'isPro', 'sellerProductCount'));
    }

    public function store(Request $request)
    {
        $isEdit = $request->filled('product_id'); // check if this is an edit
        $isAdmin = Auth::user() && Auth::user()->hasRole('Admin');

        // For sellers, enforce category_id from their registered business type
        if (!$isAdmin) {
            $userInfo = UserInfo::where('user_id', Auth::id())->first();
            if ($userInfo && $userInfo->business_category_id) {
                $request->merge(['category_id' => $userInfo->business_category_id]);
            }
        }

        $existingTopDeal = 0;
        $existingTopDealPriority = 0;
        if ($isEdit) {
            $existingProduct = Product::findOrFail($request->input('product_id'));
            $existingTopDeal = (int) ($existingProduct->is_top_deal ?? 0);
            $existingTopDealPriority = (int) ($existingProduct->top_deal_priority ?? 0);
        }

        $rules = [
            'name'        => 'required|max:255',
            'category_id' => 'required|in:1,2,3,4,5',
            // Images required only on create
            'images'      => ($isEdit ? 'nullable' : 'required'),
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'total_price' => 'required|numeric',
            'tax'         => 'required',
            'offer_price' => 'required|numeric',
            'final_price' => 'required|numeric',
            'quantity'    => 'required|integer|min:0',
            'warranty'    => 'nullable|string|max:255',
            'is_top_deal' => 'nullable|boolean',
            'top_deal_priority' => 'nullable|integer|min:0|max:9999',
        ];

        $messages = [
            'name.required'         => 'Name can’t be empty',
            'category_id.required'  => 'Please select a category',
            'category_id.in'        => 'Invalid category',
            'images.required'       => 'At least one image is required',
            'images.*.image'        => 'Each file must be an image',
            'images.*.mimes'        => 'Only JPG, PNG, and WEBP formats are allowed',
            'images.*.max'          => 'Each image must not exceed 2MB',
            'total_price.required'  => 'Total price is required',
            'total_price.numeric'   => 'Total price must be a valid amount',
            'tax.required'  => 'Tax is required',
            'offer_price.required'  => 'Offer price is required',
            'offer_price.numeric'   => 'Offer price must be a valid amount',
            'final_price.required'  => 'Final price is required',
            'final_price.numeric'   => 'Final price must be a valid amount',
            'quantity.required'     => 'Quantity is required',
            'quantity.integer'      => 'Quantity must be a whole number',
            'quantity.min'          => 'Quantity cannot be negative',
            'top_deal_priority.integer' => 'Top deal priority must be a number',
        ];

        $this->validate($request, $rules, $messages);

        // Enforce product limit for free-plan sellers
        if (!$isEdit && !$isAdmin) {
            $sellerUserInfo = UserInfo::where('user_id', Auth::id())->first();
            $sellerPlan = optional($sellerUserInfo)->subscription_plan ?? 'free';
            if ($sellerPlan !== 'paid') {
                $existingCount = Product::where('created_by', Auth::id())->where('deleted', 0)->count();
                if ($existingCount >= 50) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'You have reached the 50-product limit on the Free plan. Please upgrade to the Pro plan to list unlimited products.']);
                }
            }
        }

        // Debug log for final_price
        \Log::info('Final Price Submitted:', ['final_price' => $request->input('final_price')]);
        $logged_user_id = loggedin_admin('id');
        $input = $request->all();

        $insert = [
            'name'        => $input['name'],
            'total_price' => $input['total_price'],
            'tax'         => $input['tax'],
            'offer_price' => $input['offer_price'],
            'final_price' => $input['final_price'],
            'is_top_deal' => $isAdmin ? ($request->has('is_top_deal') ? 1 : 0) : ($isEdit ? $existingTopDeal : 0),
            'top_deal_priority' => $isAdmin ? (int) $request->input('top_deal_priority', 0) : ($isEdit ? $existingTopDealPriority : 0),
            'desc'        => $input['desc'],
            'category_id'     => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id') ?: null,
            'company'     => $request->input('company'),
            'quantity'    => (int) $request->input('quantity', 1),
            'warranty'    => $request->input('warranty') ?: null,
        ];

        // Handle multiple image uploads
        $imageFilenames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $filename);
                $imageFilenames[] = $filename;
            }
            // For backward compatibility, set the first image as main
            if (count($imageFilenames)) {
                $insert['image'] = $imageFilenames[0];
            }
        }

        if (!$isEdit) {
            // Create
            $insert['status']     = 0;
            $insert['deleted']    = 0;
            $insert['created_by'] = $logged_user_id;
            $insert['created_at'] = current_datetime();
            $product = Product::create($insert);
            // Save images
            if (count($imageFilenames)) {
                foreach ($imageFilenames as $img) {
                    ProductImage::create(['product_id' => $product->id, 'image' => $img]);
                }
            }
            $msg = 'created';
        } else {
            // Update
            $insert['updated_by'] = $logged_user_id;
            $insert['updated_at'] = current_datetime();
            $product = Product::findOrFail($input['product_id']);
            $product->update($insert);
            // If new images uploaded, add them
            if (count($imageFilenames)) {
                foreach ($imageFilenames as $img) {
                    ProductImage::create(['product_id' => $product->id, 'image' => $img]);
                }
            }
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
        $categories = $this->getAllowedCategories();
        $isSeller = Auth::user()->hasRole('Seller');
        // Pre-load sub-categories for the product's current category
        $subCategories = $product->category_id
            ? \App\Models\SubCategory::where('deleted', 0)->where('status', 0)->where('category_id', $product->category_id)->orderBy('name')->get()
            : collect();

        // Subscription plan info for sellers
        $isPro = false;
        $sellerProductCount = 0;
        if ($isSeller) {
            $sellerUserInfo = UserInfo::where('user_id', Auth::id())->first();
            $isPro = optional($sellerUserInfo)->subscription_plan === 'paid';
            $sellerProductCount = Product::where('created_by', Auth::id())->where('deleted', 0)->count();
        }

        return view('products.create', compact('product', 'categories', 'isSeller', 'subCategories', 'isPro', 'sellerProductCount'));
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

    public function updateTopDeal(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('Admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admin can update top deals.',
                ], 403);
            }

            abort(403, 'Only admin can update top deals.');
        }

        $validated = $request->validate([
            'is_top_deal' => 'nullable|in:0,1',
            'top_deal_priority' => 'nullable|integer|min:0|max:9999',
        ]);

        $product->is_top_deal = ((int) $request->input('is_top_deal', 0) === 1) ? 1 : 0;
        $product->top_deal_priority = (int) ($validated['top_deal_priority'] ?? 0);
        $product->updated_by = loggedin_admin('id');
        $product->updated_at = current_datetime();
        $product->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Top deal settings updated.',
                'data' => [
                    'product_id' => $product->id,
                    'is_top_deal' => (int) $product->is_top_deal,
                    'top_deal_priority' => (int) $product->top_deal_priority,
                ],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Top deal settings updated successfully.');
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
