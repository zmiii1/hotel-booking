<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\TicketPayment;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'beach_ticket_id',
        'customer_name',
        'customer_email',     // Nullable for POS orders
        'customer_phone',     // Nullable for POS orders
        'visit_date',
        'quantity',
        'additional_request',
        'subtotal',
        'discount',
        'total_price',
        'promo_code_id',
        'payment_method',
        'payment_status',
        'amount_tendered',
        'transaction_id',
        'paid_at',
        'cashier_id',
        'is_offline_order',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'paid_at' => 'datetime',
        'is_offline_order' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code = 'TIX-' . strtoupper(Str::random(8));
        });
    }

    public function ticket()
    {
        return $this->belongsTo(BeachTicket::class, 'beach_ticket_id');
    }

    public function benefits()
    {
        return $this->hasMany(TicketBenefit::class, 'beach_ticket_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    
    public function payment()
    {
        return $this->hasOne(TicketPayment::class, 'order_code', 'order_code');
    }
    
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp. ' . number_format($this->total_price, 0, ',', '.');
    }
    
    public function getFormattedVisitDateAttribute()
    {
        return $this->visit_date->format('d F Y');
    }
    
    public function getStatusBadgeAttribute()
    {
        $status = $this->payment_status;
        
        if ($status == 'paid') {
            return '<span class="badge bg-success">Paid</span>';
        } elseif ($status == 'pending') {
            return '<span class="badge bg-warning">Pending</span>';
        } elseif ($status == 'cancelled') {
            return '<span class="badge bg-danger">Cancelled</span>';
        } else {
            return '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
        }
    }
    
    public function getPaymentMethodBadgeAttribute()
    {
        $method = $this->payment_method;
        
        if ($method == 'cash') {
            return '<span class="badge bg-success">Cash</span>';
        } elseif ($method == 'card') {
            return '<span class="badge bg-info">Card</span>';
        } elseif ($method == 'web_checkout') {
            return '<span class="badge bg-primary">Web Checkout</span>';
        } else {
            return '<span class="badge bg-secondary">' . ucfirst($method) . '</span>';
        }
    }
    
    /**
     * Get customer display name for receipts
     */
    public function getCustomerDisplayNameAttribute()
    {
        return $this->customer_name ?: 'Walk-in Guest';
    }
    
    /**
     * Get customer email for display (hide POS system emails)
     */
    public function getCustomerDisplayEmailAttribute()
    {
        // Hide system emails for POS orders
        if ($this->isPosOrder() && in_array($this->customer_email, ['pos@system.local', 'system@pos.local'])) {
            return null;
        }
        return $this->customer_email;
    }
    
    /**
     * Get customer phone for display (hide POS system phones)
     */
    public function getCustomerDisplayPhoneAttribute()
    {
        // Hide system phones for POS orders
        if ($this->isPosOrder() && in_array($this->customer_phone, ['000000000000', '111111111111'])) {
            return null;
        }
        return $this->customer_phone;
    }
    
    /**
     * Check if this is an online order (has real email)
     */
    public function isOnlineOrder()
    {
        return !$this->is_offline_order && 
               !empty($this->customer_email) && 
               !in_array($this->customer_email, ['pos@system.local', 'system@pos.local']);
    }
    
    /**
     * Check if this is a POS order
     */
    public function isPosOrder()
    {
        return $this->is_offline_order && !empty($this->cashier_id);
    }
}