@extends('layouts.app')

@section('title', 'Panduan Pendamping - AKRAB')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><a href="{{ route('bantuan') }}"><i class="bi bi-life-preserver" aria-hidden="true"></i> Bantuan</a>
                </li>
                <li><span class="current" aria-current="page">Panduan Pendamping</span></li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-5 text-center text-md-start">
            <span class="badge bg-purple-soft text-purple mb-2 px-3 py-2 rounded-pill fw-semibold"
                style="background-color: var(--bg-pink); color: var(--primary-color);">
                <i class="bi bi-people-fill me-1"></i> Khusus Orang Tua, Guru & Nakes
            </span>
            <h1 class="display-6 fw-bold text-dark mb-2">Panduan Pendamping Remaja</h1>
            <p class="text-muted fs-5">Strategi dan panduan komunikasi terbuka untuk mendampingi remaja, termasuk remaja
                Tuli, dalam memahami kesehatan reproduksi.</p>
        </div>

        <!-- Konten Panduan (Grid / Cards) -->
        <div class="row g-4 mb-5">
            <!-- Poin 1: Komunikasi Terbuka -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-small">
                            <i class="bi bi-chat-heart" aria-hidden="true"></i>
                        </div>
                        <h2 class="h5 fw-bold text-dark mb-0">Bangun Komunikasi Terbuka</h2>
                    </div>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        Hindari menggunakan bahasa yang menakut-nakuti atau menganggap tabu topik kesehatan reproduksi. Ajak
                        remaja berdiskusi dengan santai agar mereka merasa aman untuk bertanya saat menghadapi kebingungan
                        atau perubahan pada tubuhnya.
                    </p>
                </div>
            </div>

            <!-- Poin 2: Pendekatan Inklusif untuk Remaja Tuli -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-small">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </div>
                        <h2 class="h5 fw-bold text-dark mb-0">Pendekatan Visual & Isyarat</h2>
                    </div>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        Bagi remaja Tuli, pemahaman visual sangat memegang peranan penting. Gunakan media gambar, video
                        berbahasa isyarat, atau istilah anatomi tubuh yang tepat secara medis namun mudah dipahami secara
                        visual.
                    </p>
                </div>
            </div>

            <!-- Poin 3: Kenali Perubahan Pubertas -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-small">
                            <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                        </div>
                        <h2 class="h5 fw-bold text-dark mb-0">Pahami Fase Pubertas</h2>
                    </div>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        Dampingi mereka menghadapi perubahan fisik dan emosional yang terjadi pada masa pubertas (seperti
                        menstruasi atau mimpi basah). Pastikan mereka tahu bahwa perubahan tersebut adalah proses biologis
                        yang normal dan sehat.
                    </p>
                </div>
            </div>

            <!-- Poin 4: Protokol Perlindungan Diri -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-small">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                        </div>
                        <h2 class="h5 fw-bold text-dark mb-0">Edukasi Batasan & Proteksi</h2>
                    </div>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        Ajarkan konsep batasan tubuh pribadi, pentingnya persetujuan (*consent*), serta protokol keselamatan
                        <strong>TOLAK - LARI - LAPOR</strong> agar mereka memiliki kepekaan dan keberanian untuk melindungi
                        diri sendiri.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action / Unduh Modul -->
        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-light text-center">
            <h3 class="h4 fw-bold text-dark mb-2">Butuh Lembar Kerja atau Modul Cetak?</h3>
            <p class="text-muted small mb-4 mx-auto" style="max-width: 600px;">
                Anda dapat memanfaatkan materi-materi pembelajaran yang tersedia di platform AKRAB untuk mendampingi sesi
                belajar bersama remaja di rumah atau sekolah.
            </p>
            <div>
                <a href="{{ route('belajar') }}" class="btn btn-akrab-primary px-4 py-2">
                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Modul Pembelajaran
                </a>
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

        .icon-box-small {
            width: 44px;
            height: 44px;
            min-width: 44px;
            background-color: var(--bg-pink);
            color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endsection