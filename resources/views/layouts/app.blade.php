<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AKRAB - Akses Kesehatan Reproduksi Remaja')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts: Inter untuk tingkat keterbacaan yang optimal -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS untuk AKRAB (UI/UX & Aksesibilitas WCAG) -->
    <style>
        :root {
            /* Tema Warna Baru: Ungu, Kuning, Pink */
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            --accent-hover: #E5B534;
            --bg-pink: #FFF0F5;
            --text-dark: #1A1A1A;
            --text-light: #4A4A4A;
        }

        /* Global Styles */
        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background-color: #FAFAFA;
        }

        /* Accessibility: Indikator Fokus Keyboard (Wajib WCAG) */
        *:focus {
            outline: 3px solid var(--accent-color) !important;
            outline-offset: 3px !important;
        }

        /* Header & Navigasi */
        .navbar {
            background-color: #FFFFFF;
            border-bottom: 2px solid #EEEEEE;
            padding: 0.85rem 0;
        }

        .navbar-brand {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Frame lingkaran untuk logo AKRAB */
        .brand-logo-frame {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary-color);
            background-color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(106, 76, 147, 0.15);
        }

        .brand-logo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Nav Collapse Flex Layout */
        .navbar-collapse {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        /* Grup menu navigasi utama (sisi kanan bagian 1) */
        .nav-menu-list {
            display: flex;
            flex-direction: row !important;
            /* Memaksa sejajar horizontal di layar desktop */
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 2px solid transparent !important;
            white-space: nowrap;
            /* Mencegah teks menu patah menjadi dua baris */
        }

        /* Efek saat kursor diarahkan (Hover): Latar Belakang Pink */
        .nav-link:hover {
            background-color: var(--bg-pink) !important;
            color: var(--primary-color) !important;
            border-color: transparent !important;
        }

        /* Border KUNING bertahan secara permanen ketika menu aktif/terpilih */
        .nav-link-akrab.active-page {
            border-color: var(--accent-color) !important;
            color: var(--primary-color) !important;
            font-weight: 700;
            background-color: transparent !important;
            /* Latar pink hilang saat aktif, digantikan border kuning */
        }

        /* Grup Masuk/Daftar (sisi kanan bagian 2) */
        .nav-auth-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: 1.5rem;
            padding-left: 1.5rem;
            border-left: 2px solid #EEEEEE;
        }

        .btn-nav-outline {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            border-radius: 10px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            background-color: transparent;
            white-space: nowrap;
        }

        .btn-nav-outline:hover,
        .btn-nav-outline:focus {
            background-color: var(--primary-color);
            color: #FFFFFF;
        }

        .btn-nav-solid {
            background-color: var(--accent-color);
            color: var(--text-dark);
            font-weight: 700;
            padding: 0.5rem 1.2rem;
            border-radius: 10px;
            border: 2px solid var(--accent-color);
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-nav-solid:hover,
        .btn-nav-solid:focus {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: var(--text-dark);
        }

        /* ---------- Profil (tampil kalau sudah login) ---------- */
        .profile-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: transparent;
            border: 2px solid transparent;
            border-radius: 999px;
            padding: 0.3rem 0.75rem 0.3rem 0.3rem;
            min-height: 44px;
            transition: all 0.2s;
        }

        .profile-toggle:hover,
        .profile-toggle:focus-visible,
        .profile-toggle[aria-expanded="true"] {
            background-color: var(--bg-pink);
            border-color: var(--primary-color);
        }

        .profile-toggle::after {
            /* Panah dropdown Bootstrap dibiarkan, cuma diberi warna senada */
            color: var(--primary-color);
        }

        .profile-avatar {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .profile-name {
            font-weight: 600;
            color: var(--text-dark);
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .profile-dropdown-menu {
            min-width: 230px;
            border-radius: 16px;
            border: 1px solid #EEEEEE;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .profile-dropdown-menu .dropdown-item {
            padding: 0.6rem 1rem;
            font-weight: 500;
            border-radius: 0;
        }

        .profile-dropdown-menu .dropdown-item:hover,
        .profile-dropdown-menu .dropdown-item:focus {
            background-color: var(--bg-pink);
            color: var(--primary-color);
        }

        .profile-dropdown-menu .dropdown-item.text-danger:hover,
        .profile-dropdown-menu .dropdown-item.text-danger:focus {
            background-color: #FBEAF0;
            color: #B02A5B !important;
        }

        .profile-dropdown-menu form {
            margin: 0;
        }

        /* ---------- FOOTER STYLES ---------- */
        .custom-footer {
            background-color: var(--primary-hover);
            border-top: 1px solid #EAEAEA;
            padding: 4rem 0 3rem 0;
            margin-top: 1rem; /* UBAH DI SINI: dari 5rem menjadi 1rem (atau hapus baris ini) */
            color: #FAFAFA;
        }

        .custom-footer h4 {
            color: #FAFAFA;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
        }

        .custom-footer-links a {
            color: #FAFAFA;
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .custom-footer-links a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Kustomisasi Responsif Mobile & Tablet */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                flex-direction: column;
                align-items: stretch;
            }

            .nav-menu-list {
                flex-direction: column !important;
                align-items: stretch;
                width: 100%;
                margin-top: 1rem;
            }

            .nav-link {
                justify-content: flex-start;
                border-radius: 12px;
            }

            .nav-auth-group {
                margin-left: 0;
                padding-left: 0;
                border-left: none;
                border-top: 2px solid #EEEEEE;
                padding-top: 1rem;
                margin-top: 1rem;
                width: 100%;
            }
        }

        /* Main Container */
        main {
            min-height: calc(100vh - 200px);
            padding: 1.75rem 0 3.5rem;
        }

        .container {
            max-width: 1280px;
            /* Diubah dari 1000px agar layout halaman lapang dan rapi */
            width: 100%;
        }

        /* ---------- Tombol bersama AKRAB ---------- */
        .btn-akrab-primary,
        .btn-akrab-outline,
        .btn-akrab-accent,
        .btn-akrab-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 46px;
            font-weight: 700;
            padding: 0.65rem 1.4rem;
            border-radius: 999px;
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            text-decoration: none;
            font-size: 0.95rem;
            border: 2px solid transparent;
        }

        .btn-akrab-primary {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
        }

        .btn-akrab-primary:hover,
        .btn-akrab-primary:focus-visible {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #FFFFFF;
        }

        .btn-akrab-outline {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-akrab-outline:hover,
        .btn-akrab-outline:focus-visible {
            background-color: var(--bg-pink);
            color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-akrab-accent {
            background-color: var(--accent-color);
            color: var(--text-dark);
            border-color: var(--accent-color);
        }

        .btn-akrab-accent:hover,
        .btn-akrab-accent:focus-visible {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: var(--text-dark);
        }

        .btn-akrab-danger {
            background-color: #C7365F;
            color: #FFFFFF;
            border-color: #C7365F;
        }

        .btn-akrab-danger:hover,
        .btn-akrab-danger:focus-visible {
            background-color: #A32A4C;
            border-color: #A32A4C;
            color: #FFFFFF;
        }

        .btn-akrab-primary:disabled,
        .btn-akrab-outline:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .auth-card {
            border: 1px solid #EFE7F3;
            border-radius: 22px;
            background-color: #FFFFFF;
        }
    </style>
    @yield('additional_css')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top" aria-label="Navigasi utama">
        <div class="container-fluid px-3 px-md-5">
            <!-- SISI KIRI: Logo + Tulisan AKRAB -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-logo-frame">
                    <img src="{{ asset('images/logo-akrab.png') }}"
                        alt="Logo AKRAB: ilustrasi dua telinga dan tangan bergandengan membentuk simbol hati">
                </span>
                AKRAB
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- SISI KANAN: Penyelarasan Fleksibel Lebar Penuh -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center ms-auto w-100 justify-content-end">
                    <!-- Menu Utama -->
                    <ul class="navbar-nav nav-menu-list">
                        <li class="nav-item">
                            <a class="nav-link nav-link-akrab {{ request()->url() == route('home') && !request()->getQueryString() ? 'active-page' : '' }}"
                                href="{{ route('home') }}">
                                <i class="bi bi-house-door" aria-hidden="true"></i> Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-akrab {{ Route::is('belajar') ? 'active-page' : '' }}"
                                href="{{ route('belajar') }}">
                                <i class="bi bi-book" aria-hidden="true"></i> Belajar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-akrab {{ Route::is(['bantuan', 'rujukan', 'tanya-ahli', 'pendamping']) ? 'active-page' : '' }}"
                            href="{{ route('bantuan') }}"><i class="bi bi-life-preserver" aria-hidden="true"></i> Bantuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-akrab {{ Route::is('tentang') ? 'active-page' : '' }}" 
                                href="{{ route('tentang') }}">
                                <i class="bi bi-info-circle" aria-hidden="true"></i> Tentang
                            </a>
                        </li>
                    </ul>

                    <!-- Grup Otorisasi: dinamis — tamu lihat Masuk/Daftar, sudah login lihat profil -->
                    <div class="nav-auth-group">
                        @guest
                            <a href="{{ route('login') }}" class="btn-nav-outline">
                                <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i> Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn-nav-solid">
                                <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Daftar
                            </a>
                        @else
                            <div class="dropdown">
                                <button class="profile-toggle dropdown-toggle" type="button"
                                        id="profileMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="profile-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <span class="profile-name d-none d-lg-inline">{{ auth()->user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="profileMenuButton">
                                    <li class="px-3 py-2">
                                        <div class="fw-bold" style="color: var(--text-dark);">{{ auth()->user()->name }}</div>
                                        <div class="small" style="color: var(--text-light);">
                                            {{ auth()->user()->isAdmin() ? auth()->user()->username : auth()->user()->email }}
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if(auth()->user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i> Dashboard Admin
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{ route('belajar') }}">
                                                <i class="bi bi-journal-bookmark me-2" aria-hidden="true"></i> Materi Belajar
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" tabindex="-1">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Kolom 1: Branding -->
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2 mb-3 text-dark fw-bold fs-4"
                        style="color: var(--bg-pink) !important;">
                        <i class="bi bi-balloon-heart-fill" aria-hidden="true"></i> AKRAB
                    </div>
                    <p class="small text mb-3" style="line-height: 1.5;">
                        Akses Kesehatan Reproduksi Remaja yang Adaptif dan Bersahabat
                    </p>
                    <p class="small text" style="line-height: 1.5;">
                        Ruang belajar kesehatan reproduksi yang aman, ramah, dan inklusif bagi remaja.
                    </p>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div class="col-md-3 custom-footer-links">
                    <h4 class="fw-bold">Tautan Cepat</h4>
                    <a href="{{ route('belajar') }}">Mulai Belajar</a>
                    <a href="{{ route('bantuan') }}">Cara Mencari Bantuan</a>
                    <a href="{{ route('tentang') }}">Pernyataan Aksesibilitas</a>
                </div>

                <!-- Kolom 3: Kontak Darurat -->
                <div class="col-md-4">
                    <h4 class="fw-bold">Kontak Darurat</h4>
                    <ul class="list-unstyled mb-0" style="font-size: 0.95rem;">
                        <li class="mb-2.5 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone" aria-hidden="true"></i> Polisi: <strong>110</strong>
                        </li>
                        <li class="mb-2.5 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-plus" aria-hidden="true"></i> Ambulans: <strong>118 / 119</strong>
                        </li>
                        <li class="mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-envelope" aria-hidden="true"></i> Email: <a href="mailto:bantuan@akrab.id"
                                style="color: #fafafa; text-decoration: none;"><strong>bantuan@akrab.id</strong></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('additional_js')
</body>

</html>

