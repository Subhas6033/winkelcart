<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\SellerKycVerification;

class AdminMembershipPaidNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $kyc;

    public function __construct(User $seller, SellerKycVerification $kyc)
    {
        $this->seller = $seller;
        $this->kyc = $kyc;
    }

    public function build()
    {
        return $this->subject('Seller Membership Payment Received – ₹999 – WinkelKart')
                    ->view('emails.admin-membership-paid');
    }
}
