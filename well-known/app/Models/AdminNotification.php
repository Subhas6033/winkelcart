<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'icon_color',
        'link',
        'related_id',
        'related_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Notification types
    const TYPE_USER_REGISTERED = 'user_registered';
    const TYPE_SELLER_REGISTERED = 'seller_registered';
    const TYPE_ORDER_PLACED = 'order_placed';
    const TYPE_ORDER_SHIPPED = 'order_shipped';
    const TYPE_ORDER_DELIVERED = 'order_delivered';
    const TYPE_ORDER_CANCELLED = 'order_cancelled';
    const TYPE_BOOKING_MADE = 'booking_made';
    const TYPE_BOOKING_CANCELLED = 'booking_cancelled';
    const TYPE_SUPPORT_TICKET = 'support_ticket';
    const TYPE_RETURN_REQUEST = 'return_request';
    const TYPE_CONTACT_QUERY = 'contact_query';
    const TYPE_REVIEW_ADDED = 'review_added';
    const TYPE_KYC_SUBMITTED = 'kyc_submitted';

    /**
     * Get the related model instance
     */
    public function related()
    {
        if ($this->related_type && $this->related_id) {
            return $this->related_type::find($this->related_id);
        }
        return null;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Create a notification with standard format
     */
    public static function notify($type, $title, $message, $options = [])
    {
        $icons = [
            self::TYPE_USER_REGISTERED => 'fas fa-user-plus',
            self::TYPE_SELLER_REGISTERED => 'fas fa-store',
            self::TYPE_ORDER_PLACED => 'fas fa-shopping-cart',
            self::TYPE_ORDER_SHIPPED => 'fas fa-truck',
            self::TYPE_ORDER_DELIVERED => 'fas fa-check-circle',
            self::TYPE_ORDER_CANCELLED => 'fas fa-times-circle',
            self::TYPE_BOOKING_MADE => 'fas fa-hotel',
            self::TYPE_BOOKING_CANCELLED => 'fas fa-calendar-times',
            self::TYPE_SUPPORT_TICKET => 'fas fa-life-ring',
            self::TYPE_RETURN_REQUEST => 'fas fa-undo-alt',
            self::TYPE_CONTACT_QUERY => 'fas fa-envelope',
            self::TYPE_REVIEW_ADDED => 'fas fa-star',
            self::TYPE_KYC_SUBMITTED => 'fas fa-id-card',
        ];

        $colors = [
            self::TYPE_USER_REGISTERED => 'success',
            self::TYPE_SELLER_REGISTERED => 'info',
            self::TYPE_ORDER_PLACED => 'primary',
            self::TYPE_ORDER_SHIPPED => 'warning',
            self::TYPE_ORDER_DELIVERED => 'success',
            self::TYPE_ORDER_CANCELLED => 'danger',
            self::TYPE_BOOKING_MADE => 'info',
            self::TYPE_BOOKING_CANCELLED => 'danger',
            self::TYPE_SUPPORT_TICKET => 'warning',
            self::TYPE_RETURN_REQUEST => 'warning',
            self::TYPE_CONTACT_QUERY => 'secondary',
            self::TYPE_REVIEW_ADDED => 'warning',
            self::TYPE_KYC_SUBMITTED => 'info',
        ];

        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $options['icon'] ?? ($icons[$type] ?? 'fas fa-bell'),
            'icon_color' => $options['color'] ?? ($colors[$type] ?? 'primary'),
            'link' => $options['link'] ?? null,
            'related_id' => $options['related_id'] ?? null,
            'related_type' => $options['related_type'] ?? null,
        ]);
    }
}
