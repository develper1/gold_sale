<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class LowInventoryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $currentQuantity;
    public $threshold;

    public function __construct(Product $product, $currentQuantity, $threshold)
    {
        $this->product = $product;
        $this->currentQuantity = $currentQuantity;
        $this->threshold = $threshold;
    }

    public function build()
    {
        return $this->subject('Low Inventory Alert: ' . $this->product->name)
                    ->view('emails.low-inventory-notification');
    }
}

