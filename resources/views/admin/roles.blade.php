@extends('admin.layouts.admin')

@section('title', 'Roles')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Roles</h1>
                    <p class="text-muted mb-0">Manage user roles and permissions</p>
                </div>
                <button class="btn btn-primary" onclick="showAddRoleForm()">
                    <i class="bi bi-plus"></i> Add Role
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Roles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoleForm">
                        <div class="mb-3">
                            <label for="addRoleName" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="addRoleName" name="name"
                                placeholder="Enter role name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addRolePermissions" class="form-label">Permissions</label>
                            <select class="form-select select2" id="addRolePermissions" name="permissions[]" multiple
                                data-placeholder="Select permissions">
                                <optgroup label="Product Management">
                                    <option value="view_products">View Products</option>
                                    <option value="create_products">Create Products</option>
                                    <option value="edit_products">Edit Products</option>
                                    <option value="delete_products">Delete Products</option>
                                </optgroup>
                                <optgroup label="User Management">
                                    <option value="view_users">View Users</option>
                                    <option value="edit_users">Edit Users</option>
                                    <option value="delete_users">Delete Users</option>
                                </optgroup>
                                <optgroup label="Admin Management">
                                    <option value="view_admins">View Admins</option>
                                    <option value="create_admins">Create Admins</option>
                                    <option value="edit_admins">Edit Admins</option>
                                    <option value="delete_admins">Delete Admins</option>
                                </optgroup>
                                <optgroup label="Role Management">
                                    <option value="view_roles">View Roles</option>
                                    <option value="create_roles">Create Roles</option>
                                    <option value="edit_roles">Edit Roles</option>
                                    <option value="delete_roles">Delete Roles</option>
                                </optgroup>
                                <optgroup label="Category Management">
                                    <option value="view_categories">View Categories</option>
                                    <option value="create_category">Create Category</option>
                                    <option value="edit_category">Edit Category</option>
                                    <option value="delete_category">Delete Category</option>
                                </optgroup>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRole()">Add Role</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Form -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoleForm">
                        <input type="hidden" id="editRoleId" name="role_id">
                        <div class="mb-3">
                            <label for="editRoleName" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="editRoleName" name="name"
                                placeholder="Enter role name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRolePermissions" class="form-label">Permissions</label>
                            <select class="form-select select2" id="editRolePermissions" name="permissions[]" multiple
                                data-placeholder="Select permissions">
                                <optgroup label="Product Management">
                                    <option value="view_products">View Products</option>
                                    <option value="create_products">Create Products</option>
                                    <option value="edit_products">Edit Products</option>
                                    <option value="delete_products">Delete Products</option>
                                </optgroup>
                                <optgroup label="User Management">
                                    <option value="view_users">View Users</option>
                                    <option value="edit_users">Edit Users</option>
                                    <option value="delete_users">Delete Users</option>
                                </optgroup>
                                <optgroup label="Admin Management">
                                    <option value="view_admins">View Admins</option>
                                    <option value="create_admins">Create Admins</option>
                                    <option value="edit_admins">Edit Admins</option>
                                    <option value="delete_admins">Delete Admins</option>
                                </optgroup>
                                <optgroup label="Role Management">
                                    <option value="view_roles">View Roles</option>
                                    <option value="create_roles">Create Roles</option>
                                    <option value="edit_roles">Edit Roles</option>
                                    <option value="delete_roles">Delete Roles</option>
                                </optgroup>
                                <optgroup label="Category Management">
                                    <option value="view_categories">View Categories</option>
                                    <option value="create_category">Create Category</option>
                                    <option value="edit_category">Edit Category</option>
                                    <option value="delete_category">Delete Category</option>
                                </optgroup>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateRole()">Update Role</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewRoleModal" tabindex="-1" aria-labelledby="viewRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRoleModalLabel">Role Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Role Name</dt>
                        <dd class="col-sm-8">Manager</dd>
                        <dt class="col-sm-4">Permissions</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-light-primary text-dark-primary">View Products</span>
                            <span class="badge bg-light-primary text-dark-primary">Edit Products</span>
                            <span class="badge bg-light-primary text-dark-primary">View Users</span>
                        </dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-light-primary {
            background-color: #e3f2fd !important;
        }

        .text-dark-primary {
            color: #1976d2 !important;
        }
    </style>

    <script src="{{ asset('js/admin-roles.js') }}"></script>
@endsection
