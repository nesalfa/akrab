<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - AKRAB')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            --bg-light: #F8F9FA;
            --text-dark: #1A1A1A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 280px;
            height: 100vh;
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            color: white;
            z-index: 1000;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            margin: 1.5rem 0.75rem 0.5rem 0.75rem;
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: var(--accent-color);
            color: var(--text-dark);
            font-weight: 700;
        }

        .sidebar-profile {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sidebar-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu-dark {
            background-color: #543A75;
            border: none;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
        }

        .dropdown-item:hover {
            background-color: var(--accent-color);
            color: var(--text-dark);
        }

        /* Efek klik untuk Card */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: block;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        /* Main Content Wrapper */
        .admin-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Top Navbar Admin */
        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .btn-akrab-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 700;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 0.6rem 1.25rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
            text-decoration: none;
        }

        .btn-akrab-primary:hover,
        .btn-akrab-primary:focus-visible {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #FFFFFF;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .admin-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
    @yield('additional_css')
</head>

<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar d-flex flex-column">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-shield-lock-fill text-warning"></i> AKRAB Admin
        </a>

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Ringkasan (Dashboard)
        </a>

        <div class="sidebar-section-title">Manajemen Konten</div>
        <a href="{{ route('admin.modules') }}"
            class="sidebar-link {{ request()->routeIs('admin.modules*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Kelola Modul & Isi
        </a>
        <a href="{{ route('admin.kuis-glosarium') }}"
            class="sidebar-link {{ request()->routeIs('admin.kuis-glosarium*') ? 'active' : '' }}">
            <i class="bi bi-question-circle"></i> Kelola Kuis & Glosarium
        </a>

        <div class="sidebar-section-title">Manajemen Pengguna</div>
        <a href="#" class="sidebar-link">
            <i class="bi bi-graph-up"></i> Progres Belajar Anak
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-people"></i> Profil Pengguna
        </a>
        <!-- Link untuk membalas pesan Tanya Ahli yang baru kita buat -->
        <a href="#" class="sidebar-link">
            <i class="bi bi-chat-quote"></i> Pesan Tanya Ahli
        </a>

        <!-- Profil Dropdown di Bawah Sidebar -->
        <div class="mt-auto pt-3 border-top border-light border-opacity-10">
            <div class="dropdown">
                <a href="#"
                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle sidebar-profile"
                    id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center me-2"
                        style="width: 38px; height: 38px;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                        <span class="fw-semibold small text-truncate"
                            style="max-width: 140px;">{{ auth()->user()->name }}</span>
                        <span class="text-white-50" style="font-size: 0.75rem;">Administrator</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow w-100 mb-2"
                    aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Ubah Profil</a></li>
                    <li>
                        <hr class="dropdown-divider border-light border-opacity-25">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-semibold"><i
                                    class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Konten Utama -->
    <!-- Konten Utama -->
    <main class="admin-content">
        <!-- Topbar: Lebih bersih tanpa icon profil (sudah pindah ke bawah) -->
        <div class="admin-topbar">
            <div>
                <h1 class="h4 fw-bold mb-0 text-dark">Halo, {{ auth()->user()->name ?? 'Admin' }}</h1>
                <p class="text-muted small mb-0">Pantau perkembangan belajar dan interaksi platform hari ini.</p>
            </div>
        </div>

        @yield('admin_content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('additional_js')
</body>

</html>