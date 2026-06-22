<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Admin - Busa Cileungsi</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('Frontend/landingPage_TokoKasur/img/logo_buscil.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('Frontend/dashboard/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Frontend/dashboard/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('Frontend/dashboard/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Frontend/dashboard/css/style.css') }}" rel="stylesheet">

    @livewireStyles
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>Busa Cileungsi</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('Frontend/dashboard/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.categories') }}" class="nav-item nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                        <i class="fa fa-tags me-2"></i>Kategori
                    </a>
                    <a href="{{ route('admin.brands') }}" class="nav-item nav-link {{ request()->routeIs('admin.brands') ? 'active' : '' }}">
                        <i class="fa fa-trademark me-2"></i>Merek
                    </a>
                    <a href="{{ route('admin.foam-types') }}" class="nav-item nav-link {{ request()->routeIs('admin.foam-types') ? 'active' : '' }}">
                        <i class="fa fa-cube me-2"></i>Jenis Busa
                    </a>
                    <a href="{{ route('admin.sizes') }}" class="nav-item nav-link {{ request()->routeIs('admin.sizes') ? 'active' : '' }}">
                        <i class="fa fa-ruler me-2"></i>Ukuran
                    </a>
                    <a href="{{ route('admin.products') }}" class="nav-item nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                        <i class="fa fa-box me-2"></i>Produk
                    </a>
                    <a href="{{ route('admin.imageDashboard') }}" class="nav-item nav-link {{ request()->routeIs('admin.imageDashboard') ? 'active' : '' }}">
                        <i class="fa fa-images me-2"></i>Image Produk
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-item nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i>User
                    </a>
                    <a href="{{ route('admin.usersAddress') }}" class="nav-item nav-link {{ request()->routeIs('admin.usersAddress') ? 'active' : '' }}">
                        <i class="fa fa-address-book me-2"></i>Alamat User
                    </a>
                    <a href="{{ route('admin.orders') }}" class="nav-item nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                        <i class="fa fa-shopping-cart me-2"></i>Order
                    </a>
                    <a href="{{ route('admin.orderItems') }}" class="nav-item nav-link {{ request()->routeIs('admin.orderItems') ? 'active' : '' }}">
                        <i class="fa fa-list me-2"></i>Order Items
                    </a>
                    <a href="{{ route('admin.payments') }}" class="nav-item nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                        <i class="fa fa-credit-card me-2"></i>Pembayaran
                    </a>
                    <a href="{{ route('admin.reviews') }}" class="nav-item nav-link {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
                        <i class="fa fa-star me-2"></i>Review
                    </a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-cog me-2"></i>Lainnya
                        </a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="{{ route('landingpage') }}" class="dropdown-item">
                                <i class="fa fa-home me-2"></i>Landing Page
                            </a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa fa-user-circle me-2"></i>Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item" style="background: transparent; border: none; color: var(--light);">
                                    <i class="fa fa-sign-out-alt me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('Frontend/dashboard/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa fa-user-circle me-2"></i>Profile
                            </a>
                            <a href="{{ route('landingpage') }}" class="dropdown-item">
                                <i class="fa fa-home me-2"></i>Landing Page
                            </a>
                            <hr class="dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="background: transparent; border: none;">
                                    <i class="fa fa-sign-out-alt me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid pt-4 px-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 text-center">
                            &copy; {{ date('Y') }} KasurBusaCileungsi. Hak Cipta Dilindungi.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Frontend/dashboard/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('Frontend/dashboard/js/main.js') }}"></script>

    @livewireScripts
</body>
</html>
