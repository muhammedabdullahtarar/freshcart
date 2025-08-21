@extends('admin.layouts.admin')

@section('title', 'Admins')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Admins</h1>
                <p class="text-muted mb-0">Manage admin accounts</p>
            </div>
            <button class="btn btn-primary" onclick="showAddAdminForm()">
                <i class="bi bi-plus"></i> Add Admin
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Admins</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
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


<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAdminForm">
                    <div class="mb-3">
                        <label for="addAdminName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="addAdminName" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addAdminEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="addAdminEmail" name="email" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-3">
                        <label for="addAdminPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="addAdminPassword" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="mb-3">
                        <label for="addAdminRoles" class="form-label">Roles</label>
                        <select class="form-select select2" id="addAdminRoles" name="roles[]" multiple data-placeholder="Select roles">
                            
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAdmin()">Add Admin</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Admin Form -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm">
                    <input type="hidden" id="editAdminId" name="admin_id">
                    <div class="mb-3">
                        <label for="editAdminName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editAdminName" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAdminEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editAdminEmail" name="email" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAdminPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="editAdminPassword" name="password" placeholder="Leave blank to keep current password">
                        <div class="form-text">Leave blank to keep the current password</div>
                    </div>
                    <div class="mb-3">
                        <label for="editAdminRoles" class="form-label">Roles</label>
                        <select class="form-select select2" id="editAdminRoles" name="roles[]" multiple data-placeholder="Select roles">
                            
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateAdmin()">Update Admin</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">Admin Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Full Name</dt>
                    <dd class="col-sm-8">Jane Admin</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">jane@admin.com</dd>
                    <dt class="col-sm-4">Roles</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-primary">Manager</span>
                        <span class="badge bg-primary">Editor</span>
                    </dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-admins.js') }}"></script>
@endsection