<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\DataTableTrait;
use App\Models\ContactSubmission;
use App\Models\ContactNote;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    use DataTableTrait;

    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        $this->applySearch($query, $request->get('search'), ['name', 'email', 'message']);
        $this->applyFilter($query, 'status', $request->get('status'));
        $this->applySort($query, 'created_at', ['created_at', 'status', 'name']);

        $contacts = $this->paginateResults($query, 10);

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

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_submissions,id',
            'action' => 'required|in:delete,mark_read,mark_replied,mark_resolved',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'delete') {
            ContactSubmission::whereIn('id', $ids)->delete();
            return redirect()->route('admin.contacts.index')->with('success', count($ids) . ' message(s) deleted.');
        }

        $statusMap = [
            'mark_read' => 'read',
            'mark_replied' => 'replied',
            'mark_resolved' => 'resolved',
        ];

        ContactSubmission::whereIn('id', $ids)->update(['status' => $statusMap[$action]]);
        return redirect()->route('admin.contacts.index')->with('success', count($ids) . ' message(s) updated.');
    }
}
