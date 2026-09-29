<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;

class OrderShippedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $awbCode;
    public $courierName;

    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
        $this->awbCode = $order->awb_code;
        $this->courierName = $order->courier_name ?? 'Our courier partner';
    }

    public function build()
    {
        return $this->subject('Your Order Has Been Shipped! – WinkelKart #' . $this->order->order_number)
                    ->view('emails.order-shipped');
    }
}
