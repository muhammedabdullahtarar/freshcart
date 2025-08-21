@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')


    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Total Users</h5>
                            <h3 class="mt-3 mb-3" id="totalUsers"></h3>


                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-people font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Total Products</h5>
                            <h3 class="mt-3 mb-3" id="totalProducts">1,254</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2">
                                    <i class="mdi mdi-arrow-down-bold"></i> 1.08%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-cart font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Total Admins</h5>
                            <h3 class="mt-3 mb-3" id="totalAdmins"></h3>


                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-person-badge font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Growth">Total Roles</h5>
                            <h3 class="mt-3 mb-3" id="totalRoles"></h3>

                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-shield-lock font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Growth">Total Categories</h5>
                            <h3 class="mt-3 mb-3" id="totalCategories"></h3>

                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-cart font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Growth">Total Products</h5>
                            <h3 class="mt-3 mb-3" id="totalProducts"></h3>

                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="bi bi-cart font-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/dashboardData',
                method: 'GET',

                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Dashboard Data:', response);

                    $('#totalUsers').text(response.data.totalUsers);
                    $('#totalAdmins').text(response.data.totalAdmins);
                    $('#totalRoles').text(response.data.totalRoles);
                    $('#totalProducts').text(response.data.totalProducts);
                    $('#totalCategories').text(response.data.totalCategories);
                },
                error: function(xhr) {
                    console.log('API Error:', xhr.responseJSON);
                }
            });
        });
    </script>
@endsection


@endsection
