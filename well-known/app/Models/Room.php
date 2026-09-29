<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'type', 'price', 'capacity', 'amenities', 'image', 'total_rooms'
    ];

    protected $casts = [
        'amenities' => 'array',
        'price' => 'decimal:2',
    ];

    // Accessor to support both 'type' and 'room_type'
    public function getRoomTypeAttribute()
    {
        return $this->type;
    }

    // Accessor to support both 'capacity' and 'max_guests'
    public function getMaxGuestsAttribute()
    {
        return $this->capacity;
    }

    public function amenitiesList()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
