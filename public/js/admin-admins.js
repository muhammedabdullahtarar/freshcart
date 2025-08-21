document.addEventListener('DOMContentLoaded', () => {
    loadAdmins();
});

function loadAdmins() {
    $.ajax({
        url: '/api/getAdmins',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = '';

            if (response.admins && response.admins.length > 0) {
                const fullData = Object.keys(response.admins[0]).length > 3;

                response.admins.forEach(admin => {
                    const row = document.createElement('tr');
                    const roleBadges = admin.roles ? admin.roles.map(role =>
                        `<span class="badge bg-primary me-1">${role.name}</span>`
                    ).join('') : '';

                    row.innerHTML = `
                        <td>${admin.name}</td>
                        <td class="${fullData ? '' : 'blurred'}">${fullData ? admin.email : '********'}</td>
                        <td class="${fullData ? '' : 'blurred'}">${fullData ? roleBadges : '<span class="badge bg-secondary">Hidden</span>'}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info btn-sm" onclick="showViewAdminForm(${admin.id})">View</button>
                                <button class="btn btn-outline-primary btn-sm" onclick="showEditAdminForm(${admin.id})">Edit</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteAdmin(${admin.id})">Delete</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No admins found</td></tr>';
            }
        },
        error: function (xhr) {
            console.log('Load Admins API Error:', xhr.responseJSON);
            showMessage('error', 'Failed to load admins');
        }
    });
}

function showViewAdminForm(adminId) {
    $.ajax({
        url: `/api/getAdmin/${adminId}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            $('#viewAdminModal .modal-body').html('');

            if (response.admin) {
                const admin = response.admin;
                let roleHtml = '';

                if (admin.roles && admin.roles.length > 0) {
                    admin.roles.forEach(role => {
                        roleHtml += `<span class="badge bg-primary me-1">${role.name}</span>`;
                    });
                } else {
                    roleHtml = '<span class="text-muted">No roles assigned</span>';
                }

                const modalBody = `
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Full Name</dt>
                        <dd class="col-sm-8">${admin.name}</dd>
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">${admin.email}</dd>
                        <dt class="col-sm-4">Roles</dt>
                        <dd class="col-sm-8">${roleHtml}</dd>
                    </dl>
                `;

                $('#viewAdminModal .modal-body').html(modalBody);
                $('#viewAdminModal').modal('show');
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                showMessage('error', "You don’t have permission to view this data.");
            } else if (xhr.responseJSON?.message) {
                showMessage('error', xhr.responseJSON.message);
            } else {
                showMessage('error', 'Failed to view admin');
            }
        }
    });
}

function showEditAdminForm(adminId) {
    $('#editAdminForm')[0].reset();
    $('#editAdminRoles').val([]).trigger('change');
    $('#editAdminId').val(adminId);

    $('#editAdminModal').modal('show');

    loadRolesForDropdown('#editAdminRoles');

    $('#editAdminRoles').select2({
        dropdownParent: $('#editAdminModal'),
        width: '100%'
    });

    $.ajax({
        url: `/api/getAdmin/${adminId}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            if (response.admin) {
                const admin = response.admin;
                $('#editAdminName').val(admin.name);
                $('#editAdminEmail').val(admin.email);

                if (admin.roles?.length > 0) {
                    const selectedRoles = admin.roles.map(role => role.id);
                    $('#editAdminRoles').val(selectedRoles).trigger('change');
                }
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                showMessage('error', "You don’t have permission for prefilled data.");
            } else if (xhr.responseJSON?.message) {
                showMessage('error', xhr.responseJSON.message);
            } else {
                showMessage('error', 'Failed to load admin data');
            }
        }
    });
}

function updateAdmin() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const adminId = $('#editAdminId').val().trim();
    const name = $('#editAdminName').val().trim();
    const email = $('#editAdminEmail').val().trim();
    const password = $('#editAdminPassword').val().trim();
    const roles = $('#editAdminRoles').val();

    let hasErrors = false;

    if (!name) {
        $('#editAdminName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#editAdminName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!email) {
        $('#editAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        $('#editAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">Invalid email address</div>');
        hasErrors = true;
    }

    if (password && password.length < 8) {
        $('#editAdminPassword').addClass('is-invalid').after('<div class="invalid-feedback">Password must be at least 8 characters</div>');
        hasErrors = true;
    }

    if (!roles || roles.length === 0) {
        $('#editAdminRoles').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one role</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/updateAdmin/' + adminId,
            method: 'POST',
            data: {
                editAdminName: name,
                editAdminEmail: email,
                editAdminPassword: password,
                editAdminRoles: roles
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            success: function (response) {
                if (response.success) {
                    showMessage('success', 'Admin updated successfully');
                    $('#editAdminModal').modal('hide');
                    loadAdmins();
                } else {
                    showMessage('error', 'Failed to update admin');
                }
            },
            error: function (xhr) {
                console.log('Update Admin API Error:', xhr.responseJSON);

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;

                    if (errors.editAdminName) {
                        $('#editAdminName').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editAdminName[0] + '</div>');
                    }

                    if (errors.editAdminEmail) {
                        $('#editAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editAdminEmail[0] + '</div>');
                    }

                    if (errors.editAdminPassword) {
                        $('#editAdminPassword').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editAdminPassword[0] + '</div>');
                    }

                    if (errors.editAdminRoles) {
                        $('#editAdminRoles').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.editAdminRoles[0] + '</div>');
                    }
                } else {
                    let msg = 'Failed to update admin';
                    if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                        msg = "You don’t have permission to update this data.";
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('error', msg);
                }
            }
        });
    }
}

function deleteAdmin(adminId) {
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
                url: `/api/deleteAdmin/${adminId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                },
                success: function () {
                    showMessage('success', 'Admin deleted successfully');
                    loadAdmins();
                },
                error: function (xhr) {
                    let msg = 'Failed to delete admin';
                    if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                        msg = "You don’t have permission to delete this data.";
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('error', msg);
                }
            });
        }
    });
}

