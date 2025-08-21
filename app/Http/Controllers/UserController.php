<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers()
    {
        $isAuthorized = auth()->user()->can('view', User::class);

        $query = User::where('type', 'user');

        if (! $isAuthorized) {
            $query->select('id', 'name');
        }

        $users = $query->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $this->authorize('delete', User::class);

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    public function getUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $this->authorize('view', User::class);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $this->authorize('edit', User::class);

        $validator = Validator::make($request->all(), [
            'editUserName' => 'required|string|max:255',
            'editUserEmail' => 'required|email|max:255|unique:users,email,'.$id,
            'editUserPassword' => 'nullable|string|min:8',
            'editUserStatus' => 'required|in:verified,unverified',
        ], [
            'editUserName.required' => 'Name is required',
            'editUserName.string' => 'Name must be a string',
            'editUserName.max' => 'Name cannot exceed 255 characters',
            'editUserEmail.required' => 'Email is required',
            'editUserEmail.email' => 'Please enter a valid email address',
            'editUserEmail.max' => 'Email cannot exceed 255 characters',
            'editUserEmail.unique' => 'This email is already taken',
            'editUserPassword.min' => 'Password must be at least 8 characters',
            'editUserStatus.required' => 'Status is required',
            'editUserStatus.in' => 'Invalid status selected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->name = $request->editUserName;
        $user->email = $request->editUserEmail;

        if ($request->editUserPassword) {
            $user->password = Hash::make($request->editUserPassword);
        }

        $user->email_verified_at = $request->editUserStatus === 'verified' ? now() : null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }
}
