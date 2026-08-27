@extends('layouts.app')

@section('title', 'Pusat Bantuan & Layanan - AKRAB')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><span class="current" aria-current="page">Bantuan</span></li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-6 fw-bold text-dark mb-2">Pusat Bantuan & Layanan</h1>
            <p class="text-muted fs-5 mx-auto" style="max-width: 650px;">
                Pilih jalur bantuan yang kamu butuhkan. Informasi, Tanya Ahli, atau Panduan khusus pendamping.
            </p>
        </div>

        <!-- 3 Kartu Pilihan Hub -->
        <div class="row g-4 justify-content-center">
            <!-- Kartu 1: Bantuan & Rujukan -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center hub-card">
                    <div class="card-body">
                        <div class="icon-box mx-auto mb-4">
                            <i class="bi bi-life-preserver" aria-hidden="true"></i>
                        </div>
                        <h2 class="h4 fw-bold text-dark mb-3">Bantuan & Rujukan</h2>
                        <p class="text-muted small mb-4" style="line-height: 1.6;">
                            Alur aman mencari pertolongan ke guru, orang tua, tenaga kesehatan, serta kontak darurat
                            perlindungan.
                        </p>
                        <a href="{{ route('rujukan') }}" class="btn btn-akrab-primary w-100">
                            Lihat Alur & Kontak <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Tanya Ahli -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center hub-card">
                    <div class="card-body">
                        <div class="icon-box mx-auto mb-4">
                            <i class="bi bi-chat-quote" aria-hidden="true"></i>
                        </div>
                        <h2 class="h4 fw-bold text-dark mb-3">Tanya Ahli</h2>
                        <p class="text-muted small mb-4" style="line-height: 1.6;">
                            Kirimkan pertanyaan pribadi secara aman dan rahasia, lalu dapatkan jawaban langsung dari sumber
                            yang valid.
                        </p>
                        <a href="{{ route('tanya-ahli') }}" class="btn btn-akrab-primary w-100">
                            Kirim Pertanyaan <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu 3: Panduan Pendamping -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center hub-card">
                    <div class="card-body">
                        <div class="icon-box mx-auto mb-4">
                            <i class="bi bi-people" aria-hidden="true"></i>
                        </div>
                        <h2 class="h4 fw-bold text-dark mb-3">Panduan Pendamping</h2>
                        <p class="text-muted small mb-4" style="line-height: 1.6;">
                            Informasi khusus dan modul panduan bagi orang tua, guru, serta tenaga kesehatan dalam
                            mendampingi remaja.
                        </p>
                        <a href="{{ route('pendamping') }}" class="btn btn-akrab-primary w-100">
                            Baca Panduan <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
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

        .hub-card {
            border-radius: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: #FFFFFF;
            border: 1px solid #EAEAEA !important;
        }

        .hub-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(106, 76, 147, 0.08) !important;
            border-color: var(--primary-color) !important;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            background-color: var(--bg-pink);
            color: var(--primary-color);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
    </style>
@endsection