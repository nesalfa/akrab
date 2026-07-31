@extends('layouts.app')

@section('title', 'AKRAB - Ruang Belajar Aman dan Ramah')

@section('content')
    <!-- SECTION 1: Welcome / Hero Banner -->
    <section class="mb-5">
        <div class="card border-0" style="background-color: var(--bg-pink); border-radius: 24px;">
            <div class="card-body text-center py-5 px-4">
                <div class="mb-3" style="color: var(--primary-color);">
                    <i class="bi bi-stars" style="font-size: 3.5rem;" aria-hidden="true"></i>
                </div>
                <h1 class="card-title fw-bold mb-3" style="font-size: 3rem; color: var(--text-dark);">
                    Selamat Datang di <span style="color: var(--primary-color);">AKRAB</span>
                </h1>
                <p class="fw-semibold mb-2" style="color: var(--text-light); font-size: 1.25rem;">
                    Akses Kesehatan Reproduksi Remaja yang Adaptif dan Bersahabat
                </p>
                <p class="mx-auto mb-4" style="color: var(--text-light); max-width: 700px; font-size: 1.1rem;">
                    Ruang belajar kesehatan reproduksi yang <strong>aman, ramah,</strong> dan <strong>inklusif</strong> bagi
                    remaja Tuli.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <!-- Tombol 1: Mulai Belajar (Background Ungu, Tulisan Putih, Klik: Border Kuning) -->
                    <a href="{{ route('belajar') }}"
                        class="btn btn-lg text-decoration-none d-inline-flex align-items-center justify-content-center cst-btn-ungu"
                        style="background-color: var(--primary-color); color: #FFFFFF; font-weight: 700; border-radius: 10px; min-height: 48px; border: 2px solid transparent; transition: all 0.2s;">
                        Mulai Belajar <i class="bi bi-arrow-right-short fs-4 ms-1" aria-hidden="true"></i>
                    </a>

                    <!-- Tombol 2: Cari Bantuan (Warna Putih/Transparent, Hover: Ungu Muda, Klik: BG Pink + Border Kuning) -->
                    <a href="{{ route('bantuan') }}"
                        class="btn btn-lg text-decoration-none d-inline-flex align-items-center justify-content-center cst-btn-bantuan"
                        style="background-color: #FFFFFF; color: var(--primary-color); border: 2px solid var(--primary-color); font-weight: 600; border-radius: 10px; min-height: 48px; transition: all 0.2s;">
                        Cari Bantuan
                    </a>
                </div>

                <!-- Tambahkan style pendukung ini di bawahnya atau di dalam tag <style> app.blade Anda -->
                <style>
                    /* Tombol Mulai Belajar */
                    .cst-btn-ungu:hover {
                        background-color: var(--primary-hover) !important;
                        color: #FFFFFF !important;
                    }

                    .cst-btn-ungu:focus,
                    .cst-btn-ungu:active {
                        outline: none !important;
                        border: 2px solid var(--accent-color) !important;
                        box-shadow: 0 0 0 3px rgba(255, 202, 58, 0.4) !important;
                    }

                    /* Tombol Cari Bantuan */
                    .cst-btn-bantuan:hover {
                        background-color: #F3E5F5 !important;
                        /* Warna ungu muda lembut */
                        color: var(--primary-hover) !important;
                        border-color: var(--primary-hover) !important;
                    }

                    .cst-btn-bantuan:focus,
                    .cst-btn-bantuan:active {
                        outline: none !important;
                        background-color: var(--bg-pink) !important;
                        /* Latar belakang pink saat diklik */
                        border: 2px solid var(--accent-color) !important;
                        /* Border kuning tegas */
                        color: var(--primary-color) !important;
                        box-shadow: 0 0 0 3px rgba(255, 202, 58, 0.4) !important;
                    }

                    .module-start-card {
                        transition: background-color 0.2s ease, border-color 0.2s ease;
                        color: inherit;
                    }

                    .module-start-card:hover,
                    .module-start-card:focus-visible {
                        background-color: var(--bg-pink) !important;
                        border-color: var(--primary-color) !important;
                    }

                    /* Override outline fokus global (kuning) khusus di sini.
           Alasan: kartu ini berlatar hampir putih (#FDFBFF), dan kuning
           (--accent-color) di atas latar terang gagal kontras WCAG untuk
           fokus (rasio ~1.5:1, minimum 3:1) — sudah pernah saya cek waktu
           perbaiki halaman modul. Ungu tua lolos (6.8:1) di latar ini. */
                    .module-start-card:focus-visible {
                        outline: 3px solid var(--primary-hover) !important;
                        outline-offset: 3px !important;
                    }

                    .module-start-arrow {
                        color: var(--primary-color);
                        transition: transform 0.15s ease;
                    }

                    .module-start-card:hover .module-start-arrow,
                    .module-start-card:focus-visible .module-start-arrow {
                        transform: translateX(4px);
                    }
                </style>
            </div>
        </div>
    </section>

    <!-- SECTION 2: Perjalanan Belajarmu / Progress Tracker -->
    <section id="perjalanan-belajar" class="mb-5 py-3">
        <div class="card p-4 p-md-5" style="border-radius: 24px;">
            <div class="row align-items-center g-4">
                <!-- Sisi Kiri: Lingkaran Progres -->
                <div class="col-md-3 text-center">
                    <div class="d-inline-flex flex-column align-items-center justify-content-center position-relative shadow-sm rounded-circle bg-light"
                        style="width: 140px; height: 140px; border: 12px solid #F0F2F5;">
                        <div class="fs-2 fw-bold text-dark mb-0">
                            <span style="color: var(--primary-color);">0</span><span class="fs-5 text-muted">/15</span>
                        </div>
                        <div class="small text-muted" style="font-size: 11px; line-height: 1.1;">modul selesai</div>
                    </div>
                </div>
                <!-- Sisi Kanan: Modul Pertama -->
                <div class="col-md-9">
                    <h2 class="h3 fw-bold text-dark mb-2">Perjalanan Belajarmu</h2>

                    <a href="{{ route('register') }}" class="card p-3 mb-4 module-start-card text-decoration-none d-block"
                        style="background-color: #FDFBFF; border: 1px solid #EAEAEA; border-radius: 16px;"
                        aria-label="Mulai modul pertama: Mengenal Tubuhku — belajar bagian-bagian tubuh dan fungsinya, termasuk organ reproduksi">
                        <div class="card-body p-2">
                            <span class="badge mb-2 px-3 py-1.5"
                                style="background-color: var(--bg-pink); color: var(--primary-color); border-radius: 20px; font-weight: 600;">
                                <i class="bi bi-magic me-1" aria-hidden="true"></i> Mulai dari sini
                            </span>
                            <h3 class="h5 fw-bold text-dark my-2 d-flex align-items-center justify-content-between">
                                Modul 1: Mengenal Tubuhku
                                <i class="bi bi-arrow-right-short fs-4 module-start-arrow" aria-hidden="true"></i>
                            </h3>
                            <p class="text-muted small mb-0">
                                Belajar tentang bagian-bagian tubuh dan fungsinya, termasuk organ reproduksi
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: Fitur AKRAB Grid -->
    <section class="mb-5 mt-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-dark mb-2">Fitur AKRAB</h2>
            <p class="text-muted fs-5">Dirancang khusus untuk remaja Tuli dengan pendekatan inklusif dan aksesibel</p>
        </div>

        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-camera-video fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Materi Lengkap</h3>
                        <p class="card-text text-muted small lh-base">
                            15 modul pembelajaran dengan video bahasa isyarat, infografis, dan kuis interaktif
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-card-text fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Ruang Pendamping</h3>
                        <p class="card-text text-muted small lh-base">
                            Panduan khusus untuk guru, orang tua, dan tenaga kesehatan
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-ui-checks-grid fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Bantuan Tersedia</h3>
                        <p class="card-text text-muted small lh-base">
                            Informasi cara mencari bantuan dan layanan yang terverifikasi
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-chat-heart fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Aksesibilitas Penuh</h3>
                        <p class="card-text text-muted small lh-base">
                            Sesuaikan ukuran teks, kontras, dan fitur aksesibilitas lainnya
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: Siap Untuk Mulai Belajar Callout Banner -->
    <section class="mb-5 pt-4">
        <div class="text-center py-5">
            <h2 class="display-6 fw-bold text-dark mb-3">Siap Untuk Mulai Belajar?</h2>
            <p class="text-muted fs-5 mx-auto mb-4" style="max-width: 750px;">
                Akses 15 modul pembelajaran lengkap tentang kesehatan reproduksi yang dirancang khusus untuk remaja Tuli.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('belajar') }}" class="btn btn-accent btn-lg text-decoration-none">
                    Mulai Belajar Sekarang
                </a>
                <a href="{{ route('tentang') }}"
                    class="btn btn-lg text-decoration-none d-inline-flex align-items-center justify-content-center cst-btn-bantuan"
                    style="background-color: #FFFFFF; color: var(--primary-color); border: 2px solid var(--primary-color); font-weight: 600; border-radius: 10px; min-height: 48px; transition: all 0.2s;">
                    Ruang Pendamping
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 5: Footer Komplit Tiga Kolom -->
    @section('additional_css')
        <style>
            /* Menyembunyikan footer bawaan layout app.blade.php khusus di halaman beranda */
            body>footer {
                display: none !important;
            }

            /* Desain Footer Tiga Kolom */
            .custom-footer {
                background-color: #FAFAFA;
                border-top: 1px solid #EAEAEA;
                padding: 4rem 0 3rem 0;
                margin-top: 5rem;
                color: #4A4A4A;
            }

            .custom-footer h4 {
                color: var(--text-dark);
                font-size: 1.1rem;
                margin-bottom: 1.25rem;
            }

            .custom-footer-links a {
                color: #555555;
                text-decoration: none;
                display: block;
                margin-bottom: 0.75rem;
                font-size: 0.95rem;
            }

            .custom-footer-links a:hover {
                color: var(--primary-color);
                text-decoration: underline;
            }
        </style>
    @endsection

    </div> <!-- Penutup container bawaan agar footer bisa melebar full screen -->

    <footer class="custom-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Kolom 1: Branding -->
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2 mb-3 text-dark fw-bold fs-4"
                        style="color: var(--primary-color) !important;">
                        <i class="bi bi-balloon-heart-fill" aria-hidden="true"></i> AKRAB
                    </div>
                    <p class="small text-muted mb-3" style="line-height: 1.5;">
                        Akses Kesehatan Reproduksi Remaja yang Adaptif dan Bersahabat
                    </p>
                    <p class="small text-muted" style="line-height: 1.5;">
                        Ruang belajar kesehatan reproduksi yang aman, ramah, dan inklusif bagi remaja.
                    </p>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div class="col-md-3 custom-footer-links">
                    <h4 class="fw-bold">Tautan Cepat</h4>
                    <a href="{{ route('belajar') }}">Mulai Belajar</a>
                    <a href="{{ route('tentang') }}">Ruang Pendamping</a>
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
                                style="color: #555555; text-decoration: none;"><strong>bantuan@akrab.id</strong></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <div> <!-- Pembuka tag container pembantu untuk menyeimbangkan penutup div section layout app -->
@endsection