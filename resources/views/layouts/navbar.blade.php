<!-- Navbar Start -->
<div class="container-fluid nav-bar bg-transparent">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
        <a href="/" class="navbar-brand d-flex align-items-center text-center">
            <div class="icon p-2 me-2">
                <img class="img-fluid" src="{{ asset($logo) }}" alt="Icon" style="width: 30px; height: 30px;">
            </div>
            <h1 class="m-0 text-primary">{{ $title }}</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
                <a href="/" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                <a href="/about" class="nav-item nav-link {{ request()->is('about') ? 'active' : '' }}">Tentang
                    Kami</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Akademik</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="#" class="dropdown-item {{ request()->is('kelas') ? 'active' : '' }}">Kelas</a>
                        <a href="#" class="dropdown-item {{ request()->is('galeri') ? 'active' : '' }}">Galeri</a>
                        <a href="#"
                            class="dropdown-item {{ request()->is('santri-alumni') ? 'active' : '' }}">Santri &
                            Alumni</a>
                    </div>
                </div>
                <a href="/kontak" class="nav-item nav-link {{ request()->is('kontak') ? 'active' : '' }}">Kontak</a>
            </div>
            <a href="/daftar" class="btn btn-primary px-3 d-flex">Daftar</a>
        </div>
    </nav>
</div>
<!-- Navbar End -->
