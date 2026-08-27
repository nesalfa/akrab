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
        <div class="mb-5">
            <h1 class="display-6 fw-bold text-dark mb-2">Bantuan & Rujukan</h1>
            <p class="text-muted fs-5">Panduan langkah demi langkah saat menghadapi situasi tidak nyaman atau darurat.</p>
        </div>

        <!-- Protokol Darurat: TOLAK - LARI - LAPOR -->
        <div class="card border-0 shadow-sm p-4 p-md-5 mb-5 rounded-4 bg-white border-start border-4 border-primary">
            <h2 class="h3 fw-bold text-dark mb-3">Protokol Keselamatan: TOLAK - LARI - LAPOR</h2>
            <p class="text-muted mb-4" style="line-height: 1.7;">
                Jika kamu atau seseorang yang kamu kenal mengalami situasi sentuhan atau perlakuan yang tidak aman, ingat
                dan terapkan 3 langkah cepat ini:
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light h-100">
                        <div class="fw-bold text-primary mb-1 fs-5">1. TOLAK</div>
                        <p class="text-muted small mb-0">Katakan "TIDAK!" dengan suara tegas dan tunjukkan gestur penolakan
                            yang jelas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light h-100">
                        <div class="fw-bold text-primary mb-1 fs-5">2. LARI</div>
                        <p class="text-muted small mb-0">Segera tinggalkan tempat tersebut dan menjauh menuju tempat yang
                            aman atau ramai orang.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light h-100">
                        <div class="fw-bold text-primary mb-1 fs-5">3. LAPOR</div>
                        <p class="text-muted small mb-0">Ceritakan dan laporkan kejadian tersebut kepada orang dewasa yang
                            kamu percaya (orang tua, guru, atau tenaga kesehatan).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak & Layanan Rujukan -->
        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
            <h2 class="h3 fw-bold text-dark mb-4">Layanan Pengaduan & Kontak Darurat</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-4 border rounded-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 fw-bold text-dark mb-2"><i class="bi bi-shield-check text-primary me-2"></i>
                                KEMENPPPA (SAPPA 129)</h3>
                            <p class="text-muted small mb-3">Layanan sahabat perempuan dan anak untuk pengaduan kekerasan.
                            </p>
                        </div>
                        <div>
                            <a href="tel:129" class="btn btn-akrab-primary btn-sm">Telepon 129 / WhatsApp 0811-129-129</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 border rounded-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 fw-bold text-dark mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i>
                                Layanan Darurat Umum</h3>
                            <p class="text-muted small mb-3">Nomor darurat nasional untuk respons cepat kepolisian dan
                                medis.</p>
                        </div>
                        <div>
                            <a href="tel:112" class="btn btn-akrab-outline btn-sm">Telepon 112 (Bebas Pulsa)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_css')
    <style>
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
            padding: 0.5rem 1.1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
    </style>
@endsection