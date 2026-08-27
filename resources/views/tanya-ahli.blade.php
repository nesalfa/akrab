@extends('layouts.app')

@section('title', 'Tanya Ahli - AKRAB')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><a href="{{ route('bantuan') }}"><i class="bi bi-life-preserver" aria-hidden="true"></i> Bantuan</a>
                </li>
                <li><span class="current" aria-current="page">Tanya Ahli</span></li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-5">
            <h1 class="display-6 fw-bold text-dark mb-2">Tanya Ahli</h1>
            <p class="text-muted fs-5">Punya pertanyaan seputar kesehatan reproduksi atau tumbuh kembang? Kirimkan di sini
                secara aman dan rahasia.</p>
        </div>

        <!-- Alert / Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Kirim Pertanyaan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white h-100">
                    <h2 class="h4 fw-bold text-dark mb-3">Kirim Pertanyaan Baru</h2>

                    <form action="{{ route('tanya-ahli.store') }}" method="POST">
                        @csrf

                        @auth
                            <!-- Jika sudah login, informasikan bahwa identitas terikat ke akun secara aman -->
                            <div class="alert alert-info rounded-3 mb-3 small">
                                <i class="bi bi-shield-lock-fill me-1"></i> Masuk sebagai
                                <strong>{{ auth()->user()->name }}</strong>. Pertanyaan Anda aman dan riwayat jawabannya dapat
                                dipantau.
                            </div>
                            <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                        @else
                            <!-- Jika belum login, cukup minta Nama Samaran saja tanpa memaksa email -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-dark">Nama / Nama Samaran <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Bunga / Anonim"
                                    required>
                                <div class="form-text small">Gunakan nama samaran jika ingin privasimu tetap terjaga.</div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endauth

                        <div class="mb-4">
                            <label for="question" class="form-label fw-semibold text-dark">Pertanyaan Anda <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('question') is-invalid @enderror" id="question"
                                name="question" rows="5"
                                placeholder="Tuliskan pertanyaan atau konsultasi Anda secara jelas..."
                                required>{{ old('question') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-akrab-primary btn-lg py-3">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi & Daftar Tanya Jawab Publik -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 p-md-4 rounded-4 bg-white h-100">
                    <h3 class="h5 fw-bold text-dark mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>Catatan Penting
                    </h3>
                    <ul class="text-muted small ps-3 mb-4" style="line-height: 1.7;">
                        <li>Pertanyaan yang masuk akan dikurasi dan dijawab oleh tenaga ahli terverifikasi.</li>
                        <li>Jawaban yang bersifat umum akan dipublikasikan secara anonim agar bisa menjadi pembelajaran
                            bersama.</li>
                        <li>Jaga kesopanan dan hindari mencantumkan data pribadi yang sensitif di dalam kotak pertanyaan.
                        </li>
                    </ul>

                    <div class="p-3 rounded-3 bg-light mt-auto border">
                        <div class="fw-semibold text-dark mb-1 small"><i class="bi bi-headset text-primary me-1"></i> Butuh
                            Respon Cepat?</div>
                        <p class="text-muted small mb-0">Jika ini adalah situasi darurat, silakan gunakan menu
                            <strong>Bantuan & Rujukan</strong> untuk menghubungi layanan darurat.</p>
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