<?php
// app/Policies/LetterTemplatePolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\LetterTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view list
    }

    public function view(User $user, LetterTemplate $template): bool
    {
        return true; // All authenticated users can view details
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, LetterTemplate $template): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, LetterTemplate $template): bool
    {
        return $user->role === 'admin';
    }
}