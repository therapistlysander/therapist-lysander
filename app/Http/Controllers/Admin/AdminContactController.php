<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\ContactNote;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactSubmission::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $contacts = $query->paginate(20)->withQueryString();

        return view('admin.pages.contacts.index', compact('contacts'));
    }

    public function show(ContactSubmission $contact)
    {
        $contact->load('notes.author');

        // Mark as read when viewed
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.pages.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, ContactSubmission $contact)
    {
        $request->validate(['status' => 'required|in:new,read,replied,resolved']);
        $contact->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }

    public function addNote(Request $request, ContactSubmission $contact)
    {
        $request->validate(['note' => 'required|string|max:2000']);

        ContactNote::create([
            'contact_submission_id' => $contact->id,
            'user_id'               => auth()->id(),
            'note'                  => $request->note,
        ]);

        return back()->with('success', 'Note added.');
    }
}
