<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->boolean('view_products')->default(false);
            $table->boolean('create_product')->default(false);
            $table->boolean('edit_product')->default(false);
            $table->boolean('delete_product')->default(false);

            $table->boolean('view_users')->default(false);
            $table->boolean('edit_user')->default(false);
            $table->boolean('delete_user')->default(false);

            $table->boolean('view_admins')->default(false);
            $table->boolean('create_admin')->default(false);
            $table->boolean('edit_admin')->default(false);
            $table->boolean('delete_admin')->default(false);

            $table->boolean('view_roles')->default(false);
            $table->boolean('create_role')->default(false);
            $table->boolean('edit_role')->default(false);
            $table->boolean('delete_role')->default(false);

            $table->boolean('view_categories')->default(false);
            $table->boolean('create_category')->default(false);
            $table->boolean('edit_category')->default(false);
            $table->boolean('delete_category')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
