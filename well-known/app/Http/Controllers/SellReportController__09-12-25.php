<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


class SellReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        // print_r($data->toArray());die;
        // If seller, compute seller-specific totals
        if ($user->hasRole('Seller')) {
            foreach ($data as $order) {
                $order->seller_total = $order->items
                    ->filter(fn($item) => $item->product->created_by == $user->id)
                    ->sum(fn($item) => $item->price * $item->quantity);
            }
        }

        return view('sell-report.index', compact('data'))
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
    }
}
