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
        'payment_image',
        'status',
        // add other columns you save using ::create()
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
}
