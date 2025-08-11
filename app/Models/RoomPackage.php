<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class RoomPackage extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'inclusions', 'amenities',
        'price_adjustment', 'is_default', 'status'
    ];
    
    protected $casts = [
        'inclusions' => 'array',
        'amenities' => 'array',
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];
    
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'package_id');
    }
    
    public function getInclusionsArrayAttribute()
    {
        return is_array($this->inclusions) ? $this->inclusions : json_decode($this->inclusions, true) ?? [];
    }
    
    public function getAmenitiesArrayAttribute()
    {
        return is_array($this->amenities) ? $this->amenities : json_decode($this->amenities, true) ?? [];
    }

    public function addons()
    {
        return $this->belongsToMany(RoomAddOns::class, 'package_room_add_ons', 'room_package_id', 'room_add_ons_id')
            ->withTimestamps();
    }
}

