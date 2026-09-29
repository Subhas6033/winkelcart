<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginTime;
    public $ipAddress;
    public $userAgent;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $ipAddress = null, $userAgent = null)
    {
        $this->user = $user;
        $this->loginTime = now()->format('d M Y, h:i A');
        $this->ipAddress = $ipAddress ?? 'Unknown';
        $this->userAgent = $userAgent ?? 'Unknown';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Login to Your WinkelKart Account')
                    ->view('emails.login-notification');
    }
}
