<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ContactWebController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $submission = ContactSubmission::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'message' => $validated['message'],
            'source'  => 'web_form',
            'status'  => 'new',
            'ip_address' => $request->ip(),
        ]);

        $notifications = app(NotificationService::class);
        $notifications->sendContactConfirmation($submission);
        $notifications->alertAdminNewContact($submission);

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent! I will get back to you within 24 hours.');
    }
}
