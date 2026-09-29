<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;
use App\Services\InvoiceService;

class OrderPlacedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $orderItems;
    protected $invoicePdf;
    protected $invoiceFilename;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
        $this->orderItems = $order->items()->with('product')->get();
        
        // Generate invoice PDF
        $invoiceService = new InvoiceService();
        $this->invoicePdf = $invoiceService->getInvoicePdfOutput($order);
        $this->invoiceFilename = $invoiceService->getInvoiceFilename($order);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Confirmation - WinkelKart #' . $this->order->order_number)
                    ->view('emails.order-placed')
                    ->attachData($this->invoicePdf, $this->invoiceFilename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
