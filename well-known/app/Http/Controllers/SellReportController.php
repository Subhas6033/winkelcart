<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;


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
        $isSellerView = $user->hasRole('Seller');
        $search = $request->input('search');
        $selectedProductId = $request->input('product_id');
        $selectedSellerId = $isSellerView ? null : $request->input('seller_id');

        $query = Order::with(['buyer.user_info', 'items.product.seller'])
                    ->where('deleted', 0);
                    

        // Buyer not deleted
        $query->whereHas('buyer', function ($q) {
            $q->where('deleted', 0);
        });
        // Seller not deleted
        $query->whereHas('items.product.seller', function ($q) {
            $q->where('deleted', 0);
        });

        // If seller login → only orders that include this seller’s products
        if ($isSellerView) {
            $query->whereHas('items.product', function ($q) use ($user, $selectedProductId) {
                $q->where('created_by', $user->id);

                if (!empty($selectedProductId)) {
                    $q->where('products.id', $selectedProductId);
                }
            });
        } elseif (!empty($selectedSellerId) || !empty($selectedProductId)) {
            $query->whereHas('items.product', function ($q) use ($selectedSellerId, $selectedProductId) {
                if (!empty($selectedSellerId)) {
                    $q->where('created_by', $selectedSellerId);
                }

                if (!empty($selectedProductId)) {
                    $q->where('products.id', $selectedProductId);
                }
            });
        }

        // Optional buyer search
        if (!empty($search)) {
            $query->whereHas('buyer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $productsQuery = Product::query()
            ->select('id', 'name')
            ->orderBy('name');

        if ($isSellerView) {
            $productsQuery->where('created_by', $user->id);
        } elseif (!empty($selectedSellerId)) {
            $productsQuery->where('created_by', $selectedSellerId);
        }

        $products = $productsQuery->get();

        $sellers = collect();
        if (!$isSellerView) {
            $sellers = User::role('Seller')
                ->where('deleted', 0)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $data = $query->orderBy('id', 'DESC')->paginate(25);
        
        // Calculate totals for summary
        $grandTotals = [
            'total_amount' => 0,
            'total_tax' => 0,
            'company_profit' => 0,
            'seller_profit' => 0,
        ];
        
        // Calculate detailed pricing for each order and item
        foreach ($data as $order) {
            $orderTotals = [
                'total_amount' => 0,
                'total_tax' => 0,
                'company_profit' => 0,
                'seller_profit' => 0,
            ];
            
            foreach ($order->items as $item) {
                if (!$item->product) {
                    continue;
                }

                // Skip if this is seller view and not their product
                if ($isSellerView && $item->product->created_by != $user->id) {
                    continue;
                }

                // Skip if admin selected a specific seller and item does not belong to it
                if (!$isSellerView && !empty($selectedSellerId) && (int) $item->product->created_by !== (int) $selectedSellerId) {
                    continue;
                }

                // Skip if product filter is selected and this item does not match
                if (!empty($selectedProductId) && (int) $item->product_id !== (int) $selectedProductId) {
                    continue;
                }
                
                $product = $item->product;
                $quantity = $item->quantity;
                
                // Get product pricing details
                $basePrice = $product->offer_price ?? 0;  // Base Price
                $mrp = $product->total_price ?? 0;  // MRP
                $taxPercent = $product->tax ?? 0;  // Tax %
                $finalPrice = $product->final_price ?? 0;  // Final Price per unit
                
                // Calculate pricing breakdown per unit
                $platformFee = ($basePrice * 10) / 100;
                $offerPrice = $basePrice + $platformFee;
                $taxMoney = ($offerPrice * $taxPercent) / 100;
                
                // If final_price is not set in DB, calculate it
                if ($finalPrice == 0 && $basePrice > 0) {
                    $finalPrice = $offerPrice + $taxMoney;
                }
                
                // Company profit = 10% of Final Price
                $companyProfit = ($finalPrice * 10) / 100;
                
                // Seller profit = Final Price - Company Profit
                $sellerProfit = $finalPrice - $companyProfit;
                
                // Store calculated values on item for view access (per unit)
                $item->calc_mrp = $mrp;
                $item->calc_base_price = $basePrice;
                $item->calc_platform_fee = $platformFee;
                $item->calc_offer_price = $offerPrice;
                $item->calc_tax_percent = $taxPercent;
                $item->calc_tax_money = $taxMoney;
                $item->calc_final_price = $finalPrice;
                $item->calc_company_profit = $companyProfit;
                $item->calc_seller_profit = $sellerProfit;
                
                // Calculate totals for this item (multiply by quantity)
                $item->total_tax = $taxMoney * $quantity;
                $item->total_company_profit = $companyProfit * $quantity;
                $item->total_seller_profit = $sellerProfit * $quantity;
                $item->total_amount = $finalPrice * $quantity;
                
                // Add to order totals
                $orderTotals['total_amount'] += $item->total_amount;
                $orderTotals['total_tax'] += $item->total_tax;
                $orderTotals['company_profit'] += $item->total_company_profit;
                $orderTotals['seller_profit'] += $item->total_seller_profit;
            }
            
            // Store order totals
            $order->calc_totals = $orderTotals;
            
            // Add to grand totals
            $grandTotals['total_amount'] += $orderTotals['total_amount'];
            $grandTotals['total_tax'] += $orderTotals['total_tax'];
            $grandTotals['company_profit'] += $orderTotals['company_profit'];
            $grandTotals['seller_profit'] += $orderTotals['seller_profit'];
        }

        return view('sell-report.index', compact(
            'data',
            'grandTotals',
            'products',
            'sellers',
            'selectedProductId',
            'selectedSellerId',
            'isSellerView'
        ))
            ->with('i', ($request->input('page', 1) - 1) * 25);
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
