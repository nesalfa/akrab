@extends('layouts.app')

@section('title', 'Cara Mencari Bantuan - AKRAB')

@section('content')
    <div class="container py-4" style="max-width: 900px;">

        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold mb-3" style="color: var(--primary-color);">Cara Mencari Bantuan</h1>
            <p class="fs-5" style="color: var(--text-light);">
                Mencari bantuan adalah tanda keberanian, bukan kelemahan. <br class="d-none d-md-block">
                <strong>Kamu tidak sendirian.</strong>
            </p>
        </div>

        <!-- Alert Darurat (Wajib Kontras Tinggi & Jelas) -->
        <div class="alert border-0 shadow-sm rounded-4 p-4 mb-5 d-flex gap-3 align-items-start"
            style="background-color: #FFE5E5; border-left: 6px solid #DC3545 !important;" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mt-1"></i>
            <div>
                <h2 class="h4 fw-bold text-danger mb-2">Penting!</h2>
                <p class="mb-0 fs-5 text-dark lh-base">
                    Jika kamu dalam bahaya langsung atau keadaan darurat, segera hubungi <strong>Polisi (110)</strong> atau
                    <strong>Ambulans (118/119)</strong>.
                </p>
            </div>
        </div>

        <!-- Langkah-Langkah Mencari Bantuan -->
        <section class="mb-5">
            <h2 class="h3 fw-bold text-dark mb-4 border-bottom pb-2" style="border-color: var(--bg-pink) !important;">
                Langkah-Langkah Mencari Bantuan
            </h2>

            <div class="row g-4">
                <!-- Langkah 1 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 step-card">
                        <div class="card-body p-4 position-relative">
                            <div class="step-number">1</div>
                            <h3 class="h5 fw-bold mt-4 pt-2 text-dark">Kenali Kapan Kamu Butuh Bantuan</h3>
                            <p class="text-secondary mb-0">Jika kamu merasa tidak aman, mengalami kekerasan, atau punya
                                pertanyaan kesehatan yang membuatmu khawatir, itu saatnya mencari bantuan.</p>
                        </div>
                    </div>
                </div>
                <!-- Langkah 2 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 step-card">
                        <div class="card-body p-4 position-relative">
                            <div class="step-number">2</div>
                            <h3 class="h5 fw-bold mt-4 pt-2 text-dark">Pilih Orang Dewasa yang Kamu Percaya</h3>
                            <p class="text-secondary mb-0">Bisa orang tua, guru, konselor sekolah, kerabat, atau tenaga
                                kesehatan. Pilih orang yang biasanya mendengarkan dan tidak menghakimi.</p>
                        </div>
                    </div>
                </div>
                <!-- Langkah 3 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 step-card">
                        <div class="card-body p-4 position-relative">
                            <div class="step-number">3</div>
                            <h3 class="h5 fw-bold mt-4 pt-2 text-dark">Ceritakan dengan Caramu</h3>
                            <p class="text-secondary mb-0">Kamu bisa berbicara, menulis, atau menggunakan bahasa isyarat.
                                Tidak perlu menceritakan semua detail sekaligus jika belum siap.</p>
                        </div>
                    </div>
                </div>
                <!-- Langkah 4 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 step-card">
                        <div class="card-body p-4 position-relative">
                            <div class="step-number">4</div>
                            <h3 class="h5 fw-bold mt-4 pt-2 text-dark">Jika Tidak Dibantu, Coba Lagi</h3>
                            <p class="text-secondary mb-0">Jika orang pertama tidak membantu, jangan menyerah. Coba
                                ceritakan pada orang dewasa lain atau hubungi layanan bantuan profesional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Terverifikasi -->
        <section class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2"
                style="border-color: var(--bg-pink) !important;">
                <h2 class="h3 fw-bold text-dark mb-0">Layanan Terverifikasi</h2>
                <span
                    class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> Sudah dicek tim AKRAB
                </span>
            </div>

            <div class="row g-4">
                <!-- SAPA 129 -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 contact-card">
                        <div class="card-body p-4">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                                <div>
                                    <span class="badge bg-danger-subtle text-danger mb-2">Kekerasan Seksual</span>
                                    <h3 class="h4 fw-bold text-dark mb-1">Kementerian PPPA - SAPA 129</h3>
                                </div>
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
                                </span>
                            </div>
                            <p class="text-secondary mb-4">Layanan pengaduan kekerasan terhadap perempuan dan anak, 24 jam.
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="tel:129" class="btn btn-contact px-4 py-2">
                                    <i class="bi bi-telephone-fill me-2"></i> 129
                                </a>
                                <a href="https://wa.me/628111129129" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-contact px-4 py-2">
                                    <i class="bi bi-whatsapp me-2"></i> +62 8111-129-129
                                </a>
                                <a href="#" class="btn btn-outline-contact px-4 py-2">
                                    <i class="bi bi-globe me-2"></i> Kunjungi website
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEJIWA -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 contact-card">
                        <div class="card-body p-4">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                                <div>
                                    <span class="badge bg-info-subtle text-info mb-2">Kesehatan Mental</span>
                                    <h3 class="h4 fw-bold text-dark mb-1">SEJIWA - Konseling Mental</h3>
                                </div>
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
                                </span>
                            </div>
                            <p class="text-secondary mb-4">Konseling sehat mental untuk semua usia.</p>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="tel:119" class="btn btn-contact px-4 py-2">
                                    <i class="bi bi-telephone-fill me-2"></i> 119 ext 8
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Checklist Motivasi (Ingat Selalu) -->
        <section class="card border-0 shadow-sm rounded-4 mb-5" style="background-color: var(--bg-pink);">
            <div class="card-body p-4 p-md-5">
                <h2 class="h4 fw-bold mb-4" style="color: var(--primary-color);">
                    <i class="bi bi-star-fill text-warning me-2"></i> Ingat Selalu:
                </h2>
                <ul class="list-unstyled fs-5 text-dark mb-0 d-flex flex-column gap-3 checklist-custom">
                    <li><i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i> <span>Mencari bantuan adalah tanda
                            keberanian</span></li>
                    <li><i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i> <span>Bukan salahmu jika ada
                            masalah</span></li>
                    <li><i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i> <span>Kamu berhak mendapat
                            bantuan</span></li>
                    <li><i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i> <span>Terus cari sampai ada yang
                            membantu</span></li>
                    <li><i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i> <span>Kamu tidak sendirian</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Tombol Kembali -->
        <div class="text-center pb-4">
            <a href="{{ route('belajar') }}" class="btn btn-akrab-primary btn-lg rounded-pill px-5 shadow-sm fw-bold">
                <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Materi Pembelajaran
            </a>
        </div>

    </div>

    <!-- Styles Khusus Halaman Bantuan -->
    <style>
        /* Menggunakan variabel warna global yang sudah ada */

        .step-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 2px solid transparent !important;
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(106, 76, 147, 0.08) !important;
        }

        .step-card:focus-within {
            border-color: var(--accent-color) !important;
        }

        /* Lingkaran Angka Langkah */
        .step-number {
            position: absolute;
            top: -15px;
            left: 20px;
            width: 45px;
            height: 45px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(106, 76, 147, 0.3);
        }

        .contact-card {
            border-left: 6px solid var(--primary-color) !important;
            transition: all 0.2s ease;
        }

        .contact-card:hover {
            background-color: #FAFAFA;
        }

        /* Tombol Kontak Kustom */
        .btn-contact {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-contact:hover,
        .btn-contact:focus {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-2px);
            outline: 3px solid var(--accent-color);
        }

        .btn-outline-contact {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-outline-contact:hover,
        .btn-outline-contact:focus {
            background-color: var(--bg-pink);
            color: var(--primary-hover);
            outline: 3px solid var(--accent-color);
        }

        /* List Checklist Custom */
        .checklist-custom li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-weight: 500;
        }

        /* Tombol Kembali (Menyamakan dengan desain sebelumnya) */
        .btn-akrab-primary {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border: none;
            transition: background 0.2s;
        }

        .btn-akrab-primary:hover,
        .btn-akrab-primary:focus {
            background-color: var(--primary-hover);
            color: #FFFFFF;
            outline: 3px solid var(--accent-color);
        }
    </style>
@endsection