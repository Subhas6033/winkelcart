<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;

class OrderStatusUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $status;
    public $note;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Order $order, $status, $note = null)
    {
        $this->user = $user;
        $this->order = $order;
        $this->status = $status;
        $this->note = $note;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Update: ' . $this->status . ' - WinkelKart #' . $this->order->order_number)
                    ->view('emails.order-status-update');
    }
}
