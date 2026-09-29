<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SellerSettlement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSellerSettlementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['index', 'store', 'updateStatus', 'getSettlementAmounts']);
    }

    private function ensureAdminAccess()
    {
        abort_unless(Auth::user() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = SellerSettlement::with(['seller', 'processedBy'])
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seller_id')) {
            $query->where('user_id', $request->seller_id);
        }

        $settlements = $query->paginate(25)->appends($request->query());

        $sellers = User::role('Seller')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.seller_settlements.index', compact('settlements', 'sellers'))
            ->with('isSellerPanel', false);
    }

    public function sellerIndex(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->hasRole('Seller'), 403, 'Unauthorized');

        $query = SellerSettlement::with(['seller', 'processedBy'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $settlements = $query->paginate(25)->appends($request->query());

        return view('admin.seller_settlements.index', [
            'settlements' => $settlements,
            'sellers' => collect(),
            'isSellerPanel' => true,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'seller_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'gross_amount' => 'required|numeric|min:0',
            'commission_amount' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'reference_no' => 'nullable|string|max:120',
            'note' => 'nullable|string|max:3000',
        ]);

        $commission = $validated['commission_amount'] ?? 0;
        $netAmount = $validated['net_amount'] ?? ((float) $validated['gross_amount'] - (float) $commission);

        if ($netAmount < 0) {
            return back()->withInput()->withErrors([
                'net_amount' => 'Net amount cannot be negative.',
            ]);
        }

        $settlement = SellerSettlement::create([
            'user_id' => $validated['seller_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'gross_amount' => $validated['gross_amount'],
            'commission_amount' => $commission,
            'net_amount' => $netAmount,
            'status' => 'Pending',
            'reference_no' => $validated['reference_no'] ?? null,
            'note' => $validated['note'] ?? null,
            'processed_by' => Auth::id(),
        ]);

        AuditLog::record(
            'seller_settlement.created',
            'seller_settlement',
            $settlement->id,
            [],
            [
                'seller_id' => $settlement->user_id,
                'period_start' => $settlement->period_start,
                'period_end' => $settlement->period_end,
                'net_amount' => $settlement->net_amount,
                'status' => $settlement->status,
            ]
        );

        return back()->with('success', 'Seller settlement created successfully.');
    }

    public function updateStatus(Request $request, SellerSettlement $settlement)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Paid,Hold,Failed',
            'reference_no' => 'nullable|string|max:120',
            'note' => 'nullable|string|max:3000',
        ]);

        $oldValues = [
            'status' => $settlement->status,
            'reference_no' => $settlement->reference_no,
            'paid_at' => $settlement->paid_at,
            'note' => $settlement->note,
        ];

        $settlement->status = $validated['status'];
        $settlement->reference_no = $validated['reference_no'] ?? $settlement->reference_no;
        $settlement->note = $validated['note'] ?? null;
        $settlement->processed_by = Auth::id();

        if ($validated['status'] === 'Paid') {
            $settlement->paid_at = now();
        } elseif ($validated['status'] !== 'Paid') {
            $settlement->paid_at = null;
        }

        $settlement->save();

        AuditLog::record(
            'seller_settlement.status_changed',
            'seller_settlement',
            $settlement->id,
            $oldValues,
            [
                'status' => $settlement->status,
                'reference_no' => $settlement->reference_no,
                'paid_at' => $settlement->paid_at,
                'note' => $settlement->note,
            ]
        );

        return back()->with('success', 'Settlement status updated successfully.');
    }

    // AJAX endpoint to auto-calculate settlement amounts
    public function getSettlementAmounts(Request $request)
    {
        $this->ensureAdminAccess();
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $sellerId = $request->seller_id;
        $start = $request->period_start;
        $end = $request->period_end;

        // Only completed, non-deleted orders
        $orders = \App\Models\Order::with(['items.product'])
            ->where('deleted', 0)
            ->whereBetween('created_at', [$start, $end . ' 23:59:59'])
            ->whereHas('items.product', function($q) use ($sellerId) {
                $q->where('created_by', $sellerId);
            })
            ->get();

        $gross = 0;
        $commission = 0;
        $net = 0;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->created_by == $sellerId) {
                    $basePrice = $item->product->offer_price ?? 0;
                    $platformFee = ($basePrice * 10) / 100;
                    $offerPrice = $basePrice + $platformFee;
                    $taxPercent = $item->product->tax ?? 0;
                    $taxMoney = ($offerPrice * $taxPercent) / 100;
                    $finalPrice = $offerPrice + $taxMoney;
                    $companyProfit = ($finalPrice * 10) / 100;
                    $sellerProfit = $finalPrice - $companyProfit;
                    $qty = $item->quantity;
                    $gross += $finalPrice * $qty;
                    $commission += $companyProfit * $qty;
                    $net += $sellerProfit * $qty;
                }
            }
        }
        return response()->json([
            'gross_amount' => round($gross, 2),
            'commission_amount' => round($commission, 2),
            'net_amount' => round($net, 2),
        ]);
    }
}
