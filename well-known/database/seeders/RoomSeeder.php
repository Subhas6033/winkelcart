<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $roomTypes = [
            ['type' => 'deluxe', 'price' => 3500, 'capacity' => 2, 'amenities' => json_encode(['WiFi','TV','AC']), 'image' => 'deluxe.jpg', 'total_rooms' => 5],
            ['type' => 'luxury', 'price' => 5500, 'capacity' => 3, 'amenities' => json_encode(['WiFi','TV','AC','Mini Bar']), 'image' => 'luxury.jpg', 'total_rooms' => 3],
            ['type' => 'suite', 'price' => 8000, 'capacity' => 4, 'amenities' => json_encode(['WiFi','TV','AC','Mini Bar','Jacuzzi']), 'image' => 'suite.jpg', 'total_rooms' => 2],
            ['type' => 'family', 'price' => 4500, 'capacity' => 5, 'amenities' => json_encode(['WiFi','TV','AC','Extra Beds']), 'image' => 'family.jpg', 'total_rooms' => 4],
            ['type' => 'normal', 'price' => 2000, 'capacity' => 2, 'amenities' => json_encode(['WiFi','TV']), 'image' => 'normal.jpg', 'total_rooms' => 8],
        ];

        // Add rooms for hotels 2-6 (hotel 1 already has rooms)
        foreach([2,3,4,5,6] as $hotelId) {
            foreach($roomTypes as $room) {
                $room['hotel_id'] = $hotelId;
                Room::create($room);
            }
        }
    }
}
