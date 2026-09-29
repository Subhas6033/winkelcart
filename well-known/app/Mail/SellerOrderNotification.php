<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

class SellerOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $buyer;
    public $order;
    public $sellerItems;
    public $sellerTotal;

    /**
     * Create a new message instance.
     *
     * @param User $seller The seller receiving the notification
     * @param User $buyer The customer who placed the order
     * @param Order $order The order
     * @param Collection $sellerItems Items from this order that belong to this seller
     */
    public function __construct(User $seller, User $buyer, Order $order, Collection $sellerItems)
    {
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->order = $order;
        $this->sellerItems = $sellerItems;
        $this->sellerTotal = $sellerItems->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Order Received - WinkelKart #' . $this->order->order_number)
                    ->view('emails.seller-order-notification');
    }
}
