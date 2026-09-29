<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'related_module',
        'module_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_info()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
        // return $this->hasOne(UserInfo::class);
    }

    public function kycVerification()
    {
        return $this->hasOne(SellerKycVerification::class, 'user_id');
    }

    public function settlements()
    {
        return $this->hasMany(SellerSettlement::class, 'user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function isFullyKycVerified(): bool
    {
        if (!$this->hasRole('Seller')) {
            return true;
        }

        $kyc = $this->kycVerification;

        return $kyc ? $kyc->isFullyVerified() : false;
    }

    /** Returns true if the seller has the Pro (paid) subscription plan. */
    public function isPro(): bool
    {
        return optional($this->user_info)->subscription_plan === 'paid';
    }
}
