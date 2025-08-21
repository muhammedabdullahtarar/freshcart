@extends('admin.layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Products</h1>
                    <p class="text-muted mb-0">Manage your product catalog</p>
                </div>
                <button class="btn btn-primary" onclick="showAddProductForm()">
                    <i class="bi bi-plus"></i> Add Product
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Products</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ProductsTable">
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

   
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addProductName" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="addProductName" name="name"
                                        placeholder="Enter product name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addProductCategory" class="form-label">Categories</label>
                                    <select class="form-select select2" id="addProductCategory" name="categories[]" multiple
                                        data-placeholder="Select categories">
                                       
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="addProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="addProductPrice" name="price"
                                placeholder="Enter price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="addProductDescription" name="description" rows="3"
                                placeholder="Enter product description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="addProductStock" name="addProductStock"
                                placeholder="Enter stock" required>
                        </div>
                        <div class="mb-3" id="productImageWrapper">
                            <label for="addProductStock" class="form-label">Product Images</label>
                            <br>
                            <label for="addProductImage" class="upload-btn">
                                <i class="bi bi-cloud-arrow-up"></i> UPLOAD IMAGES
                            </label>
                            <input type="file" hidden id="addProductImage" accept="image/*" multiple>
                            <div class="form-text">Upload multiple product images (max 5)</div>
                            <div id="addProductImagePreview" class="image-preview-container">
                                <div class="empty-state">
                                    <i class="bi bi-images"></i>
                                    <p>No images selected</p>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Add Product</button>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <div class="row">
                            <input type="hidden" id="editProductId" name="editProductId">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editProductName" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="editProductName" name="name"
                                        placeholder="Enter product name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editProductCategory" class="form-label">Categories</label>
                                    <select class="form-select select2" id="editProductCategory" name="categories[]" multiple
                                        data-placeholder="Select categories">
                                      
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price"
                                placeholder="Enter price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editProductDescription" name="description" rows="3"
                                placeholder="Enter product description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editProductStock" name="editProductStock"
                                placeholder="Enter stock" required>
                        </div>
                        <div class="mb-3" id="productImageWrapper">
                            <label for="editProductStock" class="form-label">Product Images</label>
                            <br>
                            <label for="editProductImage" class="upload-btn">
                                <i class="bi bi-cloud-arrow-up"></i> UPLOAD IMAGES
                            </label>
                            <input type="file" hidden id="editProductImage" accept="image/*" multiple>
                            <div class="form-text">Upload multiple product images (max 5)</div>
                            <div id="editProductImagePreview" class="image-preview-container">
                                <div class="empty-state">
                                    <i class="bi bi-images"></i>
                                    <p>No images selected</p>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateProduct()">edit Product</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-products.js') }}"></script>

@endsection
