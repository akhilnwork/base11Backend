<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contactSubmissions = ContactSubmission::latest()->paginate(20);

        $unreadCount = ContactSubmission::unread()->count();
        $totalCount = ContactSubmission::count();

        return view('admin.contact-submissions.index', compact('contactSubmissions', 'unreadCount', 'totalCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactSubmission $contactSubmission): View
    {
        // Mark as read when viewing
        if (!$contactSubmission->is_read) {
            $contactSubmission->markAsRead();
        }

        return view('admin.contact-submissions.show', compact('contactSubmission'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactSubmission $contactSubmission): RedirectResponse
    {
        $contactSubmission->delete();

        return redirect()
            ->route('admin.contact-submissions.index')
            ->with('success', 'Contact submission deleted successfully.');
    }

    /**
     * Mark a contact submission as read.
     */
    public function markAsRead(ContactSubmission $contactSubmission): RedirectResponse
    {
        $contactSubmission->markAsRead();

        return redirect()
            ->route('admin.contact-submissions.index')
            ->with('success', 'Contact submission marked as read.');
    }

    /**
     * Mark all contact submissions as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        ContactSubmission::unread()->update(['is_read' => true]);

        return redirect()
            ->route('admin.contact-submissions.index')
            ->with('success', 'All contact submissions marked as read.');
    }
}
