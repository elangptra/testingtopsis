@if (Auth::user()->level == 'admin')
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #2B2A4C">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-0">
                {{-- <img src="{{ asset('img/logoRB-removebg-eview.png') }}" alt="Group Logo" width="130px" height="auto"
                    class="brand-image img-circle elevation-1" style="opacity: .8"> --}}
                <i class="fas fa-database"></i>
            </div>
            <div class="sidebar-brand-text mx-3">TOPSIS<sup></sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('kriteria') }}">
                <i class="fas fa-plus"></i>
                <span>Kriteria</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('alternatif') }}">
                <i class="fas fa-plus"></i>
                <span>Alternatif</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('nilaialt') }}">
                <i class="fas fa-plus-circle"></i>
                <span>Nilai Alternatif</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('normalisasi_nilai') }}">
                <i class="far fa-chart-bar"></i>
                <span>Normalisasi</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('hasil_ranking') }}">
                <i class="fas fa-sort-amount-up-alt"></i>
                <span>Hasil</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
@endif
