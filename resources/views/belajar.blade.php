@extends('layouts.app')

@section('title', 'Materi Pembelajaran - AKRAB')

@section('content')
    <div class="container-fluid px-0">
        <!-- Header Utama Halaman -->
        <div class="mb-5">
            <h1 class="display-5 fw-bold text-dark mb-3">Materi Pembelajaran</h1>
            <p class="text-muted fs-5" style="max-width: 800px;">
                Pilih modul di bawah ini untuk mulai belajar. Setiap modul dirancang khusus dengan bahasa yang sederhana,
                aksesibel, dan mudah dipahami.
            </p>
        </div>

        <!-- Tata Letak Grid: Otomatis Membentuk 3 Kolom pada Layar Besar -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($modules as $module)
                <div class="col">
                    <a href="{{ route('module.show', $module->slug) }}"
                        class="learning-card h-100 p-4 d-flex flex-column text-decoration-none">
                        <!-- Baris Atas Card: Penomoran Modul Berwarna Ungu -->
                        <div class="mb-3">
                            <span class="badge rounded-pill px-3 py-1.5 fw-bold text-white shadow-sm animate-badge"
                                style="background-color: #6A4C93; font-size: 0.8rem;">
                                Modul {{ $module->order }}
                            </span>
                        </div>

                        <!-- Isi Konten Utama Card -->
                        <h3 class="h5 fw-bold mb-2 card-module-title">{{ $module->title }}</h3>
                        <p class="text-muted small mb-4 lh-base flex-grow-1">
                            {{ $module->description }}
                        </p>

                        <!-- Aksi Petunjuk Kaki Card (CTA Bawah) -->
                        <div class="d-flex align-items-center gap-2 fw-bold mt-auto cta-learn-text"
                            style="color: #6A4C93; font-size: 0.95rem;">
                            <i class="bi bi-book-half"></i> Mulai Belajar
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modifikasi Style Tampilan Sesuai Keselarasan Tema Ungu (#6A4C93) -->
    <style>
        .learning-card {
            border: 1px solid #EAEAEA;
            border-radius: 20px;
            transition: all 0.25s ease-in-out;
            background-color: #FFFFFF;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.01);
        }

        /* Hover State: Background berubah jadi Pink Lembut bawaan, border & teks menjadi Ungu */
        .learning-card:hover {
            background-color: var(--bg-pink) !important;
            border-color: #FBD5E5 !important;
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(106, 76, 147, 0.08);
        }

        /* Active & Focus State: Batasan Aksesibilitas WCAG Kuning Aksen */
        .learning-card:focus,
        .learning-card:active {
            outline: none !important;
            border: 2px solid var(--accent-color) !important;
        }

        .card-module-title {
            color: var(--text-dark);
            transition: color 0.2s ease;
        }

        .learning-card:hover .card-module-title {
            color: #6A4C93 !important;
        }

        .learning-card:hover .cta-learn-text {
            transform: translateX(4px);
            transition: transform 0.2s ease;
        }
    </style>
@endsection