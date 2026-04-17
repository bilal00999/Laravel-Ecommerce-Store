<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Http\Requests\StoreContactMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContactController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the contact form (GET /contact).
     * Only accessible to authenticated users via 'auth' middleware.
     */
    public function show()
    {
        return view('contact.show');
    }

    /**
     * Store the contact message (POST /contact).
     * Validates input using StoreContactMessageRequest.
     * Saves to contact_messages table.
     */
    public function store(StoreContactMessageRequest $request)
    {
        // The request is already validated at this point
        $validated = $request->validated();

        // Create contact message with authenticated user's ID
        $message = ContactMessage::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'pending',
        ]);

        // Optional: Send email notification to admin
        // Mail::to(config('mail.from.address'))->send(new ContactMessageReceivedMail($message));

        return redirect()->route('contact.show')
            ->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }

    /**
     * Display user's contact messages history (optional).
     * GET /contact/history - Show all messages submitted by logged-in user
     */
    public function history()
    {
        $messages = auth()->user()
            ->contactMessages()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('contact.history', ['messages' => $messages]);
    }

    /**
     * Show a single contact message (optional).
     * GET /contact/{contactMessage} - View details of a submitted message
     */
    public function showMessage(ContactMessage $contactMessage)
    {
        // Authorize: User can only view their own messages
        $this->authorize('view', $contactMessage);

        // Mark as read if still pending
        if ($contactMessage->status === 'pending') {
            $contactMessage->markAsRead();
        }

        return view('contact.message-detail', ['message' => $contactMessage]);
    }
}
