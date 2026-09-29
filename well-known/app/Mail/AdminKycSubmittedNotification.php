<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\SellerKycVerification;

class AdminKycSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $kyc;
    public $isResubmission;

    public function __construct(User $seller, SellerKycVerification $kyc, bool $isResubmission = false)
    {
        $this->seller = $seller;
        $this->kyc = $kyc;
        $this->isResubmission = $isResubmission;
    }

    public function build()
    {
        $subject = $this->isResubmission
            ? 'Seller KYC Re-Submitted – Review Required – WinkelKart'
            : 'New Seller KYC Submitted – Review Required – WinkelKart';

        return $this->subject($subject)
                    ->view('emails.admin-kyc-submitted');
    }
}
