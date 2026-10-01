<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
use App\Models\Invoice;

class BookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $invoice;

    public function __construct(Booking $booking, Invoice $invoice)
    {
        $this->booking = $booking;
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \App\Mail\BookingConfirmationMail($this->booking, $this->invoice))
            ->to($this->booking->email, $this->booking->name);
    }
}