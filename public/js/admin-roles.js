document.addEventListener('DOMContentLoaded', () => {
    loadRoles();
});

function loadRoles() {
    $.ajax({
        url: '/api/getRoles',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            const tbody = document.querySelector('table tbody');




            tbody.innerHTML = '';

            if (response.roles && response.roles.length > 0) {
                const fullData = Object.keys(response.roles[0]).length > 2;

                response.roles.forEach(role => {
                    const row = document.createElement('tr');
                    const permissionBadges = getPermissionBadges(role, fullData);

                    row.innerHTML = `
                        <td>${role.name}</td>
                        <td class="${fullData ? '' : 'blurred'}">${fullData ? permissionBadges : '********'}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info btn-sm" onclick="showViewRoleForm(${role.id})">View</button>
                                <button class="btn btn-outline-primary btn-sm" onclick="showEditRoleForm(${role.id})">Edit</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteRole(${role.id})">Delete</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No roles found</td></tr>';
            }
        },
        error: function (xhr) {
            console.log('Load Roles API Error:', xhr.responseJSON);
            showMessage('error', 'Failed to load roles');
        }
    });
}

function getPermissionBadges(role, fullData) {
    if (!fullData) return '<span class="badge bg-secondary">Hidden</span>';

    const permissions = [];
    if (role.view_products) permissions.push('View Products');
    if (role.create_product) permissions.push('Create Products');
    if (role.edit_product) permissions.push('Edit Products');
    if (role.delete_product) permissions.push('Delete Products');
    if (role.view_users) permissions.push('View Users');
    if (role.edit_user) permissions.push('Edit Users');
    if (role.delete_user) permissions.push('Delete Users');
    if (role.view_admins) permissions.push('View Admins');
    if (role.create_admin) permissions.push('Create Admins');
    if (role.edit_admin) permissions.push('Edit Admins');
    if (role.delete_admin) permissions.push('Delete Admins');
    if (role.view_roles) permissions.push('View Roles');
    if (role.create_role) permissions.push('Create Roles');
    if (role.edit_role) permissions.push('Edit Roles');
    if (role.delete_role) permissions.push('Delete Roles');
    if (role.view_categories) permissions.push('View Categories');
    if (role.create_category) permissions.push('Create Category');
    if (role.edit_category) permissions.push('Edit Category');
    if (role.delete_category) permissions.push('Delete Category');

    return permissions.map(badge =>
        `<span class="badge bg-light-primary text-dark-primary me-1">${badge}</span>`
    ).join('');
}

function showViewRoleForm(roleId) {
    $.ajax({
        url: `/api/getRole/${roleId}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            $('#viewRoleModal .modal-body').html('');

            if (response.role) {
                const role = response.role;
                const permissionBadges = getPermissionBadges(role, true);

                const modalBody = `
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Role Name</dt>
                        <dd class="col-sm-8">${role.name}</dd>
                        <dt class="col-sm-4">Permissions</dt>
                        <dd class="col-sm-8">${permissionBadges}</dd>
                    </dl>
                `;

                $('#viewRoleModal .modal-body').html(modalBody);
                $('#viewRoleModal').modal('show');
            }
        },
        error: function (xhr) {
            let msg = xhr.responseJSON?.message === 'This action is unauthorized.'
                ? "You don't have permission to view data."
                : xhr.responseJSON?.message || 'Failed to view role';
            showMessage('error', msg);
        }
    });
}

function showEditRoleForm(roleId) {
    $('#editRoleForm')[0].reset();
    $('#editRolePermissions').val([]).trigger('change');
    $('#editRoleId').val(roleId);

    $('#editRoleModal').modal('show');

    $('#editRolePermissions').select2({
        dropdownParent: $('#editRoleModal'),
        width: '100%'
    });

    $.ajax({
        url: `/api/getRole/${roleId}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            if (response.role) {
                const role = response.role;
                $('#editRoleName').val(role.name);

                const selectedPermissions = [];
                if (role.view_products) selectedPermissions.push('view_products');
                if (role.create_product) selectedPermissions.push('create_product');
                if (role.edit_product) selectedPermissions.push('edit_product');
                if (role.delete_product) selectedPermissions.push('delete_product');
                if (role.view_users) selectedPermissions.push('view_users');
                if (role.edit_user) selectedPermissions.push('edit_user');
                if (role.delete_user) selectedPermissions.push('delete_user');
                if (role.view_admins) selectedPermissions.push('view_admins');
                if (role.create_admin) selectedPermissions.push('create_admin');
                if (role.edit_admin) selectedPermissions.push('edit_admin');
                if (role.delete_admin) selectedPermissions.push('delete_admin');
                if (role.view_roles) selectedPermissions.push('view_roles');
                if (role.create_role) selectedPermissions.push('create_role');
                if (role.edit_role) selectedPermissions.push('edit_role');
                if (role.delete_role) selectedPermissions.push('delete_role');
                if (role.view_categories) selectedPermissions.push('view_categories');
                if (role.create_category) selectedPermissions.push('create_category');
                if (role.edit_category) selectedPermissions.push('edit_category');
                if (role.delete_category) selectedPermissions.push('delete_category');

                $('#editRolePermissions').val(selectedPermissions).trigger('change');
            }
        },
        error: function (xhr) {
            let msg = xhr.responseJSON?.message === 'This action is unauthorized.'
                ? "You don't have permission to get prefill data."
                : xhr.responseJSON?.message || 'Failed to load role data';
            showMessage('error', msg);
        }
    });
}

function updateRole() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const roleId = $('#editRoleId').val().trim();
    const name = $('#editRoleName').val().trim();
    const permissions = $('#editRolePermissions').val();

    let hasErrors = false;

    if (!name) {
        $('#editRoleName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#editRoleName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!permissions || permissions.length === 0) {
        $('#editRolePermissions').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one permission</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/updateRole/' + roleId,
            method: 'POST',
            data: {
                editRoleName: name,
                editRolePermissions: permissions
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),

            },
            success: function (response) {
                if (response.success) {
                    showMessage('success', 'Role updated successfully');
                    $('#editRoleModal').modal('hide');
                    loadRoles();
                } else {
                    showMessage('error', 'Failed to update role');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.editRoleName) {
                        $('#editRoleName').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editRoleName[0] + '</div>');
                    }
                    if (errors.editRolePermissions) {
                        $('#editRolePermissions').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editRolePermissions[0] + '</div>');
                    }
                } else {
                    let msg = xhr.responseJSON?.message === 'This action is unauthorized.'
                        ? "You don't have permission to update."
                        : xhr.responseJSON?.message || 'Failed to update role';
                    showMessage('error', msg);
                }
            }
        });
    }
}

function deleteRole(roleId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/deleteRole/${roleId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),

                },
                success: function () {
                    showMessage('success', 'Role deleted successfully');
                    loadRoles();
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message === 'This action is unauthorized.'
                        ? "You don't have permission to delete."
                        : xhr.responseJSON?.message || 'Failed to delete role';
                    showMessage('error', msg);
                }
            });
        }
    });
}

function showAddRoleForm() {
    $('#addRoleForm')[0].reset();
    $('#addRolePermissions').val(null).trigger('change');
    $('#addRoleModal').modal('show');

    $('#addRolePermissions').select2({
        dropdownParent: $('#addRoleModal'),
        width: '100%'
    });
}

function saveRole() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const name = $('#addRoleName').val().trim();
    const permissions = $('#addRolePermissions').val();

    let hasErrors = false;

    if (!name) {
        $('#addRoleName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#addRoleName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!permissions || permissions.length === 0) {
        $('#addRolePermissions').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one permission</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/createRole',
            method: 'POST',
            data: {
                addRoleName: name,
                addRolePermissions: permissions
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
            },
            success: function (response) {
                if (response.success) {
                    showMessage('success', 'Role created successfully');
                    $('#addRoleModal').modal('hide');
                    loadRoles();
                } else {
                    showMessage('error', 'Failed to create role');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.addRoleName) {
                        $('#addRoleName').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addRoleName[0] + '</div>');
                    }
                    if (errors.addRolePermissions) {
                        $('#addRolePermissions').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addRolePermissions[0] + '</div>');
                    }
                } else {
                    let msg = xhr.responseJSON?.message === 'This action is unauthorized.'
                        ? "You don't have permission to create."
                        : xhr.responseJSON?.message || 'Failed to create role';
                    showMessage('error', msg);
                }
            }
        });
    }
}