@extends('layouts.app')

@section('title', 'Bantuan & Rujukan - AKRAB')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><a href="{{ route('bantuan') }}"><i class="bi bi-life-preserver" aria-hidden="true"></i> Bantuan</a>
                </li>
                <li><span class="current" aria-current="page">Bantuan & Rujukan</span></li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-5 text-center text-md-start">
            <span class="badge mb-3 px-3 py-2"
                style="background-color: var(--bg-pink); color: var(--primary-color); border-radius: 20px; font-weight: 600;">
                <i class="bi bi-shield-fill-check me-1"></i> Ruang Aman AKRAB
            </span>
            <h1 class="display-6 fw-bold text-dark mb-2">Bantuan & Rujukan</h1>
            <p class="text-muted fs-5">Panduan langkah demi langkah saat kamu menghadapi situasi tidak nyaman atau darurat.
            </p>
        </div>

        <!-- Protokol Darurat: TOLAK - LARI - LAPOR -->
        <!-- GARIS UNGU DIHAPUS DARI SINI -->
        <div class="card border-0 shadow-sm p-4 p-md-5 mb-5 rounded-4 bg-white">
            <div class="text-center mb-5">
                <h2 class="h3 fw-bold text-dark mb-3">Protokol Keselamatan: <span style="color: var(--primary-color);">TOLAK
                        - LARI - LAPOR</span></h2>
                <p class="text-muted mx-auto" style="max-width: 700px; line-height: 1.6;">
                    Tubuhmu adalah milikmu. Jika kamu atau temanmu mengalami sentuhan, rayuan, atau perlakuan yang membuat
                    tidak nyaman dan tidak aman, segera lakukan 3 langkah ini:
                </p>
            </div>

            <div class="row g-4 position-relative">
                <!-- Langkah 1: TOLAK -->
                <div class="col-md-4">
                    <div class="card protocol-card h-100 border-0 text-center p-4">
                        <div class="protocol-icon mx-auto mb-3">
                            <i class="bi bi-sign-stop-fill"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-2">1. TOLAK</h3>
                        <p class="text-muted small mb-0">
                            Katakan <strong>"TIDAK!"</strong> dengan suara tegas dan tunjukkan gestur penolakan yang jelas.
                            Jangan takut untuk membela diri.
                        </p>
                    </div>
                </div>
                <!-- Langkah 2: LARI -->
                <div class="col-md-4">
                    <div class="card protocol-card h-100 border-0 text-center p-4">
                        <div class="protocol-icon mx-auto mb-3">
                            <i class="bi bi-person-walking"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-2">2. LARI</h3>
                        <p class="text-muted small mb-0">
                            Segera tinggalkan tempat atau orang tersebut. Menjauhlah secepat mungkin menuju tempat yang aman
                            atau ramai orang.
                        </p>
                    </div>
                </div>
                <!-- Langkah 3: LAPOR -->
                <div class="col-md-4">
                    <div class="card protocol-card h-100 border-0 text-center p-4">
                        <div class="protocol-icon mx-auto mb-3">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-2">3. LAPOR</h3>
                        <p class="text-muted small mb-0">
                            Ceritakan kejadian tersebut kepada orang dewasa yang kamu percaya (orang tua, guru, kakak, atau
                            tenaga kesehatan).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak & Layanan Rujukan -->
        <h2 class="h4 fw-bold text-dark mb-4"> Layanan Pengaduan & Darurat</h2>
        <div class="row g-4 mb-5">
            <!-- SAPPA 129 -->
            <div class="col-md-6">
                <div class="contact-card h-100 p-4 p-md-5 rounded-4 d-flex flex-column justify-content-between"
                    style="background-color: #FFF9E8; border: 1px solid #F5D98A;">
                    <div class="mb-4">
                        <!-- BUNGKUSAN FLEX UNTUK IKON & JUDUL BERSEBELAHAN -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm flex-shrink-0"
                                style="width: 50px; height: 50px; color: var(--primary-color); font-size: 1.5rem;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="h4 fw-bold text-dark mb-0">KEMENPPPA (SAPA 129)</h3>
                        </div>

                        <p class="text-muted mb-0">
                            Layanan Sahabat Perempuan dan Anak. Hubungi layanan ini untuk pelaporan kekerasan fisik, mental,
                            atau seksual. Privasi terjamin.
                        </p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="tel:129" class="btn btn-contact-outline flex-grow-1">
                                <i class="bi bi-telephone-fill me-1"></i> Telepon 129
                            </a>
                            <a href="https://wa.me/62811129129" target="_blank" class="btn btn-contact-outline flex-grow-1">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>
                        <a href="https://laporsapa129.kemenpppa.go.id/lapor" target="_blank"
                            class="btn btn-contact-outline w-100">
                            <i class="bi bi-globe me-1"></i> Website Pengaduan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Darurat 112 -->
            <div class="col-md-6">
                <div class="contact-card h-100 p-4 p-md-5 rounded-4 d-flex flex-column justify-content-between"
                    style="background-color: #FFF9E8; border: 1px solid #F5D98A;">
                    <div class="mb-4">
                        <!-- BUNGKUSAN FLEX UNTUK IKON & JUDUL BERSEBELAHAN -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm flex-shrink-0"
                                style="width: 50px; height: 50px; color: var(--primary-color); font-size: 1.5rem;">
                                <i class="bi bi-truck-front-fill"></i>
                            </div>
                            <h3 class="h4 fw-bold text-dark mb-0">Layanan Darurat 112</h3>
                        </div>

                        <p class="text-muted mb-0">
                            Nomor panggilan darurat nasional (bebas pulsa). Hubungi untuk respons cepat dari kepolisian,
                            ambulans, atau tim penyelamat.
                        </p>
                    </div>
                    <div>
                        <a href="tel:112" class="btn btn-contact-outline w-100 py-2">
                            <i class="bi bi-telephone-outbound-fill me-1"></i> Panggil 112 Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('additional_css')
    <style>
        /* MENGHILANGKAN BUG OUTLINE/BORDER KUNING SAAT DIKLIK */
        .card,
        .contact-card,
        .protocol-card,
        a,
        button {
            outline: none !important;
            -webkit-tap-highlight-color: transparent;
        }

        /* Desain Breadcrumb */
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
            padding: 0.4rem 1rem;
            border-radius: 50px;
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

        /* Desain Kartu Protokol */
        .protocol-card {
            background-color: #FAFAFA;
            border-radius: 20px !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            box-shadow: inset 0 0 0 1px #EFE7F3;
            cursor: default;
            /* Menghindari kursor pointer jika tidak ada link */
        }

        .protocol-card:hover {
            transform: translateY(-8px);
            background-color: #FFFFFF;
            box-shadow: 0 10px 30px rgba(106, 76, 147, 0.08), inset 0 0 0 2px var(--primary-color);
        }

        .protocol-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--bg-pink);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            transition: transform 0.3s ease;
        }

        .protocol-card:hover .protocol-icon {
            transform: scale(1.1);
            background-color: var(--primary-color);
            color: #FFFFFF;
        }

        /* Desain Kartu Kontak Hover */
        .contact-card {
            transition: transform 0.2s ease;
        }

        /* --- Desain Khusus Tombol Kontak --- */
        .btn-contact-outline {
            background-color: #FFFFFF;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 999px;
            padding: 0.65rem 1.4rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-contact-outline:hover,
        .btn-contact-outline:focus-visible {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
        }
    </style>
@endsection