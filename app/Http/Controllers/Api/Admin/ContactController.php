<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\ContactNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ContactSubmission::withCount('notes')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(20));
    }

    public function show(ContactSubmission $contactSubmission): JsonResponse
    {
        // Auto-mark as read when viewed
        if ($contactSubmission->status === 'new') {
            $contactSubmission->update(['status' => 'read']);
        }

        return response()->json($contactSubmission->load(['notes.author:id,name']));
    }

    public function updateStatus(Request $request, ContactSubmission $contactSubmission): JsonResponse
    {
        $validated = $request->validate([
            'status'      => ['required', 'string', 'in:new,read,replied,archived'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $contactSubmission->update($validated);

        return response()->json($contactSubmission);
    }

    public function destroy(ContactSubmission $contactSubmission): JsonResponse
    {
        $contactSubmission->delete();

        return response()->json(['message' => 'Submission deleted.']);
    }

    // Notes

    public function storeNote(Request $request, ContactSubmission $contactSubmission): JsonResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $note = $contactSubmission->notes()->create([
            'user_id' => $request->user()->id,
            'note'    => $validated['note'],
        ]);

        return response()->json($note->load('author:id,name'), 201);
    }

    public function destroyNote(ContactSubmission $contactSubmission, ContactNote $contactNote): JsonResponse
    {
        abort_unless($contactNote->contact_submission_id === $contactSubmission->id, 404);

        $contactNote->delete();

        return response()->json(['message' => 'Note deleted.']);
    }
}
