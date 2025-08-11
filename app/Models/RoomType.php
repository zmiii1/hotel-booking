<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_type_id');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'room_type_id');
    }

    public function getMainFacilitiesAttribute()
    {
        return $this->room->facilities->take(4);
    }

    public function getMainImageAttribute()
    {
        return $this->room->image ?? 'upload/no_image.jpg';
    }

    public function getGalleryImagesAttribute()
    {
        return $this->room->multiImages ?? collect();
    }

    public function roomNumbers()
    {
        return $this->hasMany(RoomNumber::class, 'room_type_id');
    }

    public function availableRoomNumbers()
    {
        return $this->hasManyThrough(
            RoomNumber::class,
            Room::class,
            'room_type_id', // Foreign key on rooms table
            'rooms_id',     // Foreign key on room_numbers table
            'id',           // Local key on room_types table
            'id'           // Local key on rooms table
        );
    }
}

