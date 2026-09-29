<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RazorpayPayment;

class AdminRazorpayPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = RazorpayPayment::with('user:id,name,email')
            ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by razorpay_order_id or razorpay_payment_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('razorpay_order_id', 'like', "%{$search}%")
                  ->orWhere('razorpay_payment_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(25)->withQueryString();

        // Summary stats
        $totalCount = RazorpayPayment::count();
        $paidCount = RazorpayPayment::where('status', 'paid')->count();
        $paidAmount = RazorpayPayment::where('status', 'paid')->sum('amount');
        $pendingCount = RazorpayPayment::where('status', 'created')->count();

        return view('admin.razorpay_payments.index', compact(
            'payments', 'totalCount', 'paidCount', 'paidAmount', 'pendingCount'
        ));
    }
}
