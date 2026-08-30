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
            height: 100dvh;
            max-height: 100dvh;
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.25rem 1rem env(safe-area-inset-bottom, 1rem) 1rem;
            display: flex;
            flex-direction: column;
            color: white;
            z-index: 1000;
            box-sizing: border-box;
        }

        .sidebar-header {
            flex-shrink: 0;
            margin-bottom: 1.25rem;
        }

        .sidebar-nav-container {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            overscroll-behavior: contain;
            padding-right: 4px;
            margin-bottom: 0.75rem;
        }

        /* Custom subtle scrollbar for sidebar nav */
        .sidebar-nav-container::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
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
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            margin: 1.25rem 0.75rem 0.35rem 0.75rem;
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

        /* Tombol Utama AKRAB Admin */
        .btn-akrab-primary {
            background-color: var(--primary-color) !important;
            color: #FFFFFF !important;
            border: 2px solid var(--primary-color) !important;
            font-weight: 600;
            padding: 0.6rem 1.35rem;
            border-radius: 12px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(106, 76, 147, 0.2);
            white-space: nowrap;
        }

        .btn-akrab-primary:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: #FFFFFF !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(106, 76, 147, 0.3);
        }

        .btn-akrab-primary:active {
            background-color: #432b60 !important;
            border-color: #432b60 !important;
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(106, 76, 147, 0.2);
        }

        .btn-akrab-primary:focus-visible {
            outline: 3px solid var(--accent-color) !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 0 4px rgba(255, 202, 58, 0.35) !important;
        }

        .btn-akrab-outline {
            border: 1.5px solid var(--primary-color) !important;
            color: var(--primary-color) !important;
            background: #FFFFFF;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 12px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-akrab-outline:hover {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: #FFFFFF !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(106, 76, 147, 0.25);
        }

        .btn-akrab-outline:active {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: #FFFFFF !important;
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(106, 76, 147, 0.2);
        }

        .btn-akrab-outline:focus-visible {
            outline: 3px solid var(--accent-color) !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 0 4px rgba(255, 202, 58, 0.35) !important;
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
            transition: margin-left 0.3s ease;
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
            gap: 1rem;
        }

        /* Mobile Hamburger & Overlay */
        .sidebar-toggle-btn {
            display: none;
            background: var(--bg-light);
            border: 1.5px solid #EAEAEA;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            color: var(--text-dark);
            font-size: 1.25rem;
            cursor: pointer;
            min-width: 44px;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .sidebar-toggle-btn:hover,
        .sidebar-toggle-btn:focus-visible {
            background: var(--bg-pink);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .sidebar-close-btn {
            display: none;
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
        }

        .sidebar-close-btn:hover,
        .sidebar-close-btn:focus-visible {
            background: rgba(255, 255, 255, 0.15);
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle-btn {
                display: inline-flex;
            }

            .sidebar-close-btn {
                display: block;
            }

            .admin-content {
                margin-left: 0;
                padding: 1.25rem 1rem;
            }

            .admin-topbar {
                padding: 0.85rem 1.15rem;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-sidebar {
                width: 85%;
                max-width: 300px;
            }

            .admin-topbar h1 {
                font-size: 1.1rem;
            }

            .admin-topbar p {
                font-size: 0.75rem;
            }
        }
    </style>
    @yield('additional_css')
</head>

<body>

    <!-- Sidebar Overlay Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar" aria-label="Sidebar Admin">
        <!-- Header Sidebar -->
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand flex-grow-1">
                <i class="bi bi-shield-lock-fill text-warning"></i> AKRAB Admin
            </a>
            <button type="button" class="sidebar-close-btn ms-2" id="sidebarCloseBtn" aria-label="Tutup Menu Sidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Container Menu Navigasi (Scrollable) -->
        <div class="sidebar-nav-container">
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
                class="sidebar-link {{ request()->routeIs('admin.kuis*') || request()->routeIs('admin.glosarium*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> Kelola Kuis & Glosarium
            </a>

            <div class="sidebar-section-title">Manajemen Pengguna</div>
            <a href="{{ route('admin.progress') }}"
                class="sidebar-link {{ request()->routeIs('admin.progress*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Progres Belajar Anak
            </a>
            <a href="{{ route('admin.consultations') }}"
                class="sidebar-link {{ request()->routeIs('admin.consultations*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i> Pesan Tanya Ahli
            </a>
        </div>

        <!-- Profil Dropdown di Bawah Sidebar (Sticky / Flex-Shrink 0) -->
        <div class="sidebar-footer">
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
                    <li><a class="dropdown-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile') }}"><i class="bi bi-person me-2"></i>Ubah Profil</a></li>
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
    <main class="admin-content">
        <!-- Topbar Admin dengan tombol toggle mobile -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka Menu Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h1 class="h4 fw-bold mb-0 text-dark">Halo, {{ auth()->user()->name ?? 'Admin' }}</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Pantau perkembangan belajar dan interaksi platform hari ini.</p>
                </div>
            </div>
        </div>

        @yield('admin_content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            function openSidebar() {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggleBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar?.classList.contains('show')) {
                    closeSidebar();
                }
            });

            // Tutup sidebar saat menu diklik di layar kecil
            sidebar?.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 991.98) {
                        closeSidebar();
                    }
                });
            });

            // Reset state saat resize ke desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth > 991.98) {
                    closeSidebar();
                }
            });
        });
    </script>
    @yield('additional_js')
</body>

</html>