function showAddAdminForm() {
    $('#addAdminForm')[0].reset();
    $('#addAdminModal').modal('show');

    loadRolesForDropdown('#addAdminRoles');

    $('#addAdminRoles').select2({
        dropdownParent: $('#addAdminModal'),
        width: '100%'
    });

    $('#addAdminRoles').val(null).trigger('change');
}

function saveAdmin() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const name = $('#addAdminName').val().trim();
    const email = $('#addAdminEmail').val().trim();
    const password = $('#addAdminPassword').val().trim();
    const roles = $('#addAdminRoles').val();

    let hasErrors = false;

    if (!name) {
        $('#addAdminName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#addAdminName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!email) {
        $('#addAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        $('#addAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">Invalid email address</div>');
        hasErrors = true;
    }

    if (!password) {
        $('#addAdminPassword').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (password.length < 8) {
        $('#addAdminPassword').addClass('is-invalid').after('<div class="invalid-feedback">Password must be at least 8 characters</div>');
        hasErrors = true;
    }

    if (!roles || roles.length === 0) {
        $('#addAdminRoles').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one role</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/createAdmin',
            method: 'POST',
            data: {
                addAdminName: name,
                addAdminEmail: email,
                addAdminPassword: password,
                addAdminRoles: roles
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            success: function (response) {
                if (response.success) {
                    showMessage('success', 'Admin created successfully');
                    $('#addAdminModal').modal('hide');
                    loadAdmins();
                } else {
                    showMessage('error', 'Failed to create admin');
                }
            },
            error: function (xhr) {
                console.log('Create Admin API Error:', xhr.responseJSON);

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;

                    if (errors.addAdminName) {
                        $('#addAdminName').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addAdminName[0] + '</div>');
                    }

                    if (errors.addAdminEmail) {
                        $('#addAdminEmail').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addAdminEmail[0] + '</div>');
                    }

                    if (errors.addAdminPassword) {
                        $('#addAdminPassword').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addAdminPassword[0] + '</div>');
                    }

                    if (errors.addAdminRoles) {
                        $('#addAdminRoles').addClass('is-invalid').after('<div class="invalid-feedback">' + errors.addAdminRoles[0] + '</div>');
                    }
                } else {
                    let msg = 'Failed to create admin';
                    if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                        msg = "You don’t have permission to create.";
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('error', msg);
                }
            }
        });
    }
}

function loadRolesForDropdown(selector) {
    $.ajax({
        url: '/api/getRoles',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            if (response.roles && response.roles.length > 0) {
                const $select = $(selector);
                $select.empty();

                response.roles.forEach(role => {
                    $select.append(`<option value="${role.id}">${role.name}</option>`);
                });
            } else {
                console.log('No roles available for dropdown');
            }
        },
        error: function (xhr) {
            console.error('Failed to load roles for dropdown:', xhr.responseJSON);
            showMessage('error', 'Failed to load roles');
        }
    });
}
