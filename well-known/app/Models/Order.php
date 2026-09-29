<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'cart_total',
        'shipping_cost',
        'grand_total',
        'shipping_breakdown',
        'payment_image',
        'status',
        'deleted',
        'order_status',
        'payment_method',
        'shipping_address',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'payment_status',
        'shiprocket_order_id',
        'shiprocket_shipment_id',
        'awb_code',
        'courier_name',
        'shipping_label_url',
        'shipping_invoice_url',
        'tracking_status',
        'tracking_details',
        'pickup_requested',
        'pickup_requested_at',
    ];

    protected $casts = [
        'pickup_requested'    => 'boolean',
        'pickup_requested_at' => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'shipping_breakdown'  => 'array',
        'cart_total'          => 'decimal:2',
        'shipping_cost'       => 'decimal:2',
        'grand_total'         => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Through relation to get products directly
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            OrderItem::class,
            'order_id',    // Foreign key on order_items
            'id',          // Foreign key on products
            'id',          // Local key on orders
            'product_id'   // Local key on order_items
        );
    }

    public function timelines()
    {
        return $this->hasMany(OrderStatusTimeline::class, 'order_id')->orderBy('id');
    }
}
