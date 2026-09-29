<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelFilterSeeder extends Seeder
{
    public function run()
    {
        $propertyTypes = ['hotel', 'resort', 'villa', 'apartment'];
        $tagOptions = [
            ['luxury', 'business'],
            ['beach', 'honeymoon'],
            ['mountain', 'family'],
            ['budget'],
            ['luxury', 'honeymoon'],
            ['family', 'budget'],
        ];

        $hotels = Hotel::all();
        foreach ($hotels as $index => $hotel) {
            // Assign property type based on index
            $hotel->property_type = $propertyTypes[$index % count($propertyTypes)];
            
            // Assign tags
            $hotel->tags = $tagOptions[$index % count($tagOptions)];
            
            // Calculate min/max price from rooms
            $minPrice = $hotel->rooms()->min('price');
            $maxPrice = $hotel->rooms()->max('price');
            
            $hotel->min_price = $minPrice ?: 2000;
            $hotel->max_price = $maxPrice ?: 10000;
            
            $hotel->save();
        }
    }
}
