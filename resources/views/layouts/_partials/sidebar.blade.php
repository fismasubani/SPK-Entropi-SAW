<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Logo Sekolah -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo smp 8.png') }}" alt="Logo Sekolah" style="width: 75px; height: auto;">
        </div>
    </a>

    <!-- Sidebar - Title -->
    <div class="sidebar-brand d-flex flex-column align-items-center justify-content-center py-2 px-2 sidebar-title">
        <a href="{{ route('dashboard') }}">
            <div class="text-center">
                <h6 class="text-white mb-1 font-weight-bold title-main" style="font-size: 0.95rem;">
                    SPK Entropi-SAW
                </h6>
                <span class="text-white-50 title-sub" style="font-size: 0.8rem;">
                    SMP Negeri 8 Pasuruan
                </span>
            </div>
        </a>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Navigasi
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Kriteria -->
    <li class="nav-item {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kriteria.index') }}">
            <i class="fas fa-fw fa-balance-scale"></i>
            <span>Data Kriteria</span>
        </a>
    </li>

    <!-- Nav Item - Alternatif -->
    <li class="nav-item {{ request()->routeIs('alternatif.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('alternatif.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Alternatif</span>
        </a>
    </li>

    <!-- Nav Item - Penilaian -->
    <li class="nav-item {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('penilaian.index') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>Penilaian Alternatif</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Perhitungan
    </div>

    <!-- Nav Item - Entropi -->
    <li class="nav-item {{ request()->routeIs('admin.perhitungan.entropi') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.perhitungan.entropi') }}">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Metode Entropi</span>
        </a>
    </li>

    <!-- Nav Item - SAW -->
    <li class="nav-item {{ request()->routeIs('perhitungan.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('perhitungan.index') }}">
            <i class="fas fa-fw fa-sort-amount-up-alt"></i>
            <span>Metode SAW</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Tambahan Style untuk Sidebar Title -->
    <style>
        body.sidebar-toggled .sidebar .sidebar-brand-icon img {
            width: 40px !important;
            transition: all 0.3s ease;
        }

        .sidebar .sidebar-brand-icon img {
            transition: all 0.3s ease;
        }

        .sidebar-title .title-main {
            font-size: 0.95rem;
        }

        .sidebar-title .title-sub {
            font-size: 0.8rem;
        }

        body.sidebar-toggled .sidebar .sidebar-title .title-main {
            font-size: 0.65rem !important;
        }

        body.sidebar-toggled .sidebar .sidebar-title .title-sub {
            font-size: 0.55rem !important;
        }
    </style>

</ul>
