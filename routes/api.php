<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/signin', [AuthController::class, 'signin']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/resend-verification', [AuthController::class, 'resendVerificationEmail']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboardData', [AdminController::class, 'dashboardData']);

    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getUser/{id}', [UserController::class, 'getUser']);
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);

    Route::get('/getRoles', [RoleController::class, 'getRoles']);
    Route::get('/getRole/{id}', [RoleController::class, 'getRole']);
    Route::post('/createRole', [RoleController::class, 'createRole']);
    Route::delete('/deleteRole/{id}', [RoleController::class, 'deleteRole']);
    Route::post('/updateRole/{id}', [RoleController::class, 'updateRole']);

    Route::get('/getAdmins', [AdminController::class, 'getAdmins']);
    Route::get('/getAdmin/{id}', [AdminController::class, 'getAdmin']);
    Route::post('/createAdmin', [AdminController::class, 'createAdmin']);
    Route::delete('/deleteAdmin/{id}', [AdminController::class, 'deleteAdmin']);
    Route::post('/updateAdmin/{id}', [AdminController::class, 'updateAdmin']);

    Route::get('/getProducts', [ProductController::class, 'getProducts']);
    Route::get('/getProduct/{id}', [ProductController::class, 'getProduct']);
    Route::post('/create-update-product/{id?}', [ProductController::class, 'CreateUpdateProduct']);
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/getCategoriesForSelect', [ProductController::class, 'getCategoriesForSelect']);

    Route::get('/getCategories', [CategoriesController::class, 'getCategories']);
    Route::get('/getCategory/{id}', [CategoriesController::class, 'getCategory']);
    Route::post('/createCategory', [CategoriesController::class, 'create']);
    Route::post('/updateCategory/{id}', [CategoriesController::class, 'update']);
    Route::delete('/deleteCategory/{id}', [CategoriesController::class, 'delete']);
    Route::post('/archiveCategory/{id}', [CategoriesController::class, 'archive']);
});
