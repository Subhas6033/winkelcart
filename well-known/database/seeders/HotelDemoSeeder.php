<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HotelDemoSeeder extends Seeder
{
    public function run()
    {
        DB::table('hotels')->insert([
            'name' => 'Demo Grand Hotel',
            'location' => 'Demo City',
            'image' => 'default.jpg',
            'gallery' => json_encode(['default.jpg', 'gallery1.jpg', 'gallery2.jpg']),
            'desc' => 'A beautiful demo hotel for testing purposes.',
            'checkin_time' => '2:00 PM',
            'checkout_time' => '12:00 PM',
            'rating' => '4.7',
            'contact' => '+91 12345 67890',
            'amenities' => json_encode(['Free WiFi', 'Swimming Pool', 'Spa & Wellness', 'Restaurant', 'Bar', 'Fitness Center', 'Room Service', 'Parking', 'Family Rooms']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
