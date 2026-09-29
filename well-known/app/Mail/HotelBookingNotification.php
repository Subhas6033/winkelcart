<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\User;

class HotelBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $booking;
    public $hotel;
    public $room;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Booking $booking)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->hotel = $booking->hotel;
        $this->room = $booking->room;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Booking Confirmation - WinkelKart Hotels #' . $this->booking->booking_code)
                    ->view('emails.hotel-booking');
    }
}
