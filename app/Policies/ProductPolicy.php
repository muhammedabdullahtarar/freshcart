<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function view(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('view_products');
    }

    public function create(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('create_product');
    }

    public function edit(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('edit_product');
    }

    public function delete(User $authUser)
    {
        if ($authUser->type !== 'admin' && $authUser->type !== 'super_admin') {
            return false;
        }

        return $authUser->isSuperAdmin() || $authUser->hasPermission('delete_product');
    }
}
