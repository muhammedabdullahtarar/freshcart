let productImages = [];
let existingImageIds = [];

document.addEventListener('DOMContentLoaded', function () {
    loadProducts();
});

function loadProducts() {
    $.ajax({
        url: '/api/getProducts',
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
        success: function (response) {
            const tbody = $('#ProductsTable');
            tbody.empty();

            if (!response.products.length) {
                tbody.append('<tr><td colspan="5" class="text-center text-muted">No products found</td></tr>');
                return;
            }

            response.products.forEach(product => {
                console.log(product);

                const hasName = 'name' in product;
                const hasCategories = product.categories && product.categories.length > 0;
                const hasPrice = 'price' in product && product.price !== null;
                const hasStock = 'stock' in product && product.stock !== null;

                const nameHtml = hasName ? product.name : '<span class="blurred">********</span>';
                const categoriesHtml = hasCategories
                    ? getCategoryBadges(product.categories)
                    : '<span class="badge bg-secondary blurred">Hidden</span>';
                const priceHtml = hasPrice
                    ? `$${parseFloat(product.price).toFixed(2)}`
                    : '<span class="blurred">********</span>';
                const stockHtml = hasStock
                    ? product.stock
                    : '<span class="blurred">********</span>';

                tbody.append(`
                    <tr>
                        <td><h6 class="mb-0">${nameHtml}</h6></td>
                        <td class="${hasCategories ? '' : 'blurred'}">${categoriesHtml}</td>
                        <td class="${hasPrice ? '' : 'blurred'}">${priceHtml}</td>
                        <td class="${hasStock ? '' : 'blurred'}">${stockHtml}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="showEditProductForm(${product.id})">Edit</button>
                                <button class="btn btn-outline-danger" onclick="deleteProduct(${product.id})">Delete</button>
                                <button class="btn btn-outline-secondary" onclick="viewProduct(${product.id})"><i class="bi bi-eye"></i></button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        },
        error: function (xhr) {
            console.error('Load Products Error:', xhr.responseJSON);
            showMessage('error', 'Failed to load products');
            $('#ProductsTable tbody').html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error loading products
                    </td>
                </tr>
            `);
        }
    });
}

function renderImagePreview(images, previewElementId, isEdit = false) {
    const previewDiv = document.getElementById(previewElementId);
    previewDiv.innerHTML = '';

    if (!images.length) {
        previewDiv.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-images"></i>
                <p>No images selected</p>
            </div>`;
        return;
    }

    images.forEach((image, index) => {
        const imgContainer = document.createElement('div');
        imgContainer.classList.add('image-preview');

        if (isEdit && image.isExisting) {
            imgContainer.classList.add('existing-image');
            imgContainer.setAttribute('data-image-id', image.id);
        }

        const img = document.createElement('img');
        img.src = image instanceof File
            ? URL.createObjectURL(image)
            : `http://127.0.0.1:8000/storage/${image.path}`;

        const removeBtn = document.createElement('button');
        removeBtn.classList.add('remove-image-btn');
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = (e) => {
            e.stopPropagation();
            productImages.splice(index, 1);
            if (isEdit && image.isExisting) {
                existingImageIds = existingImageIds.filter(id => id !== image.id);
            }
            renderImagePreview(productImages, previewElementId, isEdit);
        };

        imgContainer.appendChild(img);
        imgContainer.appendChild(removeBtn);
        previewDiv.appendChild(imgContainer);
    });
}

document.getElementById('addProductImage').addEventListener('change', function (event) {
    productImages = Array.from(event.target.files);
    renderImagePreview(productImages, 'addProductImagePreview');
});

function showAddProductForm() {
    loadCategoriesForDropdown('#addProductCategory');
    $('#addProductForm')[0].reset();
    $('#addProductCategory').val(null).trigger('change');
    productImages = [];
    existingImageIds = [];
    renderImagePreview([], 'addProductImagePreview');
    $('#addProductModal').modal('show');
}

function saveProduct() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const name = $('#addProductName').val().trim();
    const categories = $('#addProductCategory').val();
    const price = $('#addProductPrice').val().trim();
    const description = $('#addProductDescription').val().trim();
    const stock = $('#addProductStock').val().trim();

    let hasErrors = false;

    if (!name) {
        $('#addProductName').addClass('is-invalid').after('<div class="invalid-feedback">Please enter product name</div>');
        hasErrors = true;
    }
    if (!description) {
        $('#addProductDescription').addClass('is-invalid').after('<div class="invalid-feedback">Please enter product description</div>');
        hasErrors = true;
    }
    if (!categories?.length) {
        $('#addProductCategory').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one category</div>');
        hasErrors = true;
    }
    if (!price || isNaN(price) || parseFloat(price) <= 0) {
        $('#addProductPrice').addClass('is-invalid').after('<div class="invalid-feedback">Enter a valid price greater than 0</div>');
        hasErrors = true;
    }
    if (!stock || isNaN(stock) || parseInt(stock) < 0) {
        $('#addProductStock').addClass('is-invalid').after('<div class="invalid-feedback">Enter a valid stock number (0 or more)</div>');
        hasErrors = true;
    }
    if (hasErrors) return;

    const formData = new FormData();
    formData.append('name', name);
    formData.append('price', price);
    formData.append('description', description);
    formData.append('stock', stock);
    categories.forEach(cat => formData.append('categories[]', cat));
    productImages.forEach(file => formData.append('images[]', file));

    $.ajax({
        url: '/api/create-update-product',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        },
        success: function (response) {
            if (response.success) {
                showMessage('success', 'Product created successfully');
                $('#addProductModal').modal('hide');
                loadProducts();
            }
        },
        error: function (xhr) {
            showMessage('error', xhr.responseJSON?.message || 'Failed to create product');
        }
    });
}

