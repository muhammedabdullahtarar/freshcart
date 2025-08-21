<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function getRoles()
    {
        $isAuthorized = auth()->user()->can('view', Role::class);

        $query = Role::query();

        if ($isAuthorized) {
            $roles = $query->get();
        } else {
            $roles = $query->select('id', 'name')->get();
        }

        return response()->json([
            'success' => true,
            'roles' => $roles,
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found',
            ], 404);
        }

        $this->authorize('delete', Role::class);

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully',
        ]);
    }

    public function getRole($id)
    {
        $role = Role::find($id);

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found',
            ], 404);
        }

        $this->authorize('view', Role::class);

        return response()->json(['success' => true, 'role' => $role]);
    }

    public function createRole(Request $request)
    {
        $this->authorize('create', Role::class);

        $validator = Validator::make($request->all(), [
            'addRoleName' => 'required|string|max:255|unique:roles,name',
            'addRolePermissions' => 'required|nullable|array',
        ], [
            'addRoleName.required' => 'Role name is required',
            'addRoleName.string' => 'Role name must be a string',
            'addRoleName.max' => 'Role name cannot exceed 255 characters',
            'addRoleName.unique' => 'This role name already exists',
            'addRolePermissions.array' => 'Permissions must be an array',
            'addRolePermissions.required' => 'At least one permission is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = new Role;
        $role->name = $request->addRoleName;
        $role->view_products = in_array('view_products', $request->addRolePermissions ?? []);
        $role->create_product = in_array('create_products', $request->addRolePermissions ?? []);
        $role->edit_product = in_array('edit_products', $request->addRolePermissions ?? []);
        $role->delete_product = in_array('delete_products', $request->addRolePermissions ?? []);
        $role->view_users = in_array('view_users', $request->addRolePermissions ?? []);
        $role->edit_user = in_array('edit_users', $request->addRolePermissions ?? []);
        $role->delete_user = in_array('delete_users', $request->addRolePermissions ?? []);
        $role->view_admins = in_array('view_admins', $request->addRolePermissions ?? []);
        $role->create_admin = in_array('create_admins', $request->addRolePermissions ?? []);
        $role->edit_admin = in_array('edit_admins', $request->addRolePermissions ?? []);
        $role->delete_admin = in_array('delete_admins', $request->addRolePermissions ?? []);
        $role->view_roles = in_array('view_roles', $request->addRolePermissions ?? []);
        $role->create_role = in_array('create_roles', $request->addRolePermissions ?? []);
        $role->edit_role = in_array('edit_roles', $request->addRolePermissions ?? []);
        $role->delete_role = in_array('delete_roles', $request->addRolePermissions ?? []);
        $role->view_categories = in_array('view_categories', $request->addRolePermissions ?? []);
        $role->create_category = in_array('create_category', $request->addRolePermissions ?? []);
        $role->edit_category = in_array('edit_category', $request->addRolePermissions ?? []);
        $role->delete_category = in_array('delete_category', $request->addRolePermissions ?? []);

        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
        ]);
    }

    public function updateRole(Request $request, $id)
    {

        $role = Role::find($id);

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found',
            ], 404);
        }

        $this->authorize('edit', Role::class);

        $validator = Validator::make($request->all(), [
            'editRoleName' => 'required|string|max:255|unique:roles,name,'.$id,
            'editRolePermissions' => 'required|nullable|array',
        ], [
            'editRoleName.required' => 'Role name is required',
            'editRoleName.string' => 'Role name must be a string',
            'editRoleName.max' => 'Role name cannot exceed 255 characters',
            'editRoleName.unique' => 'This role name already exists',
            'editRolePermissions.array' => 'Permissions must be an array',
            'editRolePermissions.required' => 'At least one permission is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $role->name = $request->editRoleName;
        $role->view_products = in_array('view_products', $request->editRolePermissions ?? []);
        $role->create_product = in_array('create_products', $request->editRolePermissions ?? []);
        $role->edit_product = in_array('edit_products', $request->editRolePermissions ?? []);
        $role->delete_product = in_array('delete_products', $request->editRolePermissions ?? []);
        $role->view_users = in_array('view_users', $request->editRolePermissions ?? []);
        $role->edit_user = in_array('edit_users', $request->editRolePermissions ?? []);
        $role->delete_user = in_array('delete_users', $request->editRolePermissions ?? []);
        $role->view_admins = in_array('view_admins', $request->editRolePermissions ?? []);
        $role->create_admin = in_array('create_admins', $request->editRolePermissions ?? []);
        $role->edit_admin = in_array('edit_admins', $request->editRolePermissions ?? []);
        $role->delete_admin = in_array('delete_admins', $request->editRolePermissions ?? []);
        $role->view_roles = in_array('view_roles', $request->editRolePermissions ?? []);
        $role->create_role = in_array('create_roles', $request->editRolePermissions ?? []);
        $role->edit_role = in_array('edit_roles', $request->editRolePermissions ?? []);
        $role->delete_role = in_array('delete_roles', $request->editRolePermissions ?? []);
        $role->view_categories = in_array('view_categories', $request->editRolePermissions ?? []);
        $role->create_category = in_array('create_category', $request->editRolePermissions ?? []);
        $role->edit_category = in_array('edit_category', $request->editRolePermissions ?? []);
        $role->delete_category = in_array('delete_category', $request->editRolePermissions ?? []);

        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
        ]);
    }
}
