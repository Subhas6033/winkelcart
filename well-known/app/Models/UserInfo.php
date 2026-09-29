<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $table = 'user_info';

    protected $fillable = [
        'user_id',
        'address',
        'profile_image',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'landmark',
        'city',
        'state',
        'pincode',
        'business_category_id',
        'gst_no',
        'state_id',
        'country_id',
        'subscription_plan',
        'registration_razorpay_payment_id',
        'registration_razorpay_order_id',
    ];

    /** Returns true if this seller is on the Pro (paid) plan. */
    public function isPro(): bool
    {
        return $this->subscription_plan === 'paid';
    }

    /** Returns true if this seller is on the Free plan. */
    public function isFree(): bool
    {
        return $this->subscription_plan !== 'paid';
    }

    /** Maximum products allowed (50 for free, unlimited = PHP_INT_MAX for pro). */
    public function productLimit(): int
    {
        return $this->isPro() ? PHP_INT_MAX : 50;
    }

    /**
     * Get formatted full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->landmark ? 'Near ' . $this->landmark : null,
            $this->city,
            $this->state,
            $this->pincode ? 'PIN: ' . $this->pincode : null,
        ]);
        return implode(', ', $parts) ?: $this->address;
    }

}
