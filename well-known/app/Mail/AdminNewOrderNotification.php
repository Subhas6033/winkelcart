<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;

class AdminNewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $buyer;
    public $orderItems;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->buyer = $order->buyer;
        $this->orderItems = $order->items()->with('product')->get();
    }

    public function build()
    {
        return $this->subject('New Order Placed – #' . $this->order->order_number . ' – WinkelKart')
                    ->view('emails.admin-new-order');
    }
}
