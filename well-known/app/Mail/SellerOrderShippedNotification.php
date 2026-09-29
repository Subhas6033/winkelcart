<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;

class SellerOrderShippedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $order;
    public $orderItems;

    public function __construct(User $seller, Order $order)
    {
        $this->seller = $seller;
        $this->order = $order;
        $this->orderItems = $order->items()->with('product')->get();
    }

    public function build()
    {
        return $this->subject('Shipment Dispatched – Order #' . $this->order->order_number . ' – WinkelKart')
                    ->view('emails.seller-order-shipped');
    }
}
