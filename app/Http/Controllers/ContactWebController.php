<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Services\ContactSpamGuard;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ContactWebController extends Controller
{
    public function submit(Request $request, ContactSpamGuard $guard)
    {
        // Anti-spam checks run before validation so bots get no field feedback
        if ($reason = $guard->inspect($request)) {
            if ($guard->isSilentBlock($reason)) {
                // Fake success — the bot learns nothing, no record is stored
                return redirect()->route('contact')
                    ->with('success', __('ui.contact.success_message'));
            }

            return redirect()->route('contact')
                ->withInput()
                ->withErrors([
                    // Show captcha errors under the captcha field itself
                    $reason === 'captcha_failed' ? 'captcha_answer' : 'message' => __('ui.contact.'.$reason),
                ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Identical resubmissions within an hour are acknowledged but not re-stored
        if ($guard->isDuplicate($request)) {
            return redirect()->route('contact')
                ->with('success', __('ui.contact.success_message'));
        }

        $guard->recordSubmission($request);

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'source' => 'web_form',
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        $notifications = app(NotificationService::class);
        $notifications->sendContactConfirmation($submission);
        $notifications->alertAdminNewContact($submission);

        return redirect()->route('contact')
            ->with('success', __('ui.contact.success_message'));
    }
}
