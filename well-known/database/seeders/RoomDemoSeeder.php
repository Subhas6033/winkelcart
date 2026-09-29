<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomDemoSeeder extends Seeder
{
    public function run()
    {
        $hotel = DB::table('hotels')->first();
        if (!$hotel) return;
        DB::table('rooms')->insert([
            [
                'hotel_id' => $hotel->id,
                'type' => 'deluxe',
                'price' => 3500,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV', 'AC']),
                'image' => 'deluxe.jpg',
                'total_rooms' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => $hotel->id,
                'type' => 'luxury',
                'price' => 5000,
                'capacity' => 3,
                'amenities' => json_encode(['WiFi', 'TV', 'AC', 'Mini Bar']),
                'image' => 'luxury.jpg',
                'total_rooms' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => $hotel->id,
                'type' => 'normal',
                'price' => 2000,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi']),
                'image' => 'normal.jpg',
                'total_rooms' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => $hotel->id,
                'type' => 'couple',
                'price' => 2500,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV']),
                'image' => 'couple.jpg',
                'total_rooms' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => $hotel->id,
                'type' => 'dormitory',
                'price' => 800,
                'capacity' => 8,
                'amenities' => json_encode(['WiFi']),
                'image' => 'dormitory.jpg',
                'total_rooms' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
