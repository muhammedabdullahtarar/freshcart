<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class


    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
