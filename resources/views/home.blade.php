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
                    remaja.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <!-- Tombol 1: Mulai Belajar (Background Ungu, Tulisan Putih, Klik: Border Kuning) -->
                    <a href="{{ route('belajar') }}"
                        class="btn btn-lg text-decoration-none d-inline-flex align-items-center justify-content-center cst-btn-ungu"
                        style="background-color: var(--primary-color); color: #FFFFFF; font-weight: 700; border-radius: 10px; min-height: 48px; border: 2px solid transparent; transition: all 0.2s;">
                        Mulai Belajar
                    </a>

                    <!-- Tombol 2: Cari Bantuan (Warna Putih/Transparent, Hover: Ungu Muda, Klik: BG Pink + Border Kuning) -->
                    <a href="{{ route('bantuan') }}"
                        class="btn btn-lg text-decoration-none d-inline-flex align-items-center justify-content-center cst-btn-bantuan"
                        style="background-color: #FFFFFF; color: var(--primary-color); border: 2px solid var(--primary-color); font-weight: 600; border-radius: 10px; min-height: 48px; transition: all 0.2s;">
                        Cari Bantuan
                    </a>
                </div>

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

                    /* Mematikan garis/border kuning saat elemen apa pun diklik atau difokuskan */
                    *,
                    *:focus,
                    *:active,
                    .card:focus,
                    .card:active {
                        outline: none !important;
                        box-shadow: none !important;
                    }
                </style>
            </div>
        </div>
    </section>

    <!-- SECTION 2: Perjalanan Belajarmu / Progress Tracker -->
    <section id="perjalanan-belajar" class="mb-5 py-3">
        <div class="card p-4 p-md-5 border-0 shadow-sm" style="border-radius: 24px;">
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
                    <h2 class="h3 fw-bold text-dark mb-3">Perjalanan Belajarmu</h2>

                    <!-- Logika Auth: Jika belum login ke register, jika sudah login ke route 'belajar' -->
                    <a href="{{ auth()->check() ? route('belajar') : route('register') }}"
                        class="card module-start-card text-decoration-none d-block p-4"
                        style="background-color: #FDFBFF; border: 1px solid #EAEAEA; border-radius: 16px;"
                        aria-label="Mulai modul pertama: Mengenal Tubuhku">

                        <!-- Badge diposisikan agar sejajar rata kiri dengan teks di bawahnya -->
                        <span class="badge d-inline-block mb-3 px-3 py-2"
                            style="background-color: var(--bg-pink); color: var(--primary-color); border-radius: 20px; font-weight: 600;">
                            <i class="bi bi-magic me-1" aria-hidden="true"></i> Mulai dari sini
                        </span>

                        <h3 class="h5 fw-bold text-dark mb-2 d-flex align-items-center justify-content-between">
                            Modul 1: Mengenal Tubuh Kita
                            <i class="bi bi-arrow-right-short fs-4 module-start-arrow" aria-hidden="true"></i>
                        </h3>
                        <p class="text-muted small mb-0">
                            Belajar tentang bagian-bagian tubuh dan fungsinya, termasuk organ reproduksi
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: Fitur AKRAB Grid -->
    <!-- SECTION 3: Fitur AKRAB Grid (Ikon Ditengahkan) -->
    <section class="mb-5 mt-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-dark mb-2">Fitur AKRAB</h2>
            <p class="text-muted fs-5">Dirancang khusus untuk remaja dengan pendekatan inklusif dan aksesibel</p>
        </div>

        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3 text-center" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3 mx-auto"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-camera-video fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Materi Lengkap</h3>
                        <p class="card-text text-muted small lh-base">
                            15 modul pembelajaran dengan media interaktif.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3 text-center" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3 mx-auto"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-card-text fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Ruang Pendamping</h3>
                        <p class="card-text text-muted small lh-base">
                            Panduan khusus untuk guru, orang tua, dan tenaga kesehatan.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3 text-center" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3 mx-auto"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-ui-checks-grid fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Bantuan Tersedia</h3>
                        <p class="card-text text-muted small lh-base">
                            Informasi cara mencari bantuan dan layanan yang terverifikasi.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 p-3 text-center" style="border-radius: 20px;">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-3 mx-auto"
                            style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-chat-heart fs-4" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Aksesibilitas Penuh</h3>
                        <p class="card-text text-muted small lh-base">
                            Sesuaikan ukuran teks, kontras, dan fitur aksesibilitas lainnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: Glosarium & Pendamping (Ikon & Judul Bersebelahan) -->
    <section class="mb-5 pt-4">
        <div class="p-4 p-md-5 text-center">
            <h2 class="h3 fw-bold text-dark mb-2">Ada yang Masih Membingungkan?</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width: 650px;">
                Pilih jalur informasi yang sesuai dengan kebutuhanmu di bawah ini.
            </p>

            <div class="row g-4 justify-content-center text-start">
                <!-- Kartu Glosarium -->
                <div class="col-md-6">
                    <div
                        class="card h-100 border-0 p-4 rounded-4 shadow-sm bg-white d-flex flex-column justify-content-between">
                        <div>
                            <!-- Ikon dan Judul Bersebelahan -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                    style="width: 48px; height: 48px; background-color: var(--bg-pink); color: var(--primary-color); font-size: 1.3rem;">
                                    <i class="bi bi-journal-text" aria-hidden="true"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-0">Kamus Glosarium</h3>
                            </div>
                            <p class="text-muted small mb-4" style="line-height: 1.6;">
                                Temukan arti dan penjelasan dari istilah-istilah seputar tubuh dan kesehatan reproduksi
                                dengan bahasa yang mudah dipahami.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('glosarium') }}"
                                class="btn btn-akrab-primary w-100 py-2 fw-bold text-decoration-none"
                                style="border-radius: 12px;">
                                Buka Glosarium
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kartu Ruang Pendamping -->
                <div class="col-md-6">
                    <div
                        class="card h-100 border-0 p-4 rounded-4 shadow-sm bg-white d-flex flex-column justify-content-between">
                        <div>
                            <!-- Ikon dan Judul Bersebelahan -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                    style="width: 48px; height: 48px; background-color:var(--bg-pink); color: var(--primary-color); font-size: 1.3rem;">
                                    <i class="bi bi-people-fill" aria-hidden="true"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-0">Ruang Pendamping</h3>
                            </div>
                            <p class="text-muted small mb-4" style="line-height: 1.6;">
                                Panduan khusus, modul pendampingan, serta informasi penting bagi orang tua dan guru dalam
                                membersamai remaja.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('bantuan') }}"
                                class="btn btn-akrab-primary w-100 py-2 fw-bold text-decoration-none"
                                style="border-radius: 12px;">
                                Kunjungi Ruang Pendamping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection