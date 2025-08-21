<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'view_products',
        'create_product',
        'edit_product',
        'delete_product',
        'view_users',
        'edit_user',
        'delete_user',
        'view_admins',
        'create_admin',
        'edit_admin',
        'delete_admin',
        'view_roles',
        'create_role',
        'edit_role',
        'delete_role',
        'view_categories',
        'create_category',
        'edit_category',
        'delete_category',
    ];

    protected $casts = [
        'view_products' => 'boolean',
        'create_product' => 'boolean',
        'edit_product' => 'boolean',
        'delete_product' => 'boolean',
        'view_users' => 'boolean',
        'edit_user' => 'boolean',
        'delete_user' => 'boolean',
        'view_admins' => 'boolean',
        'create_admin' => 'boolean',
        'edit_admin' => 'boolean',
        'delete_admin' => 'boolean',
        'view_roles' => 'boolean',
        'create_role' => 'boolean',
        'edit_role' => 'boolean',
        'delete_role' => 'boolean',
        'view_categories' => 'boolean',
        'create_category' => 'boolean',
        'edit_category' => 'boolean',
        'delete_category' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function hasPermission(string $permission): bool
    {
        return $this->$permission === true;
    }

    public function getPermissions(): array
    {
        return [
            'view_products' => $this->view_products,
            'create_product' => $this->create_product,
            'edit_product' => $this->edit_product,
            'delete_product' => $this->delete_product,
            'view_users' => $this->view_users,
            'edit_user' => $this->edit_user,
            'delete_user' => $this->delete_user,
            'view_admins' => $this->view_admins,
            'create_admin' => $this->create_admin,
            'edit_admin' => $this->edit_admin,
            'delete_admin' => $this->delete_admin,
            'view_roles' => $this->view_roles,
            'create_role' => $this->create_role,
            'edit_role' => $this->edit_role,
            'delete_role' => $this->delete_role,
            'view_categories' => $this->view_categories,
            'create_category' => $this->create_category,
            'edit_category' => $this->edit_category,
            'delete_category' => $this->delete_category,
        ];
    }
}
