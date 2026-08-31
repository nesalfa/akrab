@extends('layouts.app')

@section('title', 'Tentang AKRAB - Platform Edukasi Kesehatan Reproduksi')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><span class="current" aria-current="page">Tentang AKRAB</span></li>
            </ol>
        </nav>

        <!-- Hero Section -->
        <section class="rounded-4 mb-5 p-4 p-md-5 text-center text-lg-start position-relative overflow-hidden"
            style="background-color: var(--bg-pink); border: 1px solid #F5D9E4;">
            <div class="row align-items-center g-4 position-relative z-1">
                <div class="col-lg-8">
                    <span class="badge rounded-pill px-3 py-2 mb-3"
                        style="background-color: var(--primary-color); color: #FFFFFF; font-weight: 600;">
                        <i class="bi bi-universal-access me-1" aria-hidden="true"></i> Dirancang Inklusif & Aksesibel
                    </span>
                    <h1 class="display-5 fw-bold mb-3" style="color: var(--primary-color);">
                        Mengenal Platform AKRAB
                    </h1>
                    <p class="fs-5 mb-0" style="color: var(--text-light); line-height: 1.7; max-width: 800px;">
                        AKRAB adalah ruang belajar digital yang menyajikan edukasi kesehatan reproduksi dan seksual
                        bagi remaja secara aman, akurat, dan mudah dipahami, dengan perhatian khusus bagi remaja.
                    </p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <!-- Icon dekoratif -->
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                        style="width: 160px; height: 160px; background-color: #FFFFFF; color: var(--accent-hover);">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 5rem;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Misi & Tujuan -->
        <section class="mb-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2" style="color: var(--text-dark);">Tujuan & Komitmen Kami</h2>
                <p style="color: var(--text-light);">Mengapa platform AKRAB hadir untuk teman-teman semua?</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center custom-card-hover">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto"
                            style="width: 64px; height: 64px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-shield-check fs-2" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="color: var(--text-dark);">Informasi Ramah & Aman</h3>
                        <p class="small mb-0" style="color: var(--text-light); line-height: 1.6;">
                            Menyediakan materi kesehatan reproduksi tanpa stigma, tabu, atau bahasa yang membingungkan.
                            Semua isi konten tersaring dan ilmiah.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center custom-card-hover">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto"
                            style="width: 64px; height: 64px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-image-fill fs-2" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="color: var(--text-dark);">Penyampaian Visual</h3>
                        <p class="small mb-0" style="color: var(--text-light); line-height: 1.6;">
                            Mengutamakan pemahaman visual melalui ilustrasi, rangkuman poin-poin jelas, serta struktur teks
                            yang ramah pembaca Tuli dan fitur Aksesibilitas.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center custom-card-hover">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto"
                            style="width: 64px; height: 64px; background-color: var(--bg-pink); color: var(--primary-color);">
                            <i class="bi bi-people-fill fs-2" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="color: var(--text-dark);">Hak Edukasi Setara</h3>
                        <p class="small mb-0" style="color: var(--text-light); line-height: 1.6;">
                            Memastikan setiap remaja memiliki hak yang sama dalam memahami tubuhnya, menjaga kesehatan, dan
                            membuat keputusan yang bertanggung jawab.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Standar Aksesibilitas (WCAG 2.2 AA) -->
        <section class="mb-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-7 p-4 p-md-5">
                        <h2 class="h3 fw-bold mb-3" style="color: var(--primary-color);">
                            Pernyataan Aksesibilitas
                        </h2>
                        <p class="mb-4" style="color: var(--text-light); line-height: 1.6;">
                            Kami berkomitmen untuk terus menjaga kelayakan platform ini agar dapat diakses oleh semua
                            pengguna
                            tanpa hambatan teknis maupun visual.
                        </p>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                                style="background-color: #FAFAFA; border: 1px solid #EAEAEA;">
                                <i class="bi bi-palette-fill fs-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <strong style="color: var(--text-dark);">Kontras Warna Tinggi</strong>
                                    <div class="small mt-1" style="color: var(--text-light);">Memenuhi rasio kontras standar
                                        agar tulisan nyaman dibaca dan tidak menyilaukan mata.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                                style="background-color: #FAFAFA; border: 1px solid #EAEAEA;">
                                <i class="bi bi-keyboard-fill fs-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <strong style="color: var(--text-dark);">Navigasi Keyboard</strong>
                                    <div class="small mt-1" style="color: var(--text-light);">Seluruh menu, materi, dan kuis
                                        dapat diakses sepenuhnya menggunakan navigasi keyboard.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                                style="background-color: #FAFAFA; border: 1px solid #EAEAEA;">
                                <i class="bi bi-earbuds fs-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <strong style="color: var(--text-dark);">Ramah Pembaca Layar</strong>
                                    <div class="small mt-1" style="color: var(--text-light);">Dilengkapi struktur semantik
                                        dan deskripsi gambar yang terbaca jelas oleh perangkat Screen Reader.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 p-4 p-md-5 d-flex flex-column justify-content-center"
                        style="background-color: var(--bg-pink);">
                        <div class="text-center text-lg-start">
                            <i class="bi bi-patch-check-fill mb-3 d-block"
                                style="font-size: 3rem; color: var(--primary-color);"></i>
                            <h3 class="h4 fw-bold text-dark mb-3">Sesuai Standar Internasional</h3>
                            <p class="text-dark small mb-0" style="line-height: 1.6;">
                                Pengembangan platform AKRAB berpedoman pada standar Web Content Accessibility Guidelines
                                (WCAG) 2.2 Level AA.
                                Kami akan terus melakukan perbaikan berkelanjutan untuk pengalaman belajar yang lebih baik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ajakan / Call to Action (Menggantikan Form Laporan) -->
        <section class="mb-5 text-center">
            <h2 class="h4 fw-bold mb-3" style="color: var(--text-dark);">Sudah Siap Memulai Perjalanan Belajarmu?</h2>
            <p class="mb-4" style="color: var(--text-light);">Eksplorasi materi sekarang atau cari bantuan jika kamu
                memiliki pertanyaan.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('belajar') }}" class="btn btn-akrab-primary btn-lg rounded-pill px-4">
                    <i class="bi bi-book-half me-2"></i> Mulai Belajar
                </a>
                <a href="{{ route('bantuan') }}" class="btn btn-akrab-outline btn-lg rounded-pill px-4"
                    style="background-color: #FFFFFF;">
                    <i class="bi bi-life-preserver me-2"></i> Cari Bantuan
                </a>
            </div>
        </section>
    </div>
