<?php
// app/Policies/IncomingLetterPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\IncomingLetter;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomingLetterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view list
    }

    public function view(User $user, IncomingLetter $letter): bool
    {
        return true; // All authenticated users can view details
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    public function update(User $user, IncomingLetter $letter): bool
    {
        return $user->role === 'admin' || 
               ($user->role === 'staff' && $letter->received_by === $user->id);
    }

    public function delete(User $user, IncomingLetter $letter): bool
    {
        return $user->role === 'admin';
    }

    public function process(User $user, IncomingLetter $letter): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    public function complete(User $user, IncomingLetter $letter): bool
    {
        return $user->role === 'admin' || 
               ($user->role === 'staff' && $letter->processed_by === $user->id);
    }
}