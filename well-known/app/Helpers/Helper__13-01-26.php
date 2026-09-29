<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Helper
{
    public static function seller_register_email($data){
        $toUser = $data['email'];
        $subjectUser = "Business Registration Successful";
        $messageUser = "Hello {$data['name']},\n\nThank you for registering your business {$data['business']} with us. Temporary password: {$data['password']}\n\nDetails:\n"
            . "Business Name: {$data['business']}\n"
            . "Address: {$data['address']}\n"
            . "Phone: {$data['phone']}\n"
            . "Business Type: {$data['businessType']}\n"
            . "State: {$data['state']}\n"
            . "Country: {$data['country']}\n"
            . "We will review your registration and contact you soon.\n\nBest Regards,\nTeam";
        // $headersUser = "From: no-reply@yourdomain.com\r\nReply-To: no-reply@yourdomain.com";


        Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
            $message->to($toUser)
                    ->subject($subjectUser);
        });


        return "Email sent successfully!";
    }

    public static function admin_seller_register_email($data){
        $toUser = 'info@winkelkart.com';
        $subjectUser = "New Business Registration: {$data['business']}";
        $messageUser = "A new business has been registered.\n\nDetails:\n"
            . "Business Name: {$data['business']}\n"
            . "Address: {$data['address']}\n"
            . "Phone: {$data['phone']}\n"
            . "Business Type: {$data['businessType']}\n"
            . "State: {$data['state']}\n"
            . "Country: {$data['country']}\n";
        // $headersUser = "From: no-reply@yourdomain.com\r\nReply-To: no-reply@yourdomain.com";


        Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
            $message->to($toUser)
                    ->subject($subjectUser);
        });

        return "Email sent successfully!";
    }

    public static function user_register_email($data){
        $toUser = $data['email'];
        $subjectUser = "Registration Successful";
        $messageUser = "Hello {$data['name']},\n\nThank you for registering with us. Temporary password: {$data['password']}\n\nDetails:\n"
            . "Name: {$data['name']}\n"
            . "Phone: {$data['phone']}\n\n"
            . "Best Regards,\nTeam";

        Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
            $message->to($toUser)
                    ->subject($subjectUser);
        });

        return "Email sent successfully!";
    }
    // public static function user_register_email($data)
    // {
    //     if (empty($data['email'])) {
    //         \Log::error('User register email failed: email missing', $data);
    //         return;
    //     }

    //     $toUser = $data['email'];        
    //     $subjectUser = "Registration Successful";

    //     $messageUser = "Hello {$data['name']}," . PHP_EOL . PHP_EOL .
    //         "Thank you for registering with us. Temporary password: {$data['password']}" . PHP_EOL . PHP_EOL .
    //         "Details:" . PHP_EOL .
    //         "Name: {$data['name']}" . PHP_EOL .
    //         "Phone: {$data['phone']}" . PHP_EOL . PHP_EOL .
    //         "Best Regards," . PHP_EOL . "Team Winkel";

    //     Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
    //         $message->from(config('mail.from.address'), config('mail.from.name'))
    //                 ->to($toUser)
    //                 ->subject($subjectUser);
    //     });

    //     return "User email sent successfully!";
    // }

    public static function admin_buyer_register_email($data){
        $toUser = 'info@winkelkart.com';
        $subjectUser = "New Buyer Registration: {$data['name']}";
        $messageUser = "A new buyer has been registered.\n\nDetails:\n"
            . "Name: {$data['name']}\n"
            . "Phone: {$data['phone']}\n"
            . "Email: {$data['email']}\n";
        Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
            $message->to($toUser)
                    ->subject($subjectUser);
        });
        return "Email sent successfully!";
    }

    public static function create_order_number($length = null)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}