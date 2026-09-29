<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'property_type', 'location', 'image', 'gallery', 'desc', 
        'checkin_time', 'checkout_time', 'rating', 'min_price', 'max_price',
        'contact', 'email', 'website', 'map_link', 'amenities', 'tags',
        'created_by'
    ];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'tags' => 'array',
        'rating' => 'decimal:1',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'created_by')->where('deleted', 0);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    /**
     * Get the minimum room price for this hotel
     */
    public function getLowestPriceAttribute()
    {
        return $this->min_price ?? $this->rooms()->min('price') ?? 0;
    }

    /**
     * Get the maximum room price for this hotel
     */
    public function getHighestPriceAttribute()
    {
        return $this->max_price ?? $this->rooms()->max('price') ?? 0;
    }

    /**
     * Scope for filtering by property type
     */
    public function scopeOfType($query, $type)
    {
        if ($type) {
            return $query->where('property_type', $type);
        }
        return $query;
    }

    /**
     * Scope for filtering by location
     */
    public function scopeInLocation($query, $location)
    {
        if ($location) {
            return $query->where('location', 'like', '%' . $location . '%');
        }
        return $query;
    }

    /**
     * Scope for filtering by minimum rating
     */
    public function scopeMinRating($query, $rating)
    {
        if ($rating) {
            return $query->where('rating', '>=', $rating);
        }
        return $query;
    }

    /**
     * Scope for filtering by price range (uses min_price or rooms min price)
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        if ($maxPrice) {
            return $query->where(function($q) use ($maxPrice) {
                $q->where('min_price', '<=', $maxPrice)
                  ->orWhereHas('rooms', function($rq) use ($maxPrice) {
                      $rq->where('price', '<=', $maxPrice);
                  });
            });
        }
        return $query;
    }

    /**
     * Scope for filtering by amenities
     */
    public function scopeWithAmenities($query, array $amenities)
    {
        if (!empty($amenities)) {
            foreach ($amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }
        return $query;
    }

    /**
     * Scope for filtering by tags
     */
    public function scopeWithTags($query, $tag)
    {
        if ($tag && $tag !== 'all') {
            return $query->whereJsonContains('tags', $tag);
        }
        return $query;
    }

    public function scopeFromVerifiedSellers($query)
    {
        if (!Schema::hasColumn($this->getTable(), 'created_by')) {
            return $query;
        }

        return $query->whereHas('seller.kycVerification', function ($kycQuery) {
            $kycQuery->fullyVerified();
        });
    }

    public function isSellableByVerifiedSeller(): bool
    {
        if (!Schema::hasColumn($this->getTable(), 'created_by')) {
            return true;
        }

        $this->loadMissing('seller.kycVerification');

        return $this->seller && $this->seller->isFullyKycVerified();
    }
}
