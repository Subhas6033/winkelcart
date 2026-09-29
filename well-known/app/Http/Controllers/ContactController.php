<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactSubmission;
use App\Models\AdminNotification;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validate input

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_person' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'state_type' => 'required|string|max:255',
            'country_type' => 'required|string|max:255',
        ]);

        // Save to database
        $submission = ContactSubmission::create($validated);

        // Create admin notification for contact query
        AdminNotification::notify(
            AdminNotification::TYPE_CONTACT_QUERY,
            'New Contact Query',
            'Contact form submitted by ' . $validated['contact_person'] . ' from ' . $validated['business_name'],
            [
                'link' => route('admin.contact_queries.index'),
                'related_id' => $submission->id,
                'related_type' => ContactSubmission::class,
            ]
        );

        // Send email (customize as needed)
        Mail::raw(
            "Business Name: {$validated['business_name']}\n" .
            "Email: {$validated['email']}\n" .
            "Contact Person: {$validated['contact_person']}\n" .
            "Address: {$validated['address']}\n" .
            "State: {$validated['state_type']}\n" .
            "Country: {$validated['country_type']}\n",
            function ($message) use ($validated) {
                $message->to('your@email.com') // Change to your destination email
                        ->subject('Contact Form Submission from ' . $validated['business_name'])
                        ->replyTo($validated['email']);
            }
        );

        return back()->with('success', 'Message sent and saved successfully!');
    }
}
