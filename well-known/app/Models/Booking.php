<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'hotel_id', 'room_id', 'checkin', 'checkout', 'guests',
        'total_price', 'status', 'payment_id', 'booking_code',
        'razorpay_order_id', 'razorpay_payment_id', 'payment_status',
    ];

    protected $casts = [
        'checkin' => 'date',
        'checkout' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Generate a unique booking code
     */
    public static function generateBookingCode()
    {
        do {
            $code = 'WK' . strtoupper(Str::random(8));
        } while (self::where('booking_code', $code)->exists());
        return $code;
    }

    /**
     * Check if room is available for the given dates
     */
    public static function isRoomAvailable($roomId, $checkin, $checkout, $excludeBookingId = null)
    {
        $query = self::where('room_id', $roomId)
            ->whereNotIn('status', ['cancelled', 'Cancelled'])
            ->where(function($q) use ($checkin, $checkout) {
                $q->whereBetween('checkin', [$checkin, $checkout])
                  ->orWhereBetween('checkout', [$checkin, $checkout])
                  ->orWhere(function($q2) use ($checkin, $checkout) {
                      $q2->where('checkin', '<=', $checkin)
                         ->where('checkout', '>=', $checkout);
                  });
            });
        
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }
        
        return !$query->exists();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match(strtolower($this->status)) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Calculate number of nights
     */
    public function getNightsAttribute()
    {
        return $this->checkin->diffInDays($this->checkout);
    }
}
