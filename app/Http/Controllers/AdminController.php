<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboardData()
    {
        $totalUsers = User::where('type', 'users')->count();
        $totalAdmins = User::where('type', 'admin')->count();
        $totalCategories = Category::all()->count();
        $totalProducts = Product::all()->count();
        $totalRoles = Role::count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalUsers' => $totalUsers,
                'totalAdmins' => $totalAdmins,
                'totalRoles' => $totalRoles,
                'totalCategories' => $totalCategories,
                'totalProducts' => $totalProducts,
            ],
        ]);
    }

    public function getAdmins()
    {
        $isAuthorized = auth()->user()->can('viewAdmins', User::class);

        $query = User::where('type', 'admin')
            ->where('id', '!=', auth()->id())
            ->with(['roles:id,name']);

        if (! $isAuthorized) {
            $query->select('id', 'name');
        }

        $admins = $query->get();

        return response()->json([
            'success' => true,
            'admins' => $admins,
        ]);
    }

    public function deleteAdmin($id)
    {
        $admin = User::find($id);

        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
            ], 404);
        }

        $this->authorize('deleteAdmin', User::class);

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully',
        ]);
    }

    public function getAdmin($id)
    {
        $admin = User::where('id', $id)
            ->where(function ($query) {
                $query->where('type', 'admin')->orWhere('type', 'super_admin');
            })
            ->with(['roles:id,name'])
            ->first();

        if (! $admin) {
            return response()->json(['success' => false, 'message' => 'Admin not found'], 404);
        }

        $this->authorize('viewAdmins', User::class);

        return response()->json(['success' => true, 'admin' => $admin]);
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::find($id);

        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
            ], 404);
        }

        $this->authorize('updateAdmin', User::class);

        $validator = Validator::make($request->all(), [
            'editAdminName' => 'required|string|max:255',
            'editAdminEmail' => 'required|email|max:255|unique:users,email,'.$id,
            'editAdminPassword' => 'nullable|string|min:8',
            'editAdminRoles' => 'required|nullable|array',
        ], [
            'editAdminName.required' => 'Name is required',
            'editAdminName.string' => 'Name must be a string',
            'editAdminName.max' => 'Name cannot exceed 255 characters',
            'editAdminEmail.required' => 'Email is required',
            'editAdminEmail.email' => 'Please enter a valid email address',
            'editAdminEmail.max' => 'Email cannot exceed 255 characters',
            'editAdminEmail.unique' => 'This email is already taken',
            'editAdminPassword.min' => 'Password must be at least 8 characters',
            'editAdminRoles.array' => 'Roles must be an array',
            'editAdminRoles.required' => 'At least one role is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $admin->name = $request->editAdminName;
        $admin->email = $request->editAdminEmail;

        if ($request->editAdminPassword) {
            $admin->password = Hash::make($request->editAdminPassword);
        }

        $admin->save();

        if ($request->editAdminRoles) {

            $admin->roles()->sync($request->editAdminRoles);
        } else {
            $admin->roles()->detach();
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully',
        ]);
    }

    public function createAdmin(Request $request)
    {
        $this->authorize('createAdmin', User::class);

        $validator = Validator::make($request->all(), [
            'addAdminName' => 'required|string|max:255',
            'addAdminEmail' => 'required|email|max:255|unique:users,email',
            'addAdminPassword' => 'required|string|min:8',
            'addAdminRoles' => 'required|nullable|array',
        ], [
            'addAdminName.required' => 'Name is required',
            'addAdminName.string' => 'Name must be a string',
            'addAdminName.max' => 'Name cannot exceed 255 characters',
            'addAdminEmail.required' => 'Email is required',
            'addAdminEmail.email' => 'Please enter a valid email address',
            'addAdminEmail.max' => 'Email cannot exceed 255 characters',
            'addAdminEmail.unique' => 'This email is already taken',
            'addAdminPassword.required' => 'Password is required',
            'addAdminPassword.min' => 'Password must be at least 8 characters',
            'addAdminRoles.array' => 'Roles must be an array',
            'addAdminRoles.required' => 'At least one role is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $admin = new User;
        $admin->name = $request->addAdminName;
        $admin->email = $request->addAdminEmail;
        $admin->password = Hash::make($request->addAdminPassword);
        $admin->type = 'admin';
        $admin->email_verified_at = now();
        $admin->save();

        if ($request->addAdminRoles) {
            $admin->roles()->attach($request->addAdminRoles);
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
        ]);
    }
}
