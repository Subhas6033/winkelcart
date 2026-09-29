<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\AuditLog;
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
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $search         = $request->input('search');
        $paymentStatus  = $request->input('payment_status');
        $orderStatus    = $request->input('order_status');
        $paymentMethod  = $request->input('payment_method');
        $dateFrom       = $request->input('date_from');
        $dateTo         = $request->input('date_to');

        $query = Order::with(['buyer.user_info', 'items.product.seller'])
                    ->where('deleted', 0);

        // Buyer not deleted
        $query->whereHas('buyer', function ($q) {
            $q->where('deleted', 0);
        });

        // If seller login → only orders that include this seller's products
        if ($user->hasRole('Seller')) {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Keyword search: order number, buyer name/email, seller name, product name
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items.product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items.product.seller', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Payment status filter
        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }

        // Order/delivery status filter
        if (!empty($orderStatus)) {
            $query->where('order_status', $orderStatus);
        }

        // Payment method filter
        if (!empty($paymentMethod)) {
            $query->where('payment_method', $paymentMethod);
        }

        // Date range filter
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $data = $query->orderBy('id', 'DESC')->paginate(25);

        // If seller, compute seller-specific totals
        if ($user->hasRole('Seller')) {
            foreach ($data as $order) {
                $order->seller_total = $order->items
                    ->filter(fn($item) => $item->product && $item->product->created_by == $user->id)
                    ->sum(fn($item) => $item->price * $item->quantity);
            }
        }

        return view('orders.index', compact('data', 'search', 'paymentStatus', 'orderStatus', 'paymentMethod', 'dateFrom', 'dateTo'))
            ->with('i', ($request->input('page', 1) - 1) * 25);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $categoryId = $request->input('category_id');
        $rules = [
            'name' => [
                'required',
                'max:255',
                \Illuminate\Validation\Rule::unique('categories', 'name')->ignore($categoryId)->where(function ($query) {
                    $query->where('deleted', 0);
                }),
            ],
        ];

        $messages = [
            'name.required' => 'Name can\'t be empty',
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
            $insert['status'] = 0;
            $insert['deleted'] = 0;
            $insert['created_by'] = $logged_user_id;
            $insert['created_at'] = current_datetime();
            Category::create($insert);
            $msg = 'created';
        } else {
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
        $category = Category::find($id);
        return view('categories.create', compact('category'));
    }

    public function destroy($id)
    {
        // Soft delete the order by setting deleted = 1
        $order = Order::find($id);

        if (!$order) {
            return redirect()->route('orders.index')
                            ->with('success', 'Order not found.');
        }

        Order::where('id', $id)->update([
            'deleted'    => 1,
            'updated_at' => current_datetime(),
        ]);

        return redirect()->route('orders.index')
                        ->with('success', 'Order deleted successfully');
    }

    public function order_status_change(Request $request)
    {
        $menus = Order::find($_POST['fieldid']);
        if (!empty($menus)) {
            $logged_user_id = loggedin_admin('id');
            $oldStatus = $menus->status;
            $update_array = ($menus->status == 0)
                ? ['updated_at' => current_datetime(), 'status' => '1']
                : ['updated_at' => current_datetime(), 'status' => '0'];
            Order::where("id", $_POST['fieldid'])->update($update_array);
            $msg = ($menus->status == 0) ? 1 : 0;

            AuditLog::record(
                'order.active_status_toggled',
                'order',
                $_POST['fieldid'],
                ['status' => $oldStatus],
                ['status' => $msg]
            );

            echo $msg;
        }
    }

    public function payment_status_change(Request $request)
    {
        if (!empty($_POST['fieldid'])) {
            $menus = Order::find($_POST['fieldid']);
            if (!empty($menus)) {
                $roleName = Auth::user()->roles->pluck('name')->first();
                if ($roleName == 'Seller') {
                    echo "unauthorized"; exit;
                }
                $oldPaymentStatus = $menus->order_status;
                $update_array = ($menus->order_status == 'Done')
                    ? ['updated_at' => current_datetime(), 'order_status' => 'Pending', 'payment_status' => 'Pending']
                    : ['updated_at' => current_datetime(), 'order_status' => 'Done', 'payment_status' => 'Paid'];
                Order::where("id", $_POST['fieldid'])->update($update_array);
                $msg = ($menus->order_status == 'Done') ? 'Pending' : 'Done';

                AuditLog::record(
                    'order.payment_status_toggled',
                    'order',
                    $_POST['fieldid'],
                    ['order_status' => $oldPaymentStatus],
                    ['order_status' => $msg]
                );

                echo $msg;
            }
        }
    }

    public function updateDeliveryDate(Request $request)
    {
        $request->validate([
            'item_id'       => 'required|exists:order_items,id',
            'delivery_date' => 'nullable|date',
        ]);

        $item = OrderItem::findOrFail($request->item_id);

        $oldDate = $item->delivery_date;

        if (Auth::user()->hasRole('Seller') && $item->product->created_by != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $item->delivery_date = $request->delivery_date;
        $item->save();

        AuditLog::record(
            'order_item.delivery_date_updated',
            'order_item',
            $item->id,
            ['delivery_date' => $oldDate],
            ['delivery_date' => $item->delivery_date]
        );

        return response()->json(['success' => true]);
    }

    public function updateSellerPayment(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'amount'  => 'nullable|numeric|min:0',
        ]);

        $item = \App\Models\OrderItem::find($request->item_id);
        $oldAmount = $item->admin_paid_amount;
        $item->admin_paid_amount = $request->amount;
        $item->save();

        AuditLog::record(
            'order_item.seller_payment_updated',
            'order_item',
            $item->id,
            ['admin_paid_amount' => $oldAmount],
            ['admin_paid_amount' => $item->admin_paid_amount]
        );

        return response()->json(['success' => true]);
    }
}