<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('view_users');
    }

    public function edit(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('edit_user');
    }

    public function delete(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('delete_user');
    }

    public function viewAdmins(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('view_admins');
    }

    public function createAdmin(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('create_admin');
    }

    public function updateAdmin(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('edit_admin');
    }

    public function deleteAdmin(User $authUser): bool
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('delete_admin');
    }
}
