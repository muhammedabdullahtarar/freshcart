
document.addEventListener('DOMContentLoaded', function () {
    loadCategories();
});

function loadCategories() {
    $.ajax({
        url: '/api/getCategories',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (response) {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = '';

            if (response.categories && response.categories.length > 0) {
                response.categories.forEach(category => {

                    const hasCreatedAt = 'created_at' in category;
                    const createdAt = hasCreatedAt
                        ? new Date(category.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' })
                        : '';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${category.name}</td>
                        <td class="${hasCreatedAt ? '' : 'blurred'}">${hasCreatedAt ? createdAt : '********'}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="showEditCategoryForm(${category.id})">Edit</button>
                                <button class="btn btn-outline-danger" onclick="deleteCategory(${category.id})">Delete</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No categories found</td></tr>';
            }
        },
        error: function (xhr) {
            console.log('Load Categories API Error:', xhr.responseJSON);
            showMessage('error', 'Failed to load categories');
        }
    });
}

function showCreateCategoryForm() {

    $('#addCategoryName').val('');
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    $('#createCategoryModal').modal('show');
}

function saveCategory() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const name = $('#addCategoryName').val().trim();

    let hasErrors = false;

    if (!name) {
        $('#addCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#addCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/createCategory',
            method: 'POST',
            data: {
                name: name,
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
            },
            success: function (response) {
                if (response.success) {
                    showMessage('success', 'Category created successfully');
                    $('#createCategoryModal').modal('hide');
                    loadCategories();
                } else {
                    showMessage('error', 'Failed to create category');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    const e = xhr.responseJSON.errors;
                    if (e.name) {
                        $('#addCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">' + e.name[0] + '</div>');
                    }
                } else {
                    let msg = 'Failed to create category';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('error', msg);
                }
            }
        });
    }
}

function showEditCategoryForm(id) {

    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');


    $('#editCategoryModal').modal('show');

    $.ajax({
        url: '/api/getCategory/' + id,
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function (res) {
            console.log(res);
            $('#editCategoryId').val(res.category.id);
            $('#editCategoryName').val(res.category.name);
        },
        error: function (xhr) {
            if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                showMessage('error', "You don’t have permission for prefilled data.");
            } else if (xhr.responseJSON?.message) {
                showMessage('error', xhr.responseJSON.message);
            } else {
                showMessage('error', 'Failed to load category data');
            }
        }
    });
}

function updateCategory() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const id = $('#editCategoryId').val();
    const name = $('#editCategoryName').val().trim();

    let hasErrors = false;

    if (!name) {
        $('#editCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">Please fill this field</div>');
        hasErrors = true;
    } else if (name.length > 255) {
        $('#editCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">Name cannot exceed 255 characters</div>');
        hasErrors = true;
    }

    if (!hasErrors) {
        $.ajax({
            url: '/api/updateCategory/' + id,
            method: 'POST',
            data: {
                name: name
            },
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function (res) {
                if (res.success) {
                    showMessage('success', 'Category updated successfully');
                    $('#editCategoryModal').modal('hide');
                    loadCategories();
                } else {
                    showMessage('error', 'Failed to update category');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    const e = xhr.responseJSON.errors;
                    if (e.name) {
                        $('#editCategoryName').addClass('is-invalid').after('<div class="invalid-feedback">' + e.name[0] + '</div>');
                    }
                } else {
                    let msg = 'Failed to update category';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('error', msg);
                }
            }
        });
    }
}

function deleteCategory(id) {
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
                url: '/api/deleteCategory/' + id,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function () {
                    showMessage('success', 'Category deleted successfully');
                    loadCategories();
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message;

                    if (msg === 'This action is unauthorized.') {
                        showMessage('error', "You don’t have permission for deleting data.");
                    } else if (msg === 'Cannot delete category because it has associated products.') {
                
                        Swal.fire({
                            title: 'Category has associated products',
                            text: 'Do you want to archive it or delete category and products?',
                            icon: 'warning',
                            showCancelButton: true,
                            showDenyButton: true,
                            confirmButtonText: 'Yes, archive',
                            denyButtonText: 'No, delete category and products',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '/api/archiveCategory/' + id,
                                    method: 'POST',
                                    headers: {
                                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                                    },
                                    success: function () {
                                        showMessage('success', 'Category archived successfully');
                                        loadCategories();
                                    },
                                    error: function () {
                                        showMessage('error', 'Failed to archive category');
                                    }
                                });
                            } else if (result.isDenied) {
                         
                                $.ajax({
                                    url: '/api/deleteCategory/' + id + '?force=true',
                                    method: 'DELETE',
                                    headers: {
                                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                                    },
                                    success: function () {
                                        showMessage('success', 'Category and products deleted successfully');
                                        loadCategories();
                                    },
                                    error: function () {
                                        showMessage('error', 'Failed to delete category and products');
                                    }
                                });
                            }
                        });

                    } else if (msg) {
                        showMessage('error', msg);
                    } else {
                        showMessage('error', 'Failed to load data');
                    }
                }
            });
        }
    });
}
