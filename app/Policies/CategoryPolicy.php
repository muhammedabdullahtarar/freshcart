<?php

namespace App\Policies;

use App\Models\User;

class CategoryPolicy
{
    public function view(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('view_categories');
    }

    public function create(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('create_category');
    }

    public function edit(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('edit_category');
    }

    public function delete(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('delete_category');
    }
}
