<?php

namespace App\Mail;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking, $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(ConsultationBooking $booking, $invoice)
    {
        $this->booking = $booking;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pemberitahuan Pemesanan Baru')
                    ->view('emails.new-booking');
    }
}
