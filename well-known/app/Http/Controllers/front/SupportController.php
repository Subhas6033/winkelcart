<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = null;
        $userOrders = collect();

        if (Auth::check()) {
            $tickets = SupportTicket::where('user_id', Auth::id())
                ->orderByDesc('id')
                ->paginate(10);

            $userOrders = Order::where('user_id', Auth::id())
                ->where('deleted', 0)
                ->orderByDesc('id')
                ->pluck('order_number')
                ->filter()
                ->unique()
                ->values();
        }

        return view('front.support.index', compact('tickets', 'userOrders'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error_login', 'Please login to submit a support ticket.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:190',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
            'order_number' => 'required|string|max:64',
        ]);

        $userId = Auth::id();

        $isOwnedOrder = Order::where('user_id', $userId)
            ->where('order_number', $validated['order_number'])
            ->where('deleted', 0)
            ->exists();

        if (!$isOwnedOrder) {
            return back()->withInput()->withErrors([
                'order_number' => 'Please select a valid order number from your account.',
            ]);
        }

        $ticket = SupportTicket::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'order_number' => $validated['order_number'] ?? null,
            'status' => 'Open',
        ]);

        // Create admin notification for support ticket
        AdminNotification::notify(
            AdminNotification::TYPE_SUPPORT_TICKET,
            'New Support Ticket',
            'Support ticket "' . $validated['subject'] . '" submitted by ' . $validated['name'],
            [
                'link' => route('admin.support_tickets.index'),
                'related_id' => $ticket->id,
                'related_type' => SupportTicket::class,
            ]
        );

        return back()->with('success', 'Support request submitted successfully.');
    }
}
