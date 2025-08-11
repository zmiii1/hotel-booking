<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomBookedDate;
use App\Models\Booking;
use App\Models\RoomNumber;
use App\Models\BookingRoomList;

class FixRoomNumberIds extends Command
{
    // The name and signature of the console command
    protected $signature = 'fix:room-number-ids';

    // The console command description
    protected $description = 'Fix bookings that have null room number IDs';

    // Execute the console command
    public function handle()
    {
        $nullRoomBookings = RoomBookedDate::whereNull('room_number_id')
            ->get()
            ->groupBy('booking_id');

        $this->info("Found " . $nullRoomBookings->count() . " bookings with null room number IDs");

        foreach ($nullRoomBookings as $bookingId => $bookedDates) {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                $this->warn("Booking ID $bookingId not found, skipping");
                continue;
            }

            $this->info("Processing booking ID $bookingId (Room ID: {$booking->rooms_id})");

            $roomNumber = RoomNumber::where('rooms_id', $booking->rooms_id)
                ->where('status', 'Active')
                ->first();

            if (!$roomNumber) {
                $this->warn("No active room numbers found for Room ID {$booking->rooms_id}, skipping");
                continue;
            }

            $updateCount = 0;
            foreach ($bookedDates as $bookedDate) {
                $bookedDate->room_number_id = $roomNumber->id;
                $bookedDate->save();
                $updateCount++;
            }

            $this->info("Updated $updateCount booked dates with room number ID {$roomNumber->id}");

            $bookingRoomList = BookingRoomList::where('booking_id', $bookingId)->first();

            if (!$bookingRoomList) {
                $this->info("Creating missing booking room list entry for booking ID $bookingId");

                BookingRoomList::create([
                    'booking_id' => $bookingId,
                    'room_id' => $booking->rooms_id,
                    'room_number_id' => $roomNumber->id
                ]);
            } elseif ($bookingRoomList->room_number_id === null) {
                $this->info("Updating null room number ID in booking room list");

                $bookingRoomList->room_number_id = $roomNumber->id;
                $bookingRoomList->save();
            }
        }

        $this->info("Repair script completed.");
    }
}