document.getElementById('editProductImage').addEventListener('change', function (event) {
    const newFiles = Array.from(event.target.files);
    productImages = [...productImages.filter(img => img instanceof File), ...newFiles];
    renderImagePreview(productImages, 'editProductImagePreview', true);
});

function showEditProductForm(productId) {
    $('#editProductForm')[0].reset();
    $('#editProductCategory').val([]).trigger('change');
    productImages = [];
    existingImageIds = [];
    $('#editProductId').val(productId);

    loadCategoriesForDropdown('#editProductCategory');
    $('#editProductModal').modal('show');

    $.ajax({
        url: `/api/getProduct/${productId}`,
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
        success: function (response) {
            if (response.product) {
                const product = response.product;
                $('#editProductName').val(product.name);
                $('#editProductPrice').val(product.price);
                $('#editProductStock').val(product.stock);
                $('#editProductDescription').val(product.description);
                $('#editProductCategory').val(product.categories.map(c => c.id)).trigger('change');

                if (product.images?.length) {
                    productImages = product.images.map(img => ({
                        id: img.id,
                        path: img.path,
                        isExisting: true
                    }));
                    existingImageIds = product.images.map(img => img.id);
                    renderImagePreview(productImages, 'editProductImagePreview', true);
                }
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                showMessage('error', "You don't have permission for getting prefilled data.");
            } else if (xhr.responseJSON?.message) {
                showMessage('error', xhr.responseJSON.message);
            } else {
                showMessage('error', 'Failed to load data');
            }
        }
    });
}

