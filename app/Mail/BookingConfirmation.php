<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $roomName;
    public $nights;
    public $checkInFormatted;
    public $checkOutFormatted;
    public $formattedTotal;
    public $addonTotal;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     * @param string $roomName
     * @return void
     */
    public function __construct(Booking $booking, $roomName = null)
    {
        $this->booking = $booking;

        // Set room name
        if ($roomName) {
            $this->roomName = $roomName;
        } elseif ($booking->roomType) {
            $this->roomName = $booking->roomType->name;
        } elseif ($booking->room) {
            if ($booking->room instanceof \Illuminate\Database\Eloquent\Collection) {
                $this->roomName = $booking->room->isNotEmpty() ? $booking->room->first()->name : "Standard Room";
            } else {
                $this->roomName = $booking->room->name ?? "Standard Room";
            }
        } else {
            $this->roomName = "Standard Room";
        }
        
        // Hitung jumlah malam
        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);
        $this->nights = $checkIn->diffInDays($checkOut);
        
        // Format tanggal
        $this->checkInFormatted = $checkIn->format('D, d M Y');
        $this->checkOutFormatted = $checkOut->format('D, d M Y');
        
        // Format harga
        $this->formattedTotal = number_format($booking->total_amount, 0, ',', '.');
        
        // Hitung addon total dengan benar
        $this->addonTotal = 0;
        foreach ($booking->addons as $addon) {
            $this->addonTotal += $addon->pivot->total_price;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Confirmation - ' . $this->booking->code)
                    ->view('emails.booking.confirmation');
    }
}