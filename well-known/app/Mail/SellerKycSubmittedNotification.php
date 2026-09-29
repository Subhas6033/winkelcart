<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SellerKycSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $isResubmission;

    public function __construct(User $seller, bool $isResubmission = false)
    {
        $this->seller = $seller;
        $this->isResubmission = $isResubmission;
    }

    public function build()
    {
        $subject = $this->isResubmission
            ? 'KYC Re-Submitted – We Are Reviewing Your Details – WinkelKart'
            : 'KYC Submitted – Awaiting Verification – WinkelKart';

        return $this->subject($subject)
                    ->view('emails.seller-kyc-submitted');
    }
}
