<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'payment_method', 'payment_status', 'reference_id', 'amount', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'Paid' => 'success',
            'Pending' => 'warning',
            'Failed' => 'danger',
            'Refunded' => 'info',
            'Cancelled' => 'secondary',
            default => 'secondary',
        };
    }
}
