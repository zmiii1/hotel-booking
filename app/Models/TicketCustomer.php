<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Get all orders for this customer
     */
    public function orders()
    {
        return $this->hasMany(TicketOrder::class, 'ticket_customer_id');
    }
    
    /**
     * Get total spent by this customer
     */
    public function getTotalSpentAttribute()
    {
        return $this->orders()->sum('total_price');
    }
    
    /**
     * Get total orders count
     */
    public function getOrdersCountAttribute()
    {
        return $this->orders()->count();
    }
    
    /**
     * Get most recent order
     */
    public function getLastOrderAttribute()
    {
        return $this->orders()->latest()->first();
    }
}