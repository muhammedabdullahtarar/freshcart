<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Codescandy" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - FreshCart Admin</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/feather-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/message-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .blurred {
        filter: blur(3px) grayscale(1);
        user-select: none;
        pointer-events: none;
    }

    /* Green themed button */
    .upload-btn {
        display: inline-block;
        background: #28a745;
        color: #fff;
        padding: 10px 18px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        transition: background 0.2s, transform 0.2s;
    }

    .upload-btn:hover {
        background: #218838;
        transform: translateY(-1px);
    }

    .upload-btn:active {
        transform: translateY(1px);
    }

    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 10px;
        min-height: 150px;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
    }

    .image-preview {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .image-preview:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .remove-image-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 25px;
        height: 25px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .image-preview:hover .remove-image-btn {
        opacity: 1;
    }

    .empty-state {
        width: 100%;
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 10px;
        color: #dee2e6;
    }
    </style>

</head>

<body>
    <div id="messagePopup" class="message-popup">
        <span id="messageText"></span>
    </div>

    <!-- Loader Overlay -->
    <div id="loaderOverlay" class="loader-overlay">
        <div class="loader-spinner"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="d-flex align-items-center">
                    <a class="text-inherit d-block d-xl-none me-4" data-bs-toggle="offcanvas" href="#offcanvasExample"
                        role="button" aria-controls="offcanvasExample">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-text-indent-right" viewBox="0 0 16 16">
                            <path
                                d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z">
                            </path>
                        </svg>
                    </a>


                </div>
                <div class="d-flex align-items-center">
                    <td class="blurred"><span id="userType" class="badge bg-primary me-1">Admin Role</span></td>

                    <div class="lh-1 px-3 py-2">
                        <h5 class="mb-1 h6" id="userName">FreshCart Admin</h5>
                        <small id="userEmail">admin@freshcart.com</small>
                    </div>
                    <div class="ms-3">
                        <button onclick="logout()" class="btn btn-outline-secondary btn-sm">Log Out</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">

        <nav class="navbar-vertical-nav d-none d-xl-block">
            <div class="navbar-vertical">
                <div class="px-4 py-5">
                    <a href="{{ url('/') }}" class="navbar-brand">
                        <img src="{{ asset('images/freshcart-logo.svg') }}" alt="">
                    </a>
                </div>
                <div class="navbar-vertical-content flex-grow-1" data-simplebar>
                    <ul class="navbar-nav flex-column" id="sideNavbar">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                                    <span class="nav-link-text">Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item mt-6 mb-3">
                            <span class="nav-label">Admin Management</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-cart"></i></span>
                                    <span class="nav-link-text">Products</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.categories') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-boxes"></i></span>
                                    <span class="nav-link-text">Categories</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-people"></i></span>
                                    <span class="nav-link-text">Users</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.admins') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-person-badge"></i></span>
                                    <span class="nav-link-text">Admins</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.roles') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-shield-lock"></i></span>
                                    <span class="nav-link-text">Roles</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <nav class="navbar-vertical-nav offcanvas offcanvas-start navbar-offcanvac" tabindex="-1"
            id="offcanvasExample">
            <div class="navbar-vertical">
                <div class="px-4 py-5 d-flex justify-content-between align-items-center">
                    <a href="{{ url('/') }}" class="navbar-brand">
                        <img src="{{ asset('images/freshcart-logo.svg') }}" alt="">
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="navbar-vertical-content flex-grow-1" data-simplebar>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item mt-6 mb-3">
                            <span class="nav-label">Admin Management</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-cart"></i></span>
                                    <span class="nav-link-text">Products</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-people"></i></span>
                                    <span class="nav-link-text">Users</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.admins') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-person-badge"></i></span>
                                    <span class="nav-link-text">Admins</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.roles') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><i class="bi bi-shield-lock"></i></span>
                                    <span class="nav-link-text">Roles</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <main class="main-content-wrapper">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/theme.min.js') }}"></script>
    <script src="{{ asset('js/ui-components.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/auth-check.js') }}"></script>
    <script>
        checkAuth();

        window.adminRoutes = {
            signin: "{{ route('signin') }}",
            adminDashboard: "{{ route('admin.dashboard') }}",
            adminUsers: "{{ route('admin.users') }}",
            adminAdmins: "{{ route('admin.admins') }}",
            adminRoles: "{{ route('admin.roles') }}"
        };
    </script>
    <script src="{{ asset('js/admin-layout.js') }}"></script>

    @yield('scripts')
</body>

</html>
