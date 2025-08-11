<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomNumber extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
    
    public function booked_dates()
    {
        return $this->hasMany(RoomBookedDate::class, 'rooms_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id');
    }

    public function isAvailableForDates($checkIn, $checkOut)
    {
        // If the room is not active, it's not available
        if ($this->status !== 'Active') {
            return false;
        }

        // Convert dates to Carbon instances
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        
        // Create array of dates to check
        $dates = \Carbon\CarbonPeriod::create($checkInDate, $checkOutDate->subDay())->toArray();
        $dateStrings = array_map(fn($date) => $date->format('d-m-Y'), $dates);
        
        // Check if this room number is booked for any of these dates
        $bookings = BookingRoomList::where('room_number_id', $this->id)
            ->whereHas('booking', function($query) use ($dateStrings) {
                $query->whereHas('booked_dates', function($q) use ($dateStrings) {
                    $q->whereIn('book_date', $dateStrings);
                });
            })
            ->count();
            
        // If there are no bookings for these dates, the room is available
        return $bookings === 0;
    }

    public function getCapacityAttribute()
    {
        // If there's a specific capacity for this room number, use that
        if (isset($this->attributes['capacity']) && $this->attributes['capacity']) {
            return $this->attributes['capacity'];
        }
        
        // Otherwise, use the room's capacity
        return $this->room ? $this->room->guests_total : 0;
    }
}
