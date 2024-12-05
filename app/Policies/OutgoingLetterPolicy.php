<?php
// app/Policies/OutgoingLetterPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\OutgoingLetter;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutgoingLetterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view list
    }

    public function view(User $user, OutgoingLetter $letter): bool
    {
        return true; // All authenticated users can view details
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    public function update(User $user, OutgoingLetter $letter): bool
    {
        // Can only update if letter is not sent
        if ($letter->status === 'sent') {
            return false;
        }

        return $user->role === 'admin' || 
               ($user->role === 'staff' && $letter->created_by === $user->id);
    }

    public function delete(User $user, OutgoingLetter $letter): bool
    {
        // Can only delete if letter is not sent
        if ($letter->status === 'sent') {
            return false;
        }

        return $user->role === 'admin';
    }

    public function approve(User $user, OutgoingLetter $letter): bool
    {
        return $user->role === 'admin';
    }

    public function send(User $user, OutgoingLetter $letter): bool
    {
        return $user->role === 'admin' || 
               ($user->role === 'staff' && $letter->approved_by !== null);
    }
}