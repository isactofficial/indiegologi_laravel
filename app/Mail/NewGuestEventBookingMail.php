<?php

namespace App\Mail;

use App\Models\EventBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewGuestEventBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking, $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(EventBooking $booking)
    {
        $this->booking = $booking;
        // $this->invoice = $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pemberitahuan Pemesanan Baru')
                    ->view('emails.new-guest-event-booking');
    }
}
