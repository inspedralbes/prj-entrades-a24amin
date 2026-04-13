<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load(['event', 'tickets.seat.zone', 'user']);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmació de Compra - Shôko Barcelona')
            ->view('emails.order_confirmed');
    }
}