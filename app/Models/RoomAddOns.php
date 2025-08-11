<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomAddOns extends Model
{
    protected $table = 'room_add_ons';
    
    protected $fillable = [
        'name', 'description', 'price', 'category', 'image',
        'is_prepayment_required', 'for_guests_type', 'guest_count', 'is_included',
        'status', 'price_type', 'normal_price', 'is_bestseller', 'is_sale'
    ];
    
    protected $casts = [
        'price' => 'float',
        'normal_price' => 'float',
        'is_prepayment_required' => 'boolean',
        'is_included' => 'boolean',
        'status' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_sale' => 'boolean',
    ];
    
    // Categories: food, parking, watersport, ticket, rental, etc.
    const CATEGORIES = [
        'food' => 'Food service',
        'parking' => 'Parking',
        'welcome_drink' => 'Welcome Drink',
        'sports_leisure' => 'Sports & Leisure',
        'ticket' => 'Ticket Entrance',
        'rental' => 'Rental services'
    ];
    
    // Guest types: all, adult, child, specific_number
    const GUEST_TYPES = [
        'all' => 'For all guests',
        'adult' => 'For adults only',
        'child' => 'For children only',
        'specific' => 'For specific number of guests'
    ];
    
    // Price types: per_night, one_time, per_person, per_hour
    const PRICE_TYPES = [
        'per_night' => 'Per night',
        'one_time' => 'One time',
        'per_person' => 'Per person',
        'per_hour' => 'Per hour'
    ];
    
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_room_add_ons')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
    
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(RoomPackage::class, 'package_room_add_ons')
            ->withTimestamps();
    }
    
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
    
    public function getGuestTypeTextAttribute()
    {
        return self::GUEST_TYPES[$this->for_guests_type] ?? $this->for_guests_type;
    }
    
    public function getPriceTypeTextAttribute()
    {
        return self::PRICE_TYPES[$this->price_type] ?? $this->price_type;
    }
}