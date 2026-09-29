<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'delivery_date',
        'admin_paid_amount',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->where('deleted', 0);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->where('deleted', 0);
    }
}
