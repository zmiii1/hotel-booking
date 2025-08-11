<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_order_id', 'order_code', 'payment_id', 'external_id', 'payment_status',
        'amount', 'checkout_url', 'expired_at', 'paid_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(TicketOrder::class, 'ticket_order_id');
    }
}