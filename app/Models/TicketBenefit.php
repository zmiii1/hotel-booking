<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBenefit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'beach_ticket_id',
        'description',
        'icon',
        'order'
    ];
    
    /**
     * The ticket this benefit belongs to
     */
    public function ticket()
    {
        return $this->belongsTo(BeachTicket::class, 'beach_ticket_id');
    }
    
    /**
     * Scope a query to order by the order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}