function updateProduct() {
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    const productId = $('#editProductId').val();
    const name = $('#editProductName').val().trim();
    const categories = $('#editProductCategory').val();
    const price = $('#editProductPrice').val().trim();
    const description = $('#editProductDescription').val().trim();
    const stock = $('#editProductStock').val().trim();

    let hasErrors = false;
    if (!name) {
        $('#editProductName').addClass('is-invalid').after('<div class="invalid-feedback">Please enter product name</div>');
        hasErrors = true;
    }
    if (!categories?.length) {
        $('#editProductCategory').addClass('is-invalid').after('<div class="invalid-feedback">Please select at least one category</div>');
        hasErrors = true;
    }
    if (!price || isNaN(price) || parseFloat(price) <= 0) {
        $('#editProductPrice').addClass('is-invalid').after('<div class="invalid-feedback">Enter a valid price greater than 0</div>');
        hasErrors = true;
    }
    if (!stock || isNaN(stock) || parseInt(stock) < 0) {
        $('#editProductStock').addClass('is-invalid').after('<div class="invalid-feedback">Enter a valid stock number (0 or more)</div>');
        hasErrors = true;
    }
    if (hasErrors) return;

    const formData = new FormData();
    formData.append('name', name);
    formData.append('price', price);
    formData.append('description', description);
    formData.append('stock', stock);
    categories.forEach(cat => formData.append('categories[]', cat));

    productImages.filter(img => img instanceof File)
        .forEach(file => formData.append('images[]', file));
    existingImageIds.forEach(id => formData.append('existing_images[]', id));

    $.ajax({
        url: `/api/create-update-product/${productId}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        },
        success: function (response) {
            if (response.success) {
                showMessage('success', 'Product updated successfully');
                $('#editProductModal').modal('hide');
                loadProducts();
            }
        },
        error: function (xhr) {
            showMessage('error', xhr.responseJSON?.message || 'Failed to update product');
        }
    });
}

function deleteProduct(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the product and its images.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/deleteProduct/${productId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message || 'Product deleted successfully', 'success');
                        loadProducts();
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                        showMessage('error', "You don’t have permission for deleting data.");
                    } else if (xhr.responseJSON?.message) {
                        showMessage('error', xhr.responseJSON.message);
                    } else {
                        showMessage('error', 'Failed to load data');
                    }
                }
            });
        }
    });
}

function viewProduct(productId) {
    $.ajax({
        url: `/api/getProduct/${productId}`,
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
        success: function (response) {
            if (response.product) {
                const product = response.product;
                const categoriesHtml = getCategoryBadges(product.categories);
                const price = parseFloat(product.price).toFixed(2);

                let imagesHtml = '<div class="text-center mb-3"><i class="bi bi-images fs-1 text-muted"></i><p class="text-muted">No images available</p></div>';
                if (product.images?.length) {
                    imagesHtml = `
                        <div class="row g-2 mb-3">
                            ${product.images.map(image => `
                                <div class="col-6">
                                    <div class="border rounded overflow-hidden" style="height: 150px;">
                                        <img src="http://127.0.0.1:8000/storage/${image.path}" class="w-100 h-100 object-fit-cover">
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                $('#viewProductModal .modal-body').html(`
                    <div class="row">
                        <div class="col-md-5 mb-3">${imagesHtml}</div>
                        <div class="col-md-7">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Name</dt><dd class="col-sm-8">${product.name}</dd>
                                <dt class="col-sm-4">Price</dt><dd class="col-sm-8">$${price}</dd>
                                <dt class="col-sm-4">Stock</dt><dd class="col-sm-8">${product.stock}</dd>
                                <dt class="col-sm-4">Categories</dt><dd class="col-sm-8">${categoriesHtml}</dd>
                                <dt class="col-sm-4">Description</dt><dd class="col-sm-8">${product.description || 'N/A'}</dd>
                            </dl>
                        </div>
                    </div>
                `);
                $('#viewProductModal').modal('show');
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON?.message === 'This action is unauthorized.') {
                showMessage('error', "You don’t have permission for viewing data.");
            } else if (xhr.responseJSON?.message) {
                showMessage('error', xhr.responseJSON.message);
            } else {
                showMessage('error', 'Failed to load data');
            }
        }
    });
}

function getCategoryBadges(categories) {
    return categories.map(cat => 
        `<span class="badge bg-light-primary text-dark-primary me-1">${cat.name}</span>`
    ).join('');
}

function loadCategoriesForDropdown(selector) {
    $.ajax({
        url: '/api/getCategories',
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
        success: function (response) {
            const $select = $(selector);
            $select.empty();
            response.categories.forEach(category => {
                $select.append(`<option value="${category.id}">${category.name}</option>`);
            });
            $select.select2({
                dropdownParent: $(selector).closest('.modal'),
                width: '100%'
            });
        },
        error: function () {
            showMessage('error', 'Failed to load categories');
        }
    });
}
