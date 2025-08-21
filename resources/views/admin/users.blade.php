@extends('admin.layouts.admin')

@section('title', 'Users')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Users</h1>
                <p class="text-muted mb-0">Manage user accounts</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
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

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editUserName" name="editUserName" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editUserEmail" name="editUserEmail" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="editUserPassword" name="editUserPassword"   >
                    </div>
                    <div class="mb-3">
                        <label for="editUserStatus" class="form-label">Email Verification Status</label>
                        <select class="form-select select2" id="editUserStatus" name="editUserStatus" data-placeholder="Select status">
                            <option value="verified">Verified</option>
                            <option value="unverified">Unverified</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update User</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Full Name</dt>
                    <dd class="col-sm-8" id="viewUserName"></dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8" id="viewUserEmail"></dd>
                    <dt class="col-sm-4">Joined</dt>
                    <dd class="col-sm-8" id="viewUserJoined"></dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8" ><span class="badge bg-light-secondary text-dark-secondary me-1" id="viewUserStatus"></span></dd>  
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-users.js') }}"></script>
@endsection