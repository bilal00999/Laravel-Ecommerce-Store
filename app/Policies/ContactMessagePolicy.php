<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    /**
     * Determine if the user can view the contact message.
     * Only the message creator or admin can view.
     */
    public function view(User $user, ContactMessage $message): bool
    {
        // User can view their own message
        if ($user->id === $message->user_id) {
            return true;
        }

        // Admin can view any message
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the contact message.
     * Only the message creator or admin can delete.
     */
    public function delete(User $user, ContactMessage $message): bool
    {
        // User can delete their own message
        if ($user->id === $message->user_id) {
            return true;
        }

        // Admin can delete any message
        if ($user->is_admin) {
            return true;
        }

        return false;
    }
}
