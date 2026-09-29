<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
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

        $query = SupportTicket::with('user')
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%')
                    ->orWhere('order_number', 'like', '%' . $search . '%');
            });
        }

        $tickets = $query
            ->paginate(25)
            ->appends($request->query());

        return view('admin.support_tickets.index', compact('tickets'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'admin_note' => ($request->status === 'Closed' ? 'required' : 'nullable') . '|string|max:2000',
        ]);

        $oldValues = [
            'status' => $ticket->status,
            'admin_note' => $ticket->admin_note,
            'resolved_at' => $ticket->resolved_at,
        ];

        $ticket->status = $validated['status'];
        $ticket->admin_note = $validated['admin_note'] ?? null;

        if ($validated['status'] === 'Resolved') {
            $ticket->resolved_at = now();
        } elseif (in_array($validated['status'], ['Open', 'In Progress'])) {
            $ticket->resolved_at = null;
        }

        $ticket->save();

        AuditLog::record(
            'support_ticket.status_changed',
            'support_ticket',
            $ticket->id,
            $oldValues,
            [
                'status' => $ticket->status,
                'admin_note' => $ticket->admin_note,
                'resolved_at' => $ticket->resolved_at,
            ]
        );

        return back()->with('success', 'Ticket updated successfully.');
    }
}
