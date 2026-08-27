@extends('layouts.app')

@section('title', 'Tentang AKRAB - Platform Edukasi Kesehatan Reproduksi')

@section('content')
    <style>
        .hero-tentang {
            background: linear-gradient(135deg, #FFF0F5 0%, #F3E8FF 100%);
            border-radius: 24px;
            padding: 3rem 2rem;
            margin-bottom: 2.5rem;
        }

        .badge-a11y {
            background-color: var(--primary-color, #6A4C93);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .card-feature {
            border: 2px solid #EEEEEE;
            border-radius: 16px;
            padding: 1.75rem;
            height: 100%;
            transition: all 0.25s ease;
            background-color: #FFFFFF;
        }

        .card-feature:hover {
            border-color: var(--primary-color, #6A4C93);
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(106, 76, 147, 0.08);
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background-color: #FFF0F5;
            color: var(--primary-color, #6A4C93);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
        }

        .a11y-report-card {
            background-color: #FFFFFF;
            border: 2px solid var(--primary-color, #6A4C93);
            border-radius: 20px;
            padding: 2rem;
        }
    </style>

    <div class="container py-4">
        <!-- Hero Section -->
        <section class="hero-tentang text-center text-md-start">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge-a11y mb-3">
                        <i class="bi bi-universal-access" aria-hidden="true"></i> Dirancang Aksesibel (WCAG 2.2 AA)
                    </span>
                    <h1 class="display-5 fw-bold mb-3" style="color: var(--primary-color, #6A4C93);">
                        Mengenal Platform AKRAB
                    </h1>
                    <p class="lead text-secondary mb-0" style="line-height: 1.7;">
                        AKRAB adalah ruang belajar digital inklusif yang menyajikan edukasi kesehatan reproduksi dan seksual
                        bagi remaja secara aman, akurat, dan mudah dipahami, dengan perhatian khusus bagi remaja Tuli dan
                        berkebutuhan khusus.
                    </p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 8rem; color: var(--primary-color, #6A4C93);"
                        aria-hidden="true"></i>
                </div>
            </div>
        </section>

        <!-- Misi & Tujuan -->
        <section class="mb-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: var(--primary-color, #6A4C93);">Tujuan & Komitmen Kami</h2>
                <p class="text-secondary">Mengapa platform AKRAB hadir untuk teman-teman semua?</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-feature">
                        <div class="icon-box">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Informasi Ramah & Aman</h3>
                        <p class="text-secondary mb-0">
                            Menyediakan materi kesehatan reproduksi tanpa stigma, tabu, atau bahasa yang membingungkan.
                            Semua isi konten tersaring dan ilmiah.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-feature">
                        <div class="icon-box">
                            <i class="bi bi-eye-fill" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Penyampaian Visual</h3>
                        <p class="text-secondary mb-0">
                            Mengutamakan pemahaman visual melalui ilustrasi, rangkuman poin-poin jelas, serta struktur teks
                            yang ramah pembaca Tuli/Ramah Aksesibilitas.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-feature">
                        <div class="icon-box">
                            <i class="bi bi-people-fill" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Hak Edukasi Setara</h3>
                        <p class="text-secondary mb-0">
                            Memastikan setiap remaja memiliki hak yang sama dalam memahami tubuhnya, menjaga kesehatan, dan
                            membuat keputusan yang bertanggung jawab.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <hr class="my-5" style="border-top: 2px dashed #E0E0E0;">

        <!-- Standar Aksesibilitas (WCAG 2.2 AA) -->
        <section class="mb-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3" style="color: var(--primary-color, #6A4C93);">
                        Pernyataan Aksesibilitas
                    </h2>
                    <p class="text-secondary mb-3">
                        Kami berkomitmen untuk terus menjaga kelayakan platform ini agar dapat diakses oleh semua pengguna
                        tanpa hambatan teknis maupun visual.
                    </p>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span><strong>Kontras Warna Tinggi:</strong> Memenuhi rasio kontras standar WCAG AA agar nyaman
                                di mata.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span><strong>Navigasi Keyboard:</strong> Seluruh menu dan tombol dapat diakses sepenuhnya
                                menggunakan keyboard (tanpa tetikus).</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span><strong>Ramah Pembaca Layar (Screen Reader):</strong> Dilengkapi struktur HTML semantik
                                dan label ARIA yang jelas.</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 rounded-4" style="background-color: #FAF5FF; border: 1px solid #E9D5FF;">
                        <h3 class="h5 fw-bold text-dark mb-2">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>Catatan Pengembang
                        </h3>
                        <p class="small text-secondary mb-0">
                            Pengembangan platform AKRAB berpedoman pada standar Internasional W3C Web Content Accessibility
                            Guidelines (WCAG) 2.2 Level AA, dengan fokus perbaikan berkelanjutan berdasarkan masukan
                            langsung dari pengguna.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulir Laporan Kendala Aksesibilitas -->
        <section class="mb-5">
            <div class="a11y-report-card">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <h2 class="h3 fw-bold mb-2" style="color: var(--primary-color, #6A4C93);">
                            Laporan Kendala Aksesibilitas
                        </h2>
                        <p class="text-secondary mb-3">
                            Apakah kamu menemukan tulisan yang sulit dibaca, tombol yang sulit ditekan, atau fitur yang
                            tidak berfungsi dengan baik? Beritahu kami agar bisa segera diperbaiki!
                        </p>
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>Laporan Anda dapat dikirim secara anonim.</span>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        @if(session('a11y_success'))
                            <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                                <div>{{ session('a11y_success') }}</div>
                            </div>
                        @endif

                        <form action="#" method="POST" class="needs-validation">
                            @csrf
                            <div class="mb-3">
                                <label for="halaman_kendala" class="form-label fw-semibold">Halaman mana yang
                                    bermasalah?</label>
                                <input type="text" class="form-control form-control-lg" id="halaman_kendala" name="halaman"
                                    placeholder="Contoh: Halaman Kuis, Halaman Belajar Modul 1" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_kendala" class="form-label fw-semibold">Jelaskan kendala yang
                                    dialami</label>
                                <textarea class="form-control" id="deskripsi_kendala" name="deskripsi" rows="3"
                                    placeholder="Contoh: Warna tulisan kurang jelas, tombol tidak bisa diklik lewat keyboard..."
                                    required></textarea>
                            </div>

                            <button type="submit" class="btn btn-lg fw-bold text-white w-100"
                                style="background-color: var(--primary-color, #6A4C93); border-radius: 12px;">
                                <i class="bi bi-send-fill me-2" aria-hidden="true"></i> Kirim Laporan Aksesibilitas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection