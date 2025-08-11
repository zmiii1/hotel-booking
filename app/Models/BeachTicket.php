<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeachTicket extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang benar
    protected $table = 'beach_tickets';

    protected $fillable = [
        'name', 'description', 'price', 'beach_name', 'ticket_type', 'image_url', 'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function benefits()
    {
        return $this->hasMany(TicketBenefit::class);
    }

    public function orders()
    {
        return $this->hasMany(TicketOrder::class);
    }
    
    public function getFormattedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }
    
    public function getImageUrlAttribute($value)
    {
        // If image URL starts with http, it's an external URL
        if ($value && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))) {
            return $value;
        }
        
        // If image URL is provided but not external, prepend storage path
        if ($value) {
            return asset('storage/' . $value);
        }
        
        // Default image based on beach name
        if ($this->beach_name == 'lalassa') {
            return asset('frontend/assets/img/lalassa-beach.jpg');
        }
        
        if ($this->beach_name == 'bodur') {
            return asset('frontend/assets/img/bodur-beach.jpeg');
        }
        
        // Fallback image
        return asset('frontend/assets/img/beach-placeholder.jpg');
    }
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}