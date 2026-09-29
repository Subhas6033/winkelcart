<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SellerKycApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(User $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject('KYC Approved – You Can Now Sell on WinkelKart!')
                    ->view('emails.seller-kyc-approved');
    }
}
