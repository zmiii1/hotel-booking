<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomPackage;

class RoomPackageSeeder extends Seeder
{
    public function run()
    {
        // Create Room Only Package
        RoomPackage::create([
            'name' => 'Room Only',
            'code' => 'ROOM_ONLY',
            'description' => 'Basic room package without breakfast',
            'inclusions' => json_encode([
                'Free Welcome drink',
                'Free Parking',
                'Voucher free pass lalassa beach, bodur beach & ladda beach',
                'Free WiFi access throughout the hotel area',
                'refill water'
            ]),
            'amenities' => json_encode([
                'Living Room (Mutiara & Berlian Type)',
                'Swimming Pool Public',
                'Kids Club'
            ]),
            'price_adjustment' => 0,
            'is_default' => true,
            'status' => true
        ]);
        
        // Create Room with Breakfast Package
        RoomPackage::create([
            'name' => 'Room with Breakfast',
            'code' => 'ROOM_BREAKFAST',
            'description' => 'Room package with breakfast included',
            'inclusions' => json_encode([
                'Daily Breakfast',
                'Voucher free pass lalassa beach, bodur beach & ladda beach',
                'Voucher rental bike',
                'Free Welcome drink',
                'Free Parking',
                'Free WiFi access throughout the hotel area',
                'refill water'
            ]),
            'amenities' => json_encode([
                'Living Room (Mutiara & Berlian Type)',
                'Swimming Pool Public',
                'Kids Club'
            ]),
            'price_adjustment' => 240000, // Rp. 240,000 for breakfast
            'is_default' => false,
            'status' => true
        ]);
    }
}