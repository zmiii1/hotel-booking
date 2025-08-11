<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Booking extends Model
{
    use HasFactory;
    protected $guarded= [];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function assign_rooms() {
        return $this->hasMany(BookingRoomList::class, 'booking_id');
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id');
    }
        
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function booked_dates()
    {
        return $this->hasMany(RoomBookedDate::class, 'booking_id');
    }

    public function room_lists()
    {
        return $this->hasMany(BookingRoomList::class, 'booking_id');
    }

    public function package()
    {
        return $this->belongsTo(RoomPackage::class, 'package_id');
    }

    public function addons()
    {
        return $this->belongsToMany(RoomAddOns::class, 'booking_room_add_ons', 'booking_id', 'room_add_ons_id')
                    ->withPivot('quantity', 'price', 'total_price')
                    ->withTimestamps();
    }
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_code', 'code');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function room_number()
    {
        return $this->belongsTo(RoomNumber::class, 'room_number_id');
    }
    public function room_numbers()
    {
        // If you have a many-to-many relationship through BookingRoomList
        return $this->hasManyThrough(
            RoomNumber::class,
            BookingRoomList::class,
            'booking_id', // Foreign key on BookingRoomList table
            'id',         // Foreign key on RoomNumber table
            'id',         // Local key on Booking table
            'room_number_id' // Local key on BookingRoomList table
        );
    }
}
