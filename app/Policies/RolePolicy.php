<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function view(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('view_roles');
    }

    public function create(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('create_role');
    }

    public function edit(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('edit_role');
    }

    public function delete(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('delete_role');
    }
}
