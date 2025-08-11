<?php

namespace Database\Seeders;

use App\Models\RoomAddOns;
use App\Models\RoomPackage;
use Illuminate\Database\Seeder;

class RoomAddOnsSeeder extends Seeder
{
    public function run(): void
    {
        // Food Service Addons
        $breakfast = RoomAddOns::create([
            'name' => 'Breakfast',
            'description' => 'Restaurant pangrango',
            'price' => 240000,
            'category' => 'food',
            'image' => 'upload/addons/breakfast.jpg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'specific',
            'guest_count' => 2,
            'is_included' => false,
            'price_type' => 'per_night',
            'status' => true
        ]);
        
        $youngCoconut = RoomAddOns::create([
            'name' => 'Young Coconut',
            'description' => null,
            'price' => 25000,
            'category' => 'food',
            'image' => 'upload/addons/coconut.jpg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'all',
            'is_included' => false,
            'price_type' => 'one_time',
            'status' => true
        ]);
        
        $dinner = RoomAddOns::create([
            'name' => 'Dinner',
            'description' => 'Dinner unlimitted price, normal price IDR. 200.000,-',
            'price' => 180000,
            'normal_price' => 200000,
            'category' => 'food',
            'image' => 'upload/addons/dinner.jpg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'specific',
            'guest_count' => 1,
            'is_included' => false,
            'price_type' => 'per_night',
            'status' => true
        ]);
        
        // Welcome Drink
        $welcomeDrink = RoomAddOns::create([
            'name' => 'Welcome Drink',
            'description' => 'Welcome Drink',
            'price' => 20000,
            'category' => 'welcome_drink',
            'image' => 'upload/addons/welcome-drink.jpeg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'all',
            'is_included' => true, // Included in packages
            'price_type' => 'one_time',
            'status' => true
        ]);
        
        // Parking
        $parking = RoomAddOns::create([
            'name' => 'Parking',
            'description' => 'Free Parking',
            'price' => 0,
            'category' => 'parking',
            'image' => 'upload/addons/parking.jpeg',
            'is_prepayment_required' => false,
            'for_guests_type' => 'all',
            'is_included' => true, // Included in packages
            'price_type' => 'one_time',
            'status' => true
        ]);
        
        // Sports & Leisure
        $watersport = RoomAddOns::create([
            'name' => 'Watersport',
            'description' => 'normal Price Rp. 110.000,- per person per hour (base on weather) valid for 1 person Please present this order our staff and for more information water sport to our staff',
            'price' => 100000,
            'normal_price' => 110000,
            'category' => 'sports_leisure',
            'image' => 'upload/addons/water-sport.jpeg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'specific',
            'guest_count' => 1,
            'is_included' => false,
            'price_type' => 'one_time',
            'status' => true
        ]);
        
        $drivingRange = RoomAddOns::create([
            'name' => 'Driving Range',
            'description' => 'for 50 ball\'s',
            'price' => 60000,
            'category' => 'sports_leisure',
            'image' => 'upload/addons/driving-range.jpeg',
            'is_prepayment_required' => false,
            'for_guests_type' => 'all',
            'is_included' => false,
            'price_type' => 'one_time',
            'is_sale' => true,
            'status' => true
        ]);
        
        $waterSportNonEngine = RoomAddOns::create([
            'name' => 'Water Sport Activities Non engine',
            'description' => 'normal Price Rp. 110.000,- per person per hour (base on weather), valid for 1 person Please present this order our staff and for more information water sport to our staff',
            'price' => 88000,
            'normal_price' => 110000,
            'category' => 'sports_leisure',
            'image' => 'upload/addons/watersport-non-engine.jpg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'all',
            'is_included' => false,
            'price_type' => 'one_time',
            'is_bestseller' => true,
            'status' => true
        ]);
        
        // Ticket Entrance
        $ticketEntrance = RoomAddOns::create([
            'name' => 'Ticket Entrance',
            'description' => 'Ticket Entrance choice of Bodur Beach or Ladda Beach or Lalassa Beach Club or Mongolian',
            'price' => 0,
            'category' => 'ticket',
            'image' => 'upload/addons/beach-ticket.jpg',
            'is_prepayment_required' => false,
            'for_guests_type' => 'specific',
            'guest_count' => 2,
            'is_included' => true, // Included in packages
            'price_type' => 'per_night',
            'status' => true
        ]);
        
        // Rental
        $bicycle = RoomAddOns::create([
            'name' => 'Bicycle',
            'description' => 'Rental Bike Normal Price Rp.50.000 per unit per hour, valid for 1 person for more information or packages tour or bike track please contact our staff',
            'price' => 50000,
            'category' => 'rental',
            'image' => 'upload/addons/bicycle.jpg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'specific',
            'guest_count' => 1,
            'is_included' => false,
            'price_type' => 'one_time',
            'status' => true
        ]);
        
        $electricBike = RoomAddOns::create([
            'name' => 'Electric Bike',
            'description' => 'Rental per unit per 12 Hours (base on availabe), valid for 1 person Please present this order our staff and for more information track to our staff',
            'price' => 160000,
            'category' => 'rental',
            'image' => 'upload/addons/electric-bike.jpeg',
            'is_prepayment_required' => true,
            'for_guests_type' => 'specific',
            'guest_count' => 1,
            'is_included' => false,
            'price_type' => 'one_time',
            'is_bestseller' => true,
            'status' => true
        ]);
        
        // Attach included addons to packages
        $roomOnly = RoomPackage::where('code', 'ROOM_ONLY')->first();
        $roomWithBreakfast = RoomPackage::where('code', 'ROOM_BREAKFAST')->first();
        
        if ($roomOnly) {
            $roomOnly->addons()->attach([
                $welcomeDrink->id,
                $parking->id,
                $ticketEntrance->id
            ]);
        }
        
        if ($roomWithBreakfast) {
            $roomWithBreakfast->addons()->attach([
                $breakfast->id,
                $welcomeDrink->id,
                $parking->id,
                $ticketEntrance->id,
                $bicycle->id
            ]);
        }
    }
}