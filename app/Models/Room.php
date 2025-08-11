<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $guarded=[];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    // In your Room model
    public function facilities()
    {
        return $this->hasMany(Facilities::class, 'rooms_id');
    }
    
    public function multiImages()
    {
        return $this->hasMany(MultiImage::class, 'rooms_id');
    }

    public function room_numbers()
    {
        return $this->hasMany(RoomNumber::class, 'rooms_id');
    }

    public function promoCodes(): BelongsToMany
    {
        return $this->belongsToMany(PromoCode::class);
    }
    protected $appends = ['final_price'];
    public $promo_discount = 0;

    public function getFinalPriceAttribute()
    {
        // Base price minus regular discount
        $price = $this->price - $this->discount;
        
        // Apply promo discount if set
        if ($this->promo_discount > 0) {
            $price -= $this->promo_discount;
        }
        
        return max($price, 0); // Ensure price doesn't go negative
    }

    public function getAvailableCapacityForDates($checkIn, $checkOut)
    {
        // Convert dates to Carbon instances
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        
        // Create array of dates to check
        $dates = \Carbon\CarbonPeriod::create($checkInDate, $checkOutDate->subDay())->toArray();
        $dateStrings = array_map(fn($date) => $date->format('d-m-Y'), $dates);
        
        // Get all active room numbers for this room
        $activeRoomNumbers = $this->room_numbers()->where('status', 'Active')->get();
        
        // Get booked room number IDs for these dates
        $bookedRoomNumberIds = BookingRoomList::whereIn('room_number_id', $activeRoomNumbers->pluck('id'))
            ->whereHas('booking', function($query) use ($dateStrings) {
                $query->whereHas('booked_dates', function($q) use ($dateStrings) {
                    $q->whereIn('book_date', $dateStrings);
                });
            })
            ->pluck('room_number_id')
            ->toArray();
        
        // Count available room numbers
        $availableRoomNumbers = $activeRoomNumbers->whereNotIn('id', $bookedRoomNumberIds);
        
        return $availableRoomNumbers->count();
    }

    public function hasCapacityForGuests($totalGuests, $checkIn, $checkOut)
    {
        // Check if any room numbers can accommodate the guests
        $activeRoomNumbers = $this->activeRoomNumbers()->get();
        
        if ($activeRoomNumbers->isEmpty()) {
            return false;
        }
        
        // Get booked room number IDs for these dates
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        $dates = \Carbon\CarbonPeriod::create($checkInDate, $checkOutDate->subDay())->toArray();
        $dateStrings = array_map(fn($date) => $date->format('d-m-Y'), $dates);
        
        $bookedRoomNumberIds = BookingRoomList::whereIn('room_number_id', $activeRoomNumbers->pluck('id'))
            ->whereHas('booking', function($query) use ($dateStrings) {
                $query->whereHas('booked_dates', function($q) use ($dateStrings) {
                    $q->whereIn('book_date', $dateStrings);
                });
            })
            ->pluck('room_number_id')
            ->toArray();
        
        // Filter out booked rooms
        $availableRoomNumbers = $activeRoomNumbers->whereNotIn('id', $bookedRoomNumberIds);
        
        // Check if any available room has capacity for the guests
        return $availableRoomNumbers->isNotEmpty() && $this->guests_total >= $totalGuests;
    }
    
}
