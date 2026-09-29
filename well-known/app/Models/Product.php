<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'products';
    protected $fillable = [
        'id',
        'category_id',
        'sub_category_id',
        'name',
        'image',
        'total_price',
        'tax',
        'offer_price',
        'final_price',
        'is_top_deal',
        'top_deal_priority',
        'company',
        'desc',
        'weight',
        'length',
        'breadth',
        'height',
        'quantity',
        'warranty',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'created_by')->where('deleted', 0);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeFromVerifiedSellers($query)
    {
        $adminIds = User::role('Admin')->pluck('id');

        return $query->where(function ($q) use ($adminIds) {
            // Allow products from any admin user without KYC
            $q->whereIn('created_by', $adminIds)
              // Allow legacy/seeded products with no creator
              ->orWhereNull('created_by')
              ->orWhereHas('seller.kycVerification', function ($kycQuery) {
                  $kycQuery->fullyVerified();
              });
        });
    }
}
