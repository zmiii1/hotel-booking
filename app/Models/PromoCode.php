<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 
        'description', 
        'discount_type', 
        'discount_value',
        'min_purchase',       // Minimum purchase amount
        'max_discount',       // Maximum discount cap (for percentage discounts)
        'start_date', 
        'end_date', 
        'max_uses', 
        'used_count', 
        'is_active',
        'applies_to'          // 'rooms' or 'beach_tickets'
    ];

    protected $dates = ['start_date', 'end_date'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'discount_value' => 'float',
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'promo_code_room');
    }
    
    // Constants untuk applies_to
    const APPLIES_ROOMS = 'rooms';
    const APPLIES_BEACH_TICKETS = 'beach_tickets';
    
    /**
     * Get all possible values for applies_to field
     */
    public static function getAppliesOptions()
    {
        return [
            self::APPLIES_ROOMS => 'Room Bookings',
            self::APPLIES_BEACH_TICKETS => 'Beach Tickets',
        ];
    }
    
    /**
     * Get formatted applies_to text
     */
    public function getAppliesText()
    {
        $options = self::getAppliesOptions();
        return $options[$this->applies_to] ?? 'Unknown';
    }
    
    /**
     * Beach tickets that this promo code applies to
     */
    public function beachTickets(): BelongsToMany
    {
        return $this->belongsToMany(BeachTicket::class, 'promo_code_beach_ticket');
    }
    
    /**
     * Check if promo code is valid based on dates and usage
     */
    public function isValid(): bool
    {
        return $this->is_active &&
               now()->between($this->start_date, $this->end_date) &&
               ($this->max_uses === null || $this->used_count < $this->max_uses);
    }
    
    /**
 * Calculate discount amount with proper max_discount handling
 */
public function calculateDiscount($price)
{
    if ($this->discount_type === 'percentage') {
        // Cap percentage at 100%
        $percentage = min($this->discount_value, 100);
        $discount = ($price * $percentage) / 100;
        
        // Apply maximum discount cap if it exists
        if ($this->max_discount && $this->max_discount > 0) {
            $discount = min($discount, $this->max_discount);
        }
        
        return $discount;
    } else { // fixed_amount
        // Don't return more discount than the price
        return min($this->discount_value, $price);
    }
}

/**
 * Get formatted discount description for display
 */
public function getDiscountDescription()
{
    if ($this->discount_type === 'percentage') {
        $desc = $this->discount_value . '%';
        
        if ($this->max_discount && $this->max_discount > 0) {
            $desc .= ' (max Rp ' . number_format($this->max_discount, 0, ',', '.') . ')';
        }
        
        return $desc;
    } else {
        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }
}

/**
 * Check if promo code is valid for a beach ticket with proper validation
 */
public function isValidForBeachTicket($ticketId, $totalAmount = 0, $visitDate = null)
{
    // Check if this promo applies to beach tickets
    if (!$this->appliesToBeachTickets()) {
        return [
            'valid' => false,
            'message' => 'This promo code is only valid for room bookings.'
        ];
    }
    
    // Get the beach ticket
    $ticket = BeachTicket::find($ticketId);
    if (!$ticket) {
        return [
            'valid' => false,
            'message' => 'Invalid ticket.'
        ];
    }
    
    // Check basic validity (active, not expired, usage limit)
    if (!$this->isValid()) {
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'This promo code is inactive.'
            ];
        }
        
        if (now()->lt($this->start_date)) {
            return [
                'valid' => false,
                'message' => 'This promo code is not yet active.'
            ];
        }
        
        if (now()->gt($this->end_date)) {
            return [
                'valid' => false,
                'message' => 'This promo code has expired.'
            ];
        }
        
        if ($this->hasReachedUsageLimit()) {
            return [
                'valid' => false,
                'message' => 'This promo code has reached its usage limit.'
            ];
        }
    }
    
    // Check minimum purchase if specified
    if ($this->min_purchase && $totalAmount < $this->min_purchase) {
        return [
            'valid' => false,
            'message' => 'Minimum purchase of Rp ' . number_format($this->min_purchase, 0, ',', '.') . ' required.'
        ];
    }
    
    // Check visit date validity if provided
    if ($visitDate) {
        $visitDateObj = Carbon::parse($visitDate)->startOfDay();
        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->endOfDay();
        
        if (!$visitDateObj->between($startDate, $endDate)) {
            return [
                'valid' => false,
                'message' => 'This promo code is not valid for the selected visit date.'
            ];
        }
    }
    
    // Check if specific tickets are set and this ticket is included
    if ($this->beachTickets->count() > 0 && !$this->beachTickets->contains('id', $ticketId)) {
        return [
            'valid' => false,
            'message' => 'This promo code is not applicable to this specific beach ticket.'
        ];
    }
    
    return [
        'valid' => true,
        'message' => 'Promo code applied successfully!',
        'discount' => $this->calculateDiscount($totalAmount)
    ];
}
    
    /**
     * Check if the promo has reached its usage limit
     */
    public function hasReachedUsageLimit()
    {
        return $this->max_uses && $this->used_count >= $this->max_uses;
    }
    
    /**
     * Check if promo code applies to rooms
     */
    public function appliesToRooms()
    {
        return $this->applies_to === self::APPLIES_ROOMS;
    }
    
    /**
     * Check if promo code applies to beach tickets
     */
    public function appliesToBeachTickets()
    {
        return $this->applies_to === self::APPLIES_BEACH_TICKETS;
    }
    
    /**
     * Check if promo code is valid for room booking
     */
    public function isValidForRoom($roomId, $totalAmount = 0, $checkInDate = null)
    {
        // Check if this promo applies to rooms
        if (!$this->appliesToRooms()) {
            return [
                'valid' => false,
                'message' => 'This promo code is only valid for beach tickets.'
            ];
        }
        
        // Get the room
        $room = Room::find($roomId);
        if (!$room) {
            return [
                'valid' => false,
                'message' => 'Invalid room.'
            ];
        }
        
        // Check basic validity
        if (!$this->isValid()) {
            return [
                'valid' => false,
                'message' => 'This promo code has expired or reached its usage limit.'
            ];
        }
        
        // Check minimum purchase if specified
        if ($this->min_purchase && $totalAmount < $this->min_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimum purchase of Rp ' . number_format($this->min_purchase, 0, ',', '.') . ' required.'
            ];
        }
        
        // Check check-in date validity if provided
        if ($checkInDate) {
            $checkInDateObj = Carbon::parse($checkInDate)->startOfDay();
            if (!$checkInDateObj->between($this->start_date->startOfDay(), $this->end_date->endOfDay())) {
                return [
                    'valid' => false,
                    'message' => 'This promo code is not valid for the selected check-in date.'
                ];
            }
        }
        
        // Check if specific rooms are set and this room is included
        if ($this->rooms->count() > 0 && !$this->rooms->contains('id', $roomId)) {
            return [
                'valid' => false,
                'message' => 'This promo code is not applicable to this specific room.'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Promo code applied successfully!',
            'discount' => $this->calculateDiscount($totalAmount)
        ];
    }
    
    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->used_count += 1;
        $this->save();
        
        return $this;
    }
}