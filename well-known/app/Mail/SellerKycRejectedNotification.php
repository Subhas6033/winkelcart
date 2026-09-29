<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SellerKycRejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $adminNote;

    public function __construct(User $seller, ?string $adminNote = null)
    {
        $this->seller = $seller;
        $this->adminNote = $adminNote;
    }

    public function build()
    {
        return $this->subject('KYC Verification Rejected – Action Required – WinkelKart')
                    ->view('emails.seller-kyc-rejected');
    }
}
