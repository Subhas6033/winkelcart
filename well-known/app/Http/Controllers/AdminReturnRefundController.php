<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\ReturnRefundRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReturnRefundController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    private function ensureAdminAccess()
    {
        abort_unless(Auth::user() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = ReturnRefundRequest::with(['user', 'order', 'handledBy'])
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('request_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $requests = $query->paginate(25)->appends($request->query());

        $users = User::orderBy('name')
            ->limit(300)
            ->get(['id', 'name', 'email']);

        return view('admin.return_refunds.index', compact('requests', 'users'));
    }

    public function store(Request $request)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'order_number' => 'nullable|string|max:64',
            'request_type' => 'required|in:Return,Refund',
            'reason' => 'required|string|max:3000',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        $order = null;
        if (!empty($validated['order_number'])) {
            $order = Order::where('order_number', trim($validated['order_number']))
                ->where('deleted', 0)
                ->first();
        }

        $userId = $validated['user_id'] ?? null;
        if (!$userId && $order) {
            $userId = $order->user_id;
        }

        $returnRefund = ReturnRefundRequest::create([
            'user_id' => $userId,
            'order_id' => $order ? $order->id : null,
            'order_number' => $validated['order_number'] ?? ($order->order_number ?? null),
            'request_type' => $validated['request_type'],
            'reason' => $validated['reason'],
            'status' => 'Pending',
            'refund_amount' => $validated['refund_amount'] ?? null,
            'requested_at' => now(),
        ]);

        AuditLog::record(
            'return_refund.created',
            'return_refund_request',
            $returnRefund->id,
            [],
            [
                'user_id' => $returnRefund->user_id,
                'order_number' => $returnRefund->order_number,
                'request_type' => $returnRefund->request_type,
                'status' => $returnRefund->status,
            ]
        );

        return back()->with('success', 'Return/Refund request created successfully.');
    }

    public function updateStatus(Request $request, ReturnRefundRequest $returnRefund)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Under Review,Approved,Rejected,Completed',
            'admin_note' => 'nullable|string|max:3000',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        $oldValues = [
            'status' => $returnRefund->status,
            'admin_note' => $returnRefund->admin_note,
            'refund_amount' => $returnRefund->refund_amount,
            'resolved_at' => $returnRefund->resolved_at,
        ];

        $returnRefund->status = $validated['status'];
        $returnRefund->admin_note = $validated['admin_note'] ?? null;
        $returnRefund->handled_by = Auth::id();

        if (array_key_exists('refund_amount', $validated)) {
            $returnRefund->refund_amount = $validated['refund_amount'];
        }

        if (in_array($validated['status'], ['Approved', 'Rejected', 'Completed'])) {
            $returnRefund->resolved_at = now();
        } elseif (in_array($validated['status'], ['Pending', 'Under Review'])) {
            $returnRefund->resolved_at = null;
        }

        $returnRefund->save();

        AuditLog::record(
            'return_refund.status_changed',
            'return_refund_request',
            $returnRefund->id,
            $oldValues,
            [
                'status' => $returnRefund->status,
                'admin_note' => $returnRefund->admin_note,
                'refund_amount' => $returnRefund->refund_amount,
                'resolved_at' => $returnRefund->resolved_at,
            ]
        );

        return back()->with('success', 'Return/Refund request updated successfully.');
    }
}
