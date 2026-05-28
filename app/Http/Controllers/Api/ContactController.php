<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['source']     = 'contact-form';

        $submission = ContactSubmission::create($validated);

        $notifications = app(NotificationService::class);
        $notifications->sendContactConfirmation($submission);
        $notifications->alertAdminNewContact($submission);

        return response()->json([
            'message' => 'Your message has been received. We will respond within 2 business days.',
        ], 201);
    }
}
