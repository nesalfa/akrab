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
        <div class="mb-4">
            <h1 class="display-6 fw-bold mb-2" style="color: var(--primary-color);">Tanya Ahli</h1>
            <p class="fs-5" style="color: var(--text-light); max-width: 800px;">
                Punya pertanyaan seputar kesehatan reproduksi atau tumbuh kembang? Kirimkan di sini secara aman dan rahasia.
            </p>
        </div>

        <!-- Alert / Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-dismissible fade show rounded-4 mb-4 shadow-sm custom-alert-success" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Kirim Pertanyaan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white h-100">
                    <h2 class="h4 fw-bold mb-4" style="color: var(--text-dark);">
                        <i class="bi bi-envelope-paper-heart-fill me-2" style="color: var(--primary-color);"></i> Kirim
                        Pertanyaan Baru
                    </h2>

                    <form action="{{ route('tanya-ahli.store') }}" method="POST">
                        @csrf

                        @auth
                            <!-- Jika sudah login -->
                            <div class="rounded-3 mb-4 p-3 d-flex align-items-start gap-3"
                                style="background-color: var(--bg-pink); border: 1px solid #F5D9E4;">
                                <i class="bi bi-shield-check fs-4" style="color: var(--primary-color);"></i>
                                <div>
                                    <div class="fw-bold small" style="color: var(--primary-color);">Masuk sebagai
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="small" style="color: var(--text-light);">Pertanyaan Anda aman, rahasia, dan
                                        riwayat jawabannya dapat dipantau langsung di halaman ini.</div>
                                </div>
                            </div>
                            <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                        @else
                            <!-- Jika belum login -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold" style="color: var(--text-dark);">Nama / Nama
                                    Samaran <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control form-control-lg custom-input @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Bunga / Anonim"
                                    required>
                                <div class="form-text small mt-2" style="color: var(--text-light);">
                                    <i class="bi bi-incognito me-1"></i> Gunakan nama samaran jika ingin privasimu tetap
                                    terjaga.
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endauth

                        <div class="mb-4">
                            <label for="question" class="form-label fw-semibold" style="color: var(--text-dark);">Pertanyaan
                                Anda <span class="text-danger">*</span></label>
                            <textarea class="form-control custom-input @error('question') is-invalid @enderror"
                                id="question" name="question" rows="6"
                                placeholder="Tuliskan pertanyaan atau keluhan Anda dengan jelas di sini..."
                                required>{{ old('question') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-akrab-primary btn-lg py-3 fw-bold rounded-pill"
                                style="font-size: 1.05rem;">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pertanyaan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi & Daftar Tanya Jawab Pribadi -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white h-100 d-flex flex-column">
                    @auth
                        <h3 class="h5 fw-bold mb-4" style="color: var(--text-dark);">
                            <i class="bi bi-chat-left-text-fill me-2" style="color: var(--primary-color);"></i> Riwayat
                            Pertanyaan Saya
                        </h3>

                        @if(isset($myConsultations) && $myConsultations->count() > 0)
                            <div class="overflow-auto mb-3 custom-scrollbar pe-2" style="max-height: 400px;">
                                @foreach($myConsultations as $c)
                                    <div class="rounded-4 p-3 mb-3 border-0"
                                        style="background-color: {{ $c->status === 'answered' ? 'var(--bg-pink)' : '#FAFAFA' }}; border: 1px solid {{ $c->status === 'answered' ? '#F5D9E4' : '#EAEAEA' }} !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <!-- Badge Status Kustom -->
                                            @if($c->status === 'answered')
                                                <span class="badge rounded-pill px-3 py-2"
                                                    style="background-color: var(--primary-color); color: white;">
                                                    <i class="bi bi-check2-circle me-1"></i> Sudah Dijawab
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-3 py-2"
                                                    style="background-color: var(--accent-color); color: var(--text-dark);">
                                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                                </span>
                                            @endif
                                            <span class="small fw-semibold" style="color: var(--text-light); font-size: 0.8rem;">
                                                {{ $c->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <p class="fw-semibold small mb-3" style="color: var(--text-dark); line-height: 1.6;">
                                            "{{ $c->question }}"
                                        </p>

                                        @if($c->status === 'answered')
                                            <div class="p-3 rounded-3 bg-white shadow-sm border-0">
                                                <div class="small fw-bold mb-2" style="color: var(--primary-color);">
                                                    <i class="bi bi-person-fill-check me-1"></i> Jawaban Tenaga Ahli:
                                                </div>
                                                <p class="small mb-0" style="color: var(--text-light); line-height: 1.6;">{{ $c->answer }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 rounded-4 mb-3"
                                style="background-color: #FAFAFA; border: 1px dashed #D0D0D0;">
                                <i class="bi bi-chat-dots fs-1 d-block mb-3" style="color: #D0D0D0;"></i>
                                <span class="d-block fw-semibold text-muted">Belum ada riwayat pertanyaan.</span>
                                <span class="small text-muted">Pertanyaan yang Anda kirim akan muncul di sini.</span>
                            </div>
                        @endif
                    @else
                        <!-- State Belum Login -->
                        <h3 class="h5 fw-bold mb-4" style="color: var(--text-dark);">
                            <i class="bi bi-lightbulb-fill me-2" style="color: var(--accent-hover);"></i> Catatan Penting
                        </h3>
                        <div class="rounded-4 p-4 mb-4" style="background-color: #FAFAFA; border: 1px solid #EAEAEA;">
                            <ul class="m-0 ps-3 small" style="color: var(--text-light); line-height: 1.8;">
                                <li class="mb-2">Pertanyaan yang masuk akan dikurasi dan dijawab oleh <strong>tenaga ahli
                                        terverifikasi</strong>.</li>
                                <li class="mb-2">Masuk dengan akun untuk dapat melihat riwayat jawaban atas pertanyaan Anda
                                    secara pribadi.</li>
                                <li>Jaga kesopanan dan hindari mencantumkan data pribadi yang sensitif di dalam kotak
                                    pertanyaan.</li>
                            </ul>
                        </div>
                    @endauth

                    <!-- Boks Darurat -->
                    <div class="p-4 rounded-4 mt-auto border-0" style="background-color: var(--accent-color);">
                        <div class="fw-bold mb-2" style="color: var(--text-dark);">
                            <i class="bi bi-headset me-2"></i> Butuh Respon Darurat?
                        </div>
                        <p class="small mb-0" style="color: var(--text-dark); line-height: 1.6;">
                            Jika ini adalah situasi darurat atau kekerasan, segera gunakan menu <strong>Bantuan &
                                Rujukan</strong> untuk menghubungi layanan darurat resmi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- RUANG EDUKASI BERSAMA (Q&A PUBLIK / ANONIM)    -->
        <!-- ============================================== -->
        <div class="mt-5 pt-5 border-top">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 mb-3"
                    style="background-color: var(--bg-pink); color: var(--primary-color);">
                    <i class="bi bi-globe-asia-australia me-1"></i> Edukasi Publik
                </span>
                <h2 class="h3 fw-bold" style="color: var(--text-dark);">Ruang Edukasi Bersama</h2>
                <p style="color: var(--text-light); max-width: 600px; margin: 0 auto;">
                    Pertanyaan anonim dari teman-teman yang telah dijawab oleh ahli.
                </p>
                <p style="color: var(--text-light); max-width: 600px; margin: 0 auto;">
                    Mari belajar bersama!
                </p>
            </div>

            @if(isset($publicConsultations) && $publicConsultations->count() > 0)
                <div class="row g-4">
                    @foreach($publicConsultations as $faq)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 p-4"
                                style="background-color: #FAFAFA; border: 1px solid #EAEAEA !important;">
                                <!-- Header Penanya -->
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 40px; height: 40px; background-color: var(--accent-color); color: var(--text-dark);">
                                        <i class="bi bi-incognito fs-5"></i>
                                    </div>
                                    <div>
                                        <!-- Logika Sensor Nama (Ambil huruf pertama tiap kata, sisanya di-bintang) -->
                                        @php
                                            $words = explode(' ', $faq->name);
                                            $obfuscatedName = array_map(function ($word) {
                                                if (strlen($word) > 1) {
                                                    return substr($word, 0, 1) . str_repeat('*', strlen($word) - 1);
                                                }
                                                return $word;
                                            }, $words);
                                            $hiddenName = implode(' ', $obfuscatedName);
                                        @endphp

                                        <div class="fw-bold small" style="color: var(--text-dark); text-transform: capitalize;">
                                            {{ $hiddenName }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Dijawab
                                            {{ $faq->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <!-- Pertanyaan -->
                                <p class="fw-semibold mb-4" style="color: var(--text-dark); line-height: 1.6; font-size: 0.95rem;">
                                    "{{ $faq->question }}"
                                </p>

                                <!-- Jawaban Ahli -->
                                <div class="p-3 rounded-4 bg-white shadow-sm border-0 mt-auto">
                                    <div class="small fw-bold mb-2" style="color: var(--primary-color);">
                                        <i class="bi bi-person-fill-check me-1"></i> Jawaban Tenaga Ahli:
                                    </div>
                                    <p class="small mb-0" style="color: var(--text-light); line-height: 1.6;">
                                        {{ $faq->answer }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Kosong -->
                <div class="text-center py-5 rounded-4" style="background-color: #FAFAFA; border: 1px dashed #D0D0D0;">
                    <i class="bi bi-journal-text fs-1 d-block mb-3" style="color: #D0D0D0;"></i>
                    <span class="d-block fw-semibold text-muted">Belum ada diskusi publik.</span>
                    <span class="small text-muted">Pertanyaan anonim yang sudah dijawab akan muncul di sini.</span>
                </div>
            @endif
        </div>
        <!-- Akhir Ruang Edukasi Bersama -->

    </div>
@endsection

@section('additional_css')
    <style>
        /* Custom Input Fields */
        .custom-input {
            background-color: #FAFAFA;
            border: 2px solid #EAEAEA;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            background-color: #FFFFFF;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--bg-pink);
            outline: none;
        }

        /* Custom Alert Success (Theme Aligned) */
        .custom-alert-success {
            background-color: #E8F5EE;
            border: 1px solid #A8DCC0;
            color: #146C43;
            display: flex;
            align-items: center;
        }

        /* Custom Scrollbar for Riwayat */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #FAFAFA;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D0D0D0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Breadcrumb (Diperhalus) */
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
    </style>
@endsection