@extends('admin.layouts.admin')

@section('title', 'category')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">category</h1>
                <p class="text-muted mb-0">Manage categories</p>
                
            </div>
            <button class="btn btn-primary" onclick="showCreateCategoryForm()">
                    <i class="bi bi-plus"></i> Add Category
                </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Category</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table  class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Created At</th>
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

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId" name="Category_id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" placeholder="Enter full name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateCategory()">Update Category</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCategoryForm">
                    <input type="hidden" id="createCategoryId" name="Category_id">
                    <div class="mb-3">
                        <label for="createCategoryName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="addCategoryName" name="addCategoryName" placeholder="Enter full name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">Create Category</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/admin-categories.js') }}"></script>
@endsection