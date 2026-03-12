<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPartiallyRefundedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $amount;

    public function __construct(Order $order, float $amount)
    {
        $this->order = $order;
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->subject('Order #' . $this->order->id . ' – Partial Refund Processed')
                    ->view('emails.order-partially-refunded');
    }
}
