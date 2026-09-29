<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusTimeline extends Model
{
    use HasFactory;

    protected $table = 'order_status_timelines';

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'changed_by',
        'changed_by_role',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
