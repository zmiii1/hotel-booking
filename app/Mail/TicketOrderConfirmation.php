<?php

namespace App\Mail;

use App\Models\TicketOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param TicketOrder $order
     * @return void
     */
    public function __construct(TicketOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Beach Ticket Order Confirmation - ' . $this->order->order_code)
                    ->view('emails.ticket.confirmation');
    }
}