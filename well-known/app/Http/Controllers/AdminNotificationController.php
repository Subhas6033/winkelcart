<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        $notifications = AdminNotification::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $unreadCount = AdminNotification::where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $count = AdminNotification::where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $query = AdminNotification::orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        $notifications = $query->paginate(25)->appends($request->query());
        
        $types = [
            AdminNotification::TYPE_USER_REGISTERED => 'User Registered',
            AdminNotification::TYPE_SELLER_REGISTERED => 'Seller Registered',
            AdminNotification::TYPE_ORDER_PLACED => 'Order Placed',
            AdminNotification::TYPE_ORDER_SHIPPED => 'Order Shipped',
            AdminNotification::TYPE_ORDER_DELIVERED => 'Order Delivered',
            AdminNotification::TYPE_ORDER_CANCELLED => 'Order Cancelled',
            AdminNotification::TYPE_BOOKING_MADE => 'Hotel Booking',
            AdminNotification::TYPE_BOOKING_CANCELLED => 'Booking Cancelled',
            AdminNotification::TYPE_SUPPORT_TICKET => 'Support Ticket',
            AdminNotification::TYPE_RETURN_REQUEST => 'Return Request',
            AdminNotification::TYPE_CONTACT_QUERY => 'Contact Query',
            AdminNotification::TYPE_REVIEW_ADDED => 'Review Added',
            AdminNotification::TYPE_KYC_SUBMITTED => 'KYC Submitted',
        ];

        return view('admin.notifications.index', compact('notifications', 'types'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        // Redirect to the notification's link if available
        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        AdminNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all read notifications
     */
    public function clearRead()
    {
        AdminNotification::where('is_read', true)->delete();

        return back()->with('success', 'All read notifications cleared.');
    }
}
