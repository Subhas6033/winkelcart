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

    public static function user_register_email($data)
    {
        try {
            \Log::info('Mail function called', $data);

            Mail::send([], [], function ($message) use ($data) {

                $html = "
                    <h2>Welcome to Winkelkart</h2>
                    <p>Hello <strong>{$data['name']}</strong>,</p>

                    <p>Thank you for registering with us.</p>

                    <p>
                        <strong>Temporary Password:</strong> {$data['password']}<br>
                        <strong>Phone:</strong> {$data['phone']}
                    </p>

                    <p>Regards,<br><strong>Winkelkart Team</strong></p>
                ";

                $message->from('info@winkelkart.com', 'Winkelkart')
                        ->to($data['email'])
                        ->replyTo('info@winkelkart.com')
                        ->subject('Welcome to Winkelkart');

                // ✅ THIS IS THE FIX
                $message->setBody($html, 'text/html');
            });

            \Log::info('Mail function completed');
            return true;

        } catch (\Exception $e) {
            \Log::error('Mail exception: '.$e->getMessage());
            return false;
        }
    }


    // public static function user_register_email($data)
    // {
    //     try {
    //         \Log::info('Mail function called2', $data);

    //         $messageBody = "Hello {$data['name']},

    //             Thank you for registering with Winkelkart.

    //             Temporary Password: {$data['password']}
    //             Phone: {$data['phone']}

    //             Regards,
    //             Winkelkart Team";

    //         Mail::raw($messageBody, function ($message) use ($data) {
    //             $message->from('info@winkelkart.com', 'Winkelkart')
    //                     ->to($data['email'])
    //                     ->subject('Registration Successful');
    //         });

    //         if (count(Mail::failures()) > 0) {
    //             \Log::error('Mail failed', Mail::failures());
    //             return false;
    //         }

    //         \Log::info('Mail function completed');
    //         return true;

    //     } catch (\Exception $e) {
    //         \Log::error('Mail exception: '.$e->getMessage());
    //         return false;
    //     }
    // }

    // public static function user_register_email($data){
    //     \Log::info('Mail function called', $data);

    //     Mail::raw('Hello', function ($message) use ($data) {
    //         $message->from('info@winkelkart.com', 'Winkelkart')
    //                 ->to($data['email'])
    //                 ->subject('Registration Successful');
    //     });

    //     \Log::info('Mail function completed');

    //     return true;

    //     $toUser = $data['email'];
    //     $subjectUser = "Registration Successful";
    //     $messageUser = "Hello {$data['name']},\n\nThank you for registering with us. Temporary password: {$data['password']}\n\nDetails:\n"
    //         . "Name: {$data['name']}\n"
    //         . "Phone: {$data['phone']}\n\n"
    //         . "Best Regards,\nTeam";
    //     $messageUser = 'Hello';

    //     Mail::raw($messageUser, function ($message) use ($toUser, $subjectUser) {
    //         $message->to($toUser)
    //                 ->subject($subjectUser);
    //     });

    //     return "Email sent successfully!";
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