@endsection

@section('additional_css')
    <style>
        /* Breadcrumb (Sama seperti halaman Tanya Ahli) */
        .breadcrumb-pill {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin: 0;
            list-style: none;
            gap: 0.5rem;
            align-items: center;
        }

        .breadcrumb-pill li {
            display: flex;
            align-items: center;
        }

        .breadcrumb-pill li a,
        .breadcrumb-pill li .current {
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .breadcrumb-pill li a {
            background-color: #FFFFFF;
            color: var(--primary-color);
            border: 1px solid #EAEAEA;
        }

        .breadcrumb-pill li a:hover {
            background-color: var(--bg-pink);
            border-color: var(--primary-color);
        }

        .breadcrumb-pill li:not(:first-child)::before {
            content: '\F285';
            font-family: 'bootstrap-icons';
            margin-right: 0.5rem;
            color: #A0A0A0;
            font-size: 0.85rem;
        }

        .breadcrumb-pill li .current {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border: 1px solid var(--primary-color);
        }

        /* Hover Effect untuk Card Misi */
        .custom-card-hover {
            transition: transform 0.2s ease, border-color 0.2s ease;
            border: 1px solid transparent !important;
        }

        .custom-card-hover:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color) !important;
            box-shadow: 0 10px 20px rgba(106, 76, 147, 0.08) !important;
        }
    </style>
@endsection