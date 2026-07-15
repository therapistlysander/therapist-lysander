<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTableTrait;
use App\Models\ContactSubmission;
use App\Models\ContactNote;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    use AdminTableTrait;

    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        // Search
        $this->applySearch($query, ['name', 'email'], $request->input('search'));

        // Filters
        $this->applyFilters($query, ['status' => 'status']);

        // Sorting
        $this->applySort($query, ['name', 'status', 'created_at'], 'created_at', 'desc');

        $contacts = $this->safePaginate($query->paginate($this->getPerPage($request, 20)));

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
