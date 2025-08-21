document.addEventListener('DOMContentLoaded', function () {
    loadUsers();
});

function loadUsers() {
    $.ajax({
        url: '/api/getUsers',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (res) {
            const tbody = $('table tbody');
            tbody.html('');

            const usersExist = res.users && res.users.length > 0;
            if (!usersExist) {
                tbody.html('<tr><td colspan="5" class="text-center text-muted">No users found</td></tr>');
                return;
            }

            const fullData = Object.keys(res.users[0]).length > 2;

            res.users.forEach(function (user) {
                const createdAt = (fullData && user.created_at)
                    ? new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' })
                    : '********';

                const verified = user.email_verified_at != null;

                let statusBadge;
                if (fullData) {
                    statusBadge = `<span class="badge bg-light-${verified ? 'success' : 'secondary'} text-dark-${verified ? 'success' : 'secondary'} me-1">${verified ? 'Verified' : 'Unverified'}</span>`;
                } else {
                    statusBadge = '<span class="badge bg-secondary">Hidden</span>';
                }

                tbody.append(`
                    <tr>
                        <td>${user.name}</td>
                        <td class="${fullData ? '' : 'blurred'}">${fullData ? user.email : '********'}</td>
                        <td class="${fullData ? '' : 'blurred'}">${createdAt}</td>
                        <td class="${fullData ? '' : 'blurred'}">${statusBadge}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="showViewUserForm(${user.id})">View</button>
                                <button class="btn btn-outline-primary" onclick="showEditUserForm(${user.id})">Edit</button>
                                <button class="btn btn-outline-danger" onclick="deleteUser(${user.id})">Delete</button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        },
        error: function () {
            showMessage('error', 'Failed to load users');
        }
    });
}

function showViewUserForm(id) {
    $.ajax({
        url: `/api/getUser/${id}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (res) {
            $('#viewUserName').text(res.user.name);
            $('#viewUserEmail').text(res.user.email);

            const formattedDate = new Date(res.user.created_at)
                .toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
            $('#viewUserJoined').text(formattedDate);

            $('#viewUserStatus').text(res.user.email_verified_at ? 'Verified' : 'Unverified');

            $('#viewUserModal').modal('show');
        },
        error: function (xhr) {
            let msg = 'Failed to view user';
            if (xhr.responseJSON.message) {
                if (xhr.responseJSON.message === 'This action is unauthorized.') {
                    msg = "You don’t have permission to view data.";
                } else {
                    msg = xhr.responseJSON.message;
                }
            }
            showMessage('error', msg);
        }
    });
}

function showEditUserForm(id) {
    $('#editUserModal').modal('show');
    $('#editUserForm')[0].reset();
    $('#editUserId').val(id);

    $.ajax({
        url: `/api/getUser/${id}`,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (res) {
            if (res.user) {
                $('#editUserName').val(res.user.name);
                $('#editUserEmail').val(res.user.email);
                $('#editUserStatus').val(res.user.email_verified_at ? 'verified' : 'unverified');
            }
        },
        error: function (xhr) {
            let msg = "You don’t have permission to get prefilled data.";
            if (xhr.responseJSON.message) {
                if (xhr.responseJSON.message !== 'This action is unauthorized.') {
                    msg = xhr.responseJSON.message;
                }
            }
            showMessage('error', msg);
        }
    });
}

function updateUser() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const id = $('#editUserId').val().trim();
    const name = $('#editUserName').val().trim();
    const email = $('#editUserEmail').val().trim();
    const password = $('#editUserPassword').val().trim();
    const status = $('#editUserStatus').val();

    let hasErrors = false;

    if (!name) {
        $('#editUserName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#editUserName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!email) {
        $('#editUserEmail').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        $('#editUserEmail').addClass('is-invalid').after('<div class="invalid-feedback">Invalid email address</div>');
        hasErrors = true;
    }

    if (password && password.length < 8) {
        $('#editUserPassword').addClass('is-invalid').after('<div class="invalid-feedback">Password must be at least 8 characters</div>');
        hasErrors = true;
    }

    if (!status) {
        $('#editUserStatus').addClass('is-invalid').after('<div class="invalid-feedback">Please select a status</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/updateUser/' + id,
            method: 'POST',
            data: {
                editUserName: name,
                editUserEmail: email,
                editUserPassword: password,
                editUserStatus: status
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            },
            success: function (res) {
                if (res.success) {
                    showMessage('success', 'User updated successfully');
                    $('#editUserModal').modal('hide');
                    loadUsers();
                } else {
                    showMessage('error', 'Failed to update user');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    const e = xhr.responseJSON.errors;
                    if (e.editUserName) {
                        $('#editUserName').addClass('is-invalid').after('<div class="invalid-feedback">' + e.editUserName[0] + '</div>');
                    }
                    if (e.editUserEmail) {
                        $('#editUserEmail').addClass('is-invalid').after('<div class="invalid-feedback">' + e.editUserEmail[0] + '</div>');
                    }
                    if (e.editUserPassword) {
                        $('#editUserPassword').addClass('is-invalid').after('<div class="invalid-feedback">' + e.editUserPassword[0] + '</div>');
                    }
                    if (e.editUserStatus) {
                        $('#editUserStatus').addClass('is-invalid').after('<div class="invalid-feedback">' + e.editUserStatus[0] + '</div>');
                    }
                } else {
                    let msg = 'Failed to update user';
                    if (xhr.responseJSON.message) {
                        if (xhr.responseJSON.message === 'This action is unauthorized.') {
                            msg = "You don’t have permission to update this data.";
                        } else {
                            msg = xhr.responseJSON.message;
                        }
                    }
                    showMessage('error', msg);
                }
            }
        });
    }
}

function deleteUser(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function (res) {
        if (res.isConfirmed) {
            $.ajax({
                url: `/api/deleteUser/${id}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                },
                success: function () {
                    showMessage('success', 'User deleted successfully');
                    loadUsers();
                },
                error: function (xhr) {
                    let msg = 'Failed to delete user';
                    if (xhr.responseJSON.message) {
                        if (xhr.responseJSON.message === 'This action is unauthorized.') {
                            msg = "You don’t have permission to delete this data.";
                        } else {
                            msg = xhr.responseJSON.message;
                        }
                    }
                    showMessage('error', msg);
                }
            });
        }
    });
}
