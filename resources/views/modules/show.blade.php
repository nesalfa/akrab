@extends('layouts.app')

@section('title', $module->title . ' - AKRAB')

@section('content')

    {{--
    =====================================================================
    CATATAN PERUBAHAN vs versi sebelumnya:

    1) Pesan Kunci sekarang 1 baris tipis ("Pesan Kunci: ..."), teks ungu
    di atas latar pink — bukan lagi kartu terpisah judul+isi berwarna
    hijau/biru muda.

    2) Media (video/infografis/dst) TIDAK diubah — tetap full-width
    per kartu seperti sebelumnya.

    3) Kuis sekarang 2 kolom: kuis di kiri (col-lg-8), sidebar di kanan
    (col-lg-4) isinya Progress Belajar + Glosarium (accordion,
    kembali ke gaya buka-tutup seperti versi awal).

    CATATAN JUJUR soal widget Progress: saya belum pernah lihat kode
    section progress di halaman Beranda, jadi tampilan di bawah ini
    ANGGAPAN saya (progress bar + "X dari Y modul selesai"), bukan
    replikasi persis. Kalau beda dari yang di Beranda, kirim kode
    section itu supaya saya samakan persis.

    CATATAN JUJUR soal data progress: aplikasi ini belum punya sistem
    akun/login yang menyimpan progres ke database (cek Module.php,
    ModuleController — tidak ada tabel/kolom untuk itu). Jadi progres
    di sini saya simpan di localStorage browser (per perangkat, bukan
    per akun). Ini SAMA seperti pendekatan yang saya pakai untuk
    checklist di Materi 15 sebelumnya. Kalau nanti progres perlu
    tersambung ke akun user sungguhan, itu perlu tabel baru
    (mis. quiz_attempts / user_progress) + sistem auth — belum saya
    buat karena belum diminta eksplisit.

    4) Kuis: klik pilihan A/B/C sekarang HANYA memilih (tidak ada info
    benar/salah/undefined muncul saat itu juga). Baru setelah klik
    "Ayo Kumpulkan!" DAN semua pertanyaan terisi: jawaban dikirim ke
    /api/quiz/submit, modal "Selamat..." muncul, lalu setiap pilihan
    ditandai benar/keliru (ikon + warna, tanpa angka skor atau
    keterangan lulus/tidak lulus), dan progres tersimpan.
    =====================================================================
    --}}
    <style>
        :root {
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            --accent-hover: #E5B534;
            --bg-pink: #FFF0F5;
            --text-dark: #1A1A1A;
            --text-light: #4A4A4A;
        }

        body {
            color: var(--text-dark);
        }

        /* ---------- Skip link ---------- */
        .skip-link {
            position: absolute;
            top: -3rem;
            left: 1rem;
            z-index: 1050;
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 0.65rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: top 0.15s ease-in-out;
        }

        .skip-link:focus {
            top: 1rem;
        }

        /* ---------- Breadcrumb pill ---------- */
        .breadcrumb-pill {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 0;
            margin: 0 0 1.25rem;
        }

        .breadcrumb-pill li {
            display: flex;
            align-items: center;
        }

        .breadcrumb-pill li:not(:last-child)::after {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #C9BFD4;
            margin-left: 0.5rem;
        }

        .breadcrumb-pill a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            border: 1.5px solid transparent;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .breadcrumb-pill a:hover {
            background-color: var(--bg-pink);
            border-color: var(--primary-color);
        }

        .breadcrumb-pill .current {
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            background-color: var(--bg-pink);
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* ---------- Header modul ---------- */
        .module-header-card {
            background-color: var(--primary-color);
            border-radius: 28px;
        }

        .module-header-card p {
            color: var(--bg-pink);
        }

        /* ---------- Alur konten utama ---------- */
        .content-stream>*+* {
            margin-top: 1.5rem;
        }

        .content-card {
            border: 1px solid #EFE7F3;
            border-radius: 22px;
            background-color: #FFFFFF;
        }

        .content-card .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--primary-color);
            border-bottom: 2px solid var(--bg-pink);
            padding-bottom: 0.6rem;
        }

        .content-card .section-title i {
            font-size: 1.1em;
        }

        /* ---------- Pesan Kunci — 1 baris tipis, ungu di atas pink ---------- */
        .pesan-kunci-bar {
            background-color: var(--bg-pink);
            border: 1px solid #F5D9E4;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .pesan-kunci-bar i {
            color: var(--primary-color);
            font-size: 1.3rem;
            margin-top: 0.15rem;
            flex-shrink: 0;
        }

        .pesan-kunci-bar p {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
            line-height: 1.6;
        }

        /* ---------- Ajakan Pre-Test ---------- */
        .pretest-invite {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            background-color: #FFF9E8;
            border: 1px dashed var(--accent-hover);
            border-radius: 16px;
            padding: 1rem 1.25rem;
        }

        .pretest-invite>i {
            font-size: 1.4rem;
            color: var(--accent-hover);
            margin-top: 0.15rem;
            flex-shrink: 0;
        }

        .pretest-invite h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .pretest-invite p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.92rem;
        }

        /* ---------- Kuis ---------- */
        .quiz-card {
            border: 1px solid #EFE7F3;
            border-left: 6px solid var(--primary-color);
            border-radius: 18px;
            background-color: #FCFAFD;
            transition: border-color 0.15s ease;
        }

        .quiz-card.quiz-card-missing {
            border-left-color: #C7365F;
        }

        .quiz-legend {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.05rem;
            padding: 0;
            margin-bottom: 0.9rem;
            width: 100%;
        }

        .quiz-option-label {
            border: 2px solid #E4DCE9;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: border-color 0.15s ease, background-color 0.15s ease;
            color: var(--text-light);
            font-weight: 500;
            background-color: #FFFFFF;
        }

        .quiz-option-label:hover {
            border-color: var(--primary-color);
            background-color: var(--bg-pink);
        }

        .quiz-option-input {
            width: 1.15rem;
            height: 1.15rem;
            accent-color: var(--primary-color);
            flex-shrink: 0;
        }

        .quiz-option-input:checked~.quiz-option-text {
            color: var(--primary-color);
            font-weight: 700;
        }

        .quiz-option-input:disabled {
            cursor: default;
        }

        .quiz-review-icon {
            display: none;
            margin-left: auto;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .quiz-option-label.is-review-correct {
            border-color: #34A868;
            background-color: #E8F5EE;
        }

        .quiz-option-label.is-review-correct .quiz-review-icon {
            display: inline-block;
            color: #146C43;
        }

        .quiz-option-label.is-review-wrong {
            border-color: #C7365F;
            background-color: #FBEAF0;
        }

        .quiz-option-label.is-review-wrong .quiz-review-icon {
            display: inline-block;
            color: #B02A5B;
        }

        .quiz-feedback {
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .quiz-feedback.is-correct {
            background-color: #E8F5EE;
            color: #146C43;
            border: 1.5px solid #A8DCC0;
        }

        .quiz-feedback.is-warning {
            background-color: #FFF6E0;
            color: #8A6116;
            border: 1.5px solid #F5D98A;
        }

        /* ---------- Sidebar: Progress & Glosarium ---------- */
        .sidebar-card {
            border: 1px solid #EFE7F3;
            border-radius: 22px;
            background-color: #FFFFFF;
            overflow: hidden;
        }

        .sidebar-card-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 1rem 1.25rem;
            font-weight: 700;
            font-size: 1rem;
        }

        .progress-widget-body {
            padding: 1.25rem;
        }

        .progress-akrab {
            height: 10px;
            border-radius: 999px;
            background-color: #EFE7F3;
            overflow: hidden;
        }

        .progress-akrab-fill {
            height: 100%;
            border-radius: 999px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .accordion-button-custom {
            background-color: #FFFFFF;
            color: var(--text-dark);
            font-weight: 600;
            min-height: 44px;
        }

        .accordion-button-custom:not(.collapsed) {
            background-color: var(--bg-pink);
            color: var(--primary-color);
            box-shadow: none;
        }

        /* ---------- Ruang Aman AKRAB ---------- */
        .help-banner {
            background-color: var(--bg-pink);
            border: 1px solid #F5D9E4;
            border-left: 6px solid var(--primary-color);
            border-radius: 22px;
            padding: 1.5rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .help-banner .help-icon {
            flex-shrink: 0;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--primary-color);
        }

        .help-banner .help-text {
            flex: 1 1 260px;
        }

        .help-banner .help-text h2 {
            color: var(--primary-color);
            font-size: 1.15rem;
            margin-bottom: 0.25rem;
        }

        .help-banner .help-text p {
            color: var(--text-light);
            margin-bottom: 0;
            font-size: 0.92rem;
        }

        .help-banner .help-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            flex-shrink: 0;
        }

        /* ---------- FAQ ---------- */
        .faq-section {
            border: 1px solid #EFE7F3;
            border-radius: 22px;
            background-color: #FFFFFF;
            overflow: hidden;
        }

        /* ---------- Tombol ---------- */
        .btn-akrab-primary,
        .btn-akrab-outline,
        .btn-akrab-accent,
        .btn-akrab-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 46px;
            font-weight: 700;
            padding: 0.65rem 1.4rem;
            border-radius: 999px;
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            text-decoration: none;
            font-size: 0.95rem;
            border: 2px solid transparent;
        }

        .btn-akrab-primary {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
        }

        .btn-akrab-primary:hover,
        .btn-akrab-primary:focus-visible {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #FFFFFF;
        }

        .btn-akrab-outline {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-akrab-outline:hover,
        .btn-akrab-outline:focus-visible {
            background-color: var(--bg-pink);
            color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-akrab-accent {
            background-color: var(--accent-color);
            color: var(--text-dark);
            border-color: var(--accent-color);
        }

        .btn-akrab-accent:hover,
        .btn-akrab-accent:focus-visible {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: var(--text-dark);
        }

        .btn-akrab-danger {
            background-color: #C7365F;
            color: #FFFFFF;
            border-color: #C7365F;
        }

        .btn-akrab-danger:hover,
        .btn-akrab-danger:focus-visible {
            background-color: #A32A4C;
            border-color: #A32A4C;
            color: #FFFFFF;
        }

        .btn-akrab-primary:disabled,
        .btn-akrab-outline:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        @media (max-width: 575.98px) {

            .btn-akrab-primary,
            .btn-akrab-outline,
            .btn-akrab-accent,
            .btn-akrab-danger {
                width: 100%;
            }

            .help-banner {
                flex-direction: column;
                align-items: flex-start;
            }

            .help-banner .help-actions {
                width: 100%;
            }

            .help-banner .help-actions .btn-akrab-accent,
            .help-banner .help-actions .btn-akrab-danger {
                flex: 1 1 auto;
            }
        }
    </style>

    <div>
        {{-- ================= Breadcrumb (tidak diubah) ================= --}}
        <nav aria-label="Jalur navigasi">
            <ol class="breadcrumb-pill">
                <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda</a></li>
                <li><a href="{{ route('belajar') }}"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i>
                        Materi</a></li>
                <li><span class="current" aria-current="page">{{ $module->title }}</span></li>
            </ol>
        </nav>

        {{-- ================= Header Modul (tidak diubah) ================= --}}
        <div class="card module-header-card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">
                <h1 class="display-6 fw-bold text-white mb-2">{{ $module->title }}</h1>
                <p class="fs-5 mb-0" style="max-width: 750px;">{{ $module->description }}</p>
            </div>
        </div>

        <div class="content-stream">
            @php
                $pesanKunci = $contents->firstWhere('type', 'pesan_kunci');
                // Mengecualikan pesan_kunci, subtitle, dan transkrip agar tidak tampil sebagai kartu terpisah
                $mediaContents = $contents->whereNotIn('type', ['pesan_kunci', 'subtitle', 'transkrip']);
            @endphp

            {{-- ================= Pesan Kunci (inline, tidak diubah) ================= --}}
            @if($pesanKunci)
                <div class="pesan-kunci-bar">
                    <i class="bi bi-lightbulb-fill" aria-hidden="true"></i>
                    <p><strong>Pesan Kunci:</strong> {{ $pesanKunci->content }}</p>
                </div>
            @endif

            {{--
            ================= Pre-Test =================
            Soal SAMA PERSIS dengan Post-Test di bawah (pakai $quizzes yang
            sama) — makanya field `name` pada radio-nya sengaja diberi
            prefix "pretest_" supaya pilihan di Pre-Test dan Post-Test
            tersimpan terpisah di DOM (radio group tidak bentrok satu sama
            lain), walau option-id di database-nya identik.

            KEPUTUSAN PENTING yang saya ambil (tolong dikoreksi kalau tidak
            sesuai maksud tim): Pre-Test SENGAJA TIDAK menampilkan mana
            jawaban benar/salah, tidak ada modal, dan tidak menghitung
            progres. Alasannya: kalau Pre-Test langsung membocorkan
            jawaban benar, Post-Test di bawah (soal yang sama persis) jadi
            tidak lagi mengukur pemahaman asli setelah belajar — orang
            tinggal ingat jawaban dari Pre-Test. Pre-Test di sini murni
            mengumpulkan jawaban awal, lalu mengarahkan ke materi.
            --}}
            @if($quizzes->count() > 0)
                <div class="pretest-invite">
                    <i class="bi bi-rocket-takeoff-fill" aria-hidden="true"></i>
                    <div>
                        <h2>Sebelum Mulai, Yuk Cek Dulu!</h2>
                        <p>Coba jawab semampunya. Ini bukan ujian — cuma buat lihat sejauh mana kamu sudah tahu soal materi ini
                            sebelum belajar.</p>
                    </div>
                </div>

                <section class="card content-card border-0 shadow-sm" id="pretest-section" aria-labelledby="pretest-heading">
                    <div class="card-body p-4">
                        <span class="badge mb-2 px-3 py-2"
                            style="background-color: var(--bg-pink); color: var(--primary-color); border-radius: 20px; font-weight: 600;">
                            <i class="bi bi-1-circle-fill me-1" aria-hidden="true"></i> Pre-Test
                        </span>
                        <h2 id="pretest-heading" class="h4 fw-bold mb-2 section-title"
                            style="border-bottom: none; padding-bottom: 0;">
                            <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> Cek Pengetahuan Awal
                        </h2>
                        <p class="mb-4" style="color: var(--text-light);">
                            Pilih satu jawaban untuk tiap pertanyaan, lalu klik <strong>Ayo Kumpulkan!</strong>
                        </p>

                        <div id="pretest-alert" class="quiz-feedback is-warning d-none mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                            <span>Masih ada pertanyaan yang belum dijawab. Yuk lengkapi dulu semuanya!</span>
                        </div>

                        @foreach($quizzes as $index => $quiz)
                            @php
                                $preAttempt = $preAttempts->firstWhere('quiz_id', $quiz->id);
                                $preAnswerId = $preAttempt ? $preAttempt->selected_option_id : null;
                            @endphp
                            <div class="card quiz-card border-0 mb-4" data-quiz-card>
                                <div class="card-body p-4">
                                    <fieldset>
                                        <legend class="quiz-legend">
                                            Pertanyaan {{ $index + 1 }}: {{ $quiz->question }}
                                        </legend>

                                        <div class="quiz-options d-flex flex-column gap-2" data-quiz-id="{{ $quiz->id }}">
                                            @foreach($quiz->options as $option)
                                                <label class="quiz-option-label">
                                                    <input type="radio" name="pretest_quiz_{{ $quiz->id }}" value="{{ $option->id }}"
                                                        class="quiz-option-input" data-option-id="{{ $option->id }}" {{ $preAnswerId == $option->id ? 'checked' : '' }} {{ $preAttempt ? 'disabled' : '' }}>
                                                    <span class="quiz-option-text">
                                                        <strong>{{ $option->label }}.</strong> {{ $option->text }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        @endforeach

                        {{-- Jika belum ada riwayat jawaban pre-test --}}
                        @if($preAttempts->isEmpty())
                            <div class="text-center mt-2">
                                <button type="button" id="btn-submit-pretest" class="btn-akrab-primary">
                                    <i class="bi bi-send-check-fill" aria-hidden="true"></i> Ayo Kumpulkan!
                                </button>
                            </div>

                            {{-- Elemen pesan sukses (awalnya disembunyikan, JS akan menampilkannya) --}}
                            <div id="pretest-done-note" class="mt-4 d-none" aria-live="polite" role="status">
                                <div class="quiz-feedback is-correct">
                                    <i class="bi bi-arrow-down-circle-fill" aria-hidden="true"></i>
                                    <span>Sip, jawabanmu sudah tercatat! Yuk lanjut pelajari materinya.</span>
                                </div>
                            </div>
                        @else
                            {{-- Jika riwayat sudah ada, langsung tampilkan pesan tanpa tombol --}}
                            <div class="mt-4" aria-live="polite" role="status">
                                <div class="quiz-feedback is-correct">
                                    <i class="bi bi-arrow-down-circle-fill" aria-hidden="true"></i>
                                    <span>Sip, jawabanmu sudah tercatat! Yuk lanjut pelajari materinya.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endif

            {{-- ================= Media (tidak diubah, cuma sumbernya sekarang $mediaContents) ================= --}}
            @forelse($mediaContents as $content)
                <article class="card content-card border-0 shadow-sm" id="section-{{ $content->id }}">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4 section-title">
                            <i class="bi {{ $content->sectionIcon() }}" aria-hidden="true"></i>
                            {{ $content->sectionLabel() }}
                        </h2>

                        @php $partial = $content->partialView(); @endphp

                        @if($partial)
                            @include($partial, ['content' => $content])
                        @else
                            <div class="alert alert-light border rounded-3 small mb-0">
                                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                                Tipe konten <code>{{ $content->type }}</code> belum punya tampilan khusus.
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                @if(!$pesanKunci)
                    <div class="alert alert-light border p-4 text-center rounded-4">
                        Belum ada konten untuk modul ini.
                    </div>
                @endif
            @endforelse

            {{-- ================= Post-Test + Sidebar (Progress & Glosarium) ================= --}}
            @if($quizzes->count() > 0)
                <div class="row g-4">
                    <div class="col-lg-8">
                        <section class="card content-card border-0 shadow-sm h-100" id="quizzes-section"
                            aria-labelledby="quizzes-heading">
                            <div class="card-body p-4">
                                <span class="badge mb-2 px-3 py-2"
                                    style="background-color: var(--bg-pink); color: var(--primary-color); border-radius: 20px; font-weight: 600;">
                                    <i class="bi bi-2-circle-fill me-1" aria-hidden="true"></i> Post-Test
                                </span>
                                <h2 id="quizzes-heading" class="h4 fw-bold mb-2 section-title"
                                    style="border-bottom: none; padding-bottom: 0;">
                                    <i class="bi bi-clipboard2-check-fill" aria-hidden="true"></i> Uji Pemahamanmu Sekarang
                                </h2>
                                <p class="mb-4" style="color: var(--text-light);">
                                    Soalnya sama seperti Pre-Test di atas — sekarang setelah belajar, coba jawab lagi.
                                    Pilih satu jawaban untuk tiap pertanyaan, lalu klik <strong>Ayo Kumpulkan!</strong> di
                                    bagian bawah.
                                </p>

                                <div id="quiz-alert" class="quiz-feedback is-warning d-none mb-3" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                                    <span>Masih ada pertanyaan yang belum dijawab. Yuk lengkapi dulu semuanya!</span>
                                </div>

                                @foreach($quizzes as $index => $quiz)
                                    @php
                                        $hasPostAttempt = $postAttempts->has($quiz->id);
                                        $postAnswerId = $hasPostAttempt ? $postAttempts[$quiz->id]->selected_option_id : null;
                                        $isCorrect = $hasPostAttempt ? $postAttempts[$quiz->id]->is_correct : false;
                                        $correctOptionId = $quiz->options->where('is_correct', true)->first()->id ?? null;
                                    @endphp
                                    <div class="card quiz-card border-0 mb-4" data-quiz-card>
                                        <div class="card-body p-4">
                                            <fieldset>
                                                <legend class="quiz-legend">
                                                    Pertanyaan {{ $index + 1 }}: {{ $quiz->question }}
                                                </legend>

                                                <div class="quiz-options d-flex flex-column gap-2" data-quiz-id="{{ $quiz->id }}">
                                                    @foreach($quiz->options as $option)
                                                        @php
                                                            $labelClass = '';
                                                            $iconClass = 'd-none';

                                                            // Logika pewarnaan riwayat (jika sudah dikerjakan)
                                                            if ($hasPostAttempt) {
                                                                if ($option->id == $correctOptionId) {
                                                                    $labelClass = 'is-review-correct';
                                                                    $iconClass = 'bi-check-circle-fill text-success';
                                                                } elseif ($option->id == $postAnswerId && !$isCorrect) {
                                                                    $labelClass = 'is-review-wrong';
                                                                    $iconClass = 'bi-x-circle-fill text-danger';
                                                                }
                                                            }
                                                        @endphp
                                                        <label class="quiz-option-label {{ $labelClass }}"
                                                            data-option-id="{{ $option->id }}">
                                                            <input type="radio" name="quiz_{{ $quiz->id }}" value="{{ $option->id }}"
                                                                class="quiz-option-input" data-option-id="{{ $option->id }}" {{ $postAnswerId == $option->id ? 'checked' : '' }} {{ $hasPostAttempt ? 'disabled' : '' }}>
                                                            <span class="quiz-option-text">
                                                                <strong>{{ $option->label }}.</strong> {{ $option->text }}
                                                            </span>
                                                            <i class="bi quiz-review-icon {{ $iconClass }}"
                                                                style="{{ $hasPostAttempt ? 'display:inline-block;' : '' }}"
                                                                aria-hidden="true"></i>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Jika belum ada riwayat jawaban post-test, tampilkan tombol submit --}}
                                @if($postAttempts->isEmpty())
                                    <div class="text-center mt-2">
                                        <button type="button" id="btn-submit-quiz" class="btn-akrab-primary">
                                            <i class="bi bi-send-check-fill" aria-hidden="true"></i> Ayo Kumpulkan!
                                        </button>
                                    </div>
                                @endif

                                {{-- Jika sudah ada riwayat post-test, pastikan catatan review ini langsung muncul --}}
                                <div id="quiz-review-note" class="mt-4 {{ $postAttempts->isNotEmpty() ? '' : 'd-none' }}"
                                    aria-live="polite" role="status">
                                    <div class="quiz-feedback is-correct">
                                        <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                        <span>
                                            Selamat! Modul ini sudah selesai dipelajari.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <aside class="col-lg-4">
                        {{--
                        Widget Progress — sekarang sumber datanya dari
                        ModuleController::show() ($totalModules,
                        $completedModulesCount), yang diambil dari kolom
                        users.completed_modules. Render awal (saat
                        halaman pertama dimuat) sudah langsung benar dari
                        server; JS di bawah cuma meng-update angka ini
                        SETELAH user menuntaskan Post-Test, tanpa reload
                        halaman.
                        --}}
                        @php
                            $progressPct = $totalModules > 0 ? (int) round(($completedModulesCount / $totalModules) * 100) : 0;
                        @endphp
                        <div class="card sidebar-card border-0 shadow-sm mb-4" id="progress-widget"
                            data-total="{{ $totalModules }}" data-completed="{{ $completedModulesCount }}">
                            <div class="sidebar-card-header">
                                <i class="bi bi-bar-chart-line-fill" aria-hidden="true"></i> Progress Belajarmu
                            </div>
                            <div class="progress-widget-body">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <span id="progress-label" class="small" style="color: var(--text-light);">
                                        {{ $completedModulesCount }} dari {{ $totalModules }} modul selesai
                                    </span>
                                    <span id="progress-percent" class="fw-bold"
                                        style="color: var(--primary-color);">{{ $progressPct }}%</span>
                                </div>
                                <div class="progress-akrab" role="progressbar" id="progress-bar-track"
                                    aria-label="Progress belajar keseluruhan" aria-valuenow="{{ $progressPct }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-akrab-fill" id="progress-bar-fill" style="width: {{ $progressPct }}%;">
                                    </div>
                                </div>
                                <p class="small mt-3 mb-0" style="color: var(--text-light);">
                                    <i class="bi bi-cloud-check-fill" aria-hidden="true"></i>
                                    Progres tersimpan ke akunmu.
                                </p>
                            </div>
                        </div>

                        @if($glossary->count() > 0)
                            <div class="card sidebar-card border-0 shadow-sm">
                                <div class="sidebar-card-header">
                                    <i class="bi bi-book-half" aria-hidden="true"></i> Kamus Kata (Glosarium)
                                </div>
                                <div class="card-body p-0">
                                    <div class="accordion accordion-flush" id="glossaryAccordion">
                                        @foreach($glossary as $term)
                                            <div class="accordion-item border-bottom">
                                                <h3 class="accordion-header">
                                                    <button class="accordion-button accordion-button-custom collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#glossary-{{ $term->id }}"
                                                        aria-expanded="false">
                                                        {{ $term->term }}
                                                    </button>
                                                </h3>
                                                <div id="glossary-{{ $term->id }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#glossaryAccordion">
                                                    <div class="accordion-body small py-3 px-3" style="color: var(--text-light);">
                                                        <p class="mb-0">{{ $term->definition }}</p>
                                                        @if($term->example ?? null)
                                                            <p class="mb-0 mt-2 fst-italic">Contoh: {{ $term->example }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </aside>
                </div>
            @endif

            {{-- ================= FAQ ================= --}}
            @if(($faq ?? collect())->count() > 0)
                <section class="faq-section" aria-labelledby="faq-heading">
                    <h2 id="faq-heading" class="sidebar-card-header" style="border-radius: 0; margin: 0;">
                        <i class="bi bi-patch-question-fill" aria-hidden="true"></i> Pertanyaan Umum
                    </h2>
                    <div class="accordion accordion-flush" id="faqAccordion">
                        @foreach($faq as $item)
                            <div class="accordion-item border-bottom">
                                <h3 class="accordion-header">
                                    <button class="accordion-button accordion-button-custom collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq-{{ $item->id }}" aria-expanded="false">
                                        {{ $item->question }}
                                    </button>
                                </h3>
                                <div id="faq-{{ $item->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small py-3 px-4" style="color: var(--text-light);">
                                        {{ $item->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ================= Navigasi Modul ================= --}}
            <nav aria-label="Navigasi antar modul" class="card content-card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                        @if($prevModule)
                            <a href="{{ route('module.show', $prevModule->slug) }}" class="btn-akrab-outline">
                                <i class="bi bi-arrow-left" aria-hidden="true"></i> {{ $prevModule->title }}
                            </a>
                        @else
                            <button class="btn-akrab-outline" disabled>
                                <i class="bi bi-arrow-left" aria-hidden="true"></i> Modul Awal
                            </button>
                        @endif

                        @if($nextModule)
                            <a href="{{ route('module.show', $nextModule->slug) }}" class="btn-akrab-primary">
                                {{ $nextModule->title }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <button class="btn-akrab-accent" disabled>
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Materi Selesai!
                            </button>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    </div>

    {{-- ================= Modal Sukses Kuis ================= --}}
    <div class="modal fade" id="quizSuccessModal" tabindex="-1" aria-labelledby="quizSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 22px; border: none;">
                <div class="modal-body text-center p-4 p-md-5">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 72px; height: 72px; border-radius: 50%; background-color: var(--bg-pink);">
                        <i class="bi bi-trophy-fill" style="font-size: 2rem; color: var(--primary-color);"
                            aria-hidden="true"></i>
                    </div>
                    <h2 class="h4 fw-bold mb-2" style="color: var(--primary-color);" id="quizSuccessModalLabel">Selamat!
                    </h2>
                    <p id="quizSuccessModalBody" class="mb-4" style="color: var(--text-light);"></p>
                    <button type="button" class="btn-akrab-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg" aria-hidden="true"></i> Oke, Mengerti!
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Skrip Kuis ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            // ---------- PRE-TEST ----------
            // Jawaban tetap DIKIRIM ke server (untuk data riset pre/post-test —
            // lihat migration quiz_attempts), tapi hasilnya SENGAJA tidak
            // dipakai untuk apa pun di UI: tidak ada reveal benar/salah, tidak
            // ada modal, tidak menyentuh progres. Lihat catatan panjang di
            // komentar Blade dekat section Pre-Test soal alasannya.
            const pretestBtn = document.getElementById('btn-submit-pretest');
            if (pretestBtn) {
                const pretestSection = document.getElementById('pretest-section');
                const pretestAlert = document.getElementById('pretest-alert');
                const pretestDoneNote = document.getElementById('pretest-done-note');

                pretestBtn.addEventListener('click', async function () {
                    if (pretestBtn.disabled) return; // Cegah double klik

                    const groups = pretestSection.querySelectorAll('.quiz-options');
                    let allAnswered = true;
                    const answers = [];

                    groups.forEach((group) => {
                        const card = group.closest('.quiz-card');
                        const checked = group.querySelector('input[type="radio"]:checked');
                        if (!checked) {
                            allAnswered = false;
                            card.classList.add('quiz-card-missing');
                        } else {
                            card.classList.remove('quiz-card-missing');
                            answers.push({
                                quizId: group.dataset.quizId,
                                optionId: checked.dataset.optionId
                            });
                        }
                    });

                    if (!allAnswered) {
                        pretestAlert.classList.remove('d-none');
                        pretestAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    pretestAlert.classList.add('d-none');
                    pretestBtn.disabled = true;
                    pretestBtn.innerHTML = '<i class="bi bi-hourglass-split" aria-hidden="true"></i> Menyimpan...';

                    // Kunci pilihan
                    groups.forEach((group) => {
                        group.querySelectorAll('input[type="radio"]').forEach((input) => {
                            input.disabled = true;
                        });
                    });

                    try {
                        // Kirim data dan paksa JS melempar error jika server menolak (error 500)
                        await Promise.all(answers.map((a) =>
                            fetch('/api/quiz/submit', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    quiz_id: a.quizId,
                                    selected_option_id: a.optionId,
                                    type: 'pre',
                                }),
                            }).then(res => {
                                if (!res.ok) throw new Error('Server menolak request');
                                return res.json();
                            })
                        ));

                        // Sukses! Hilangkan tombol dan munculkan tulisan
                        pretestBtn.classList.add('d-none');
                        pretestDoneNote.classList.remove('d-none');

                        const nextEl = pretestSection.nextElementSibling;
                        if (nextEl) {
                            nextEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    } catch (err) {
                        console.error('Gagal menyimpan Pre-Test:', err);
                        pretestBtn.disabled = false;
                        pretestBtn.innerHTML = '<i class="bi bi-send-check-fill" aria-hidden="true"></i> Ayo Kumpulkan!';
                        alert('Gagal terhubung ke server. Silakan coba lagi.');
                        
                        // Buka kunci lagi karena gagal
                        groups.forEach((group) => {
                            group.querySelectorAll('input[type="radio"]').forEach((input) => {
                                input.disabled = false;
                            });
                        });
                    }
                });
            }

            // ---------- POST-TEST ----------
            const submitBtn = document.getElementById('btn-submit-quiz');
            if (!submitBtn) return; // tidak ada kuis di modul ini

            const alertBox = document.getElementById('quiz-alert');
            const reviewNote = document.getElementById('quiz-review-note');
            const moduleSlug = @json($module->slug);
            const moduleLabel = @json('Modul ' . $module->order . ': ' . $module->title);

            function updateProgressWidget(completedCount, total) {
                const widget = document.getElementById('progress-widget');
                if (!widget) return;

                const pct = total > 0 ? Math.round((completedCount / total) * 100) : 0;

                document.getElementById('progress-label').textContent = `${completedCount} dari ${total} modul selesai`;
                document.getElementById('progress-percent').textContent = `${pct}%`;

                const fill = document.getElementById('progress-bar-fill');
                fill.style.width = `${pct}%`;
                document.getElementById('progress-bar-track').setAttribute('aria-valuenow', String(pct));
            }

            // Menandai modul selesai sekarang tersimpan ke akun lewat server
            // (kolom users.completed_modules), BUKAN localStorage lagi —
            // konsisten di semua perangkat yang dipakai user untuk login.
            async function markModuleComplete() {
                try {
                    const res = await fetch(`/api/modules/${moduleSlug}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    if (!res.ok) throw new Error('Gagal menandai modul selesai');

                    const data = await res.json();
                    updateProgressWidget(data.completed_modules_count, data.total_modules);
                } catch (err) {
                    // Progres gagal tersimpan ke server (mis. koneksi putus).
                    // Tidak memblokir alur user (modal sukses tetap muncul di
                    // bawah), tapi widget sidebar TIDAK di-update supaya tidak
                    // menampilkan angka yang sebenarnya belum tersimpan.
                    console.warn('Progres modul gagal tersimpan ke server:', err);
                }
            }

            submitBtn.addEventListener('click', async function () {
                // KUNCI PENTING: Cegah klik berkali-kali!
                if (submitBtn.disabled) return;
                const quizGroups = document.querySelectorAll('.quiz-options');
                const answers = [];
                let allAnswered = true;

                quizGroups.forEach((group) => {
                    const card = group.closest('.quiz-card');
                    const checked = group.querySelector('input[type="radio"]:checked');

                    if (!checked) {
                        allAnswered = false;
                        card.classList.add('quiz-card-missing');
                    } else {
                        card.classList.remove('quiz-card-missing');
                        answers.push({
                            quizId: group.dataset.quizId,
                            optionId: checked.dataset.optionId,
                            group: group,
                        });
                    }
                });

                if (!allAnswered) {
                    alertBox.classList.remove('d-none');
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                alertBox.classList.add('d-none');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split" aria-hidden="true"></i> Memeriksa…';

                try {
                    const results = await Promise.all(answers.map((a) =>
                        fetch('/api/quiz/submit', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({ quiz_id: a.quizId, selected_option_id: a.optionId, type: 'post' }),
                        }).then((res) => res.json())
                    ));

                    results.forEach((data, i) => {
                        const { group, optionId } = answers[i];
                        const correctId = data.correct_option ? String(data.correct_option.id) : null;

                        group.querySelectorAll('.quiz-option-label').forEach((label) => {
                            const input = label.querySelector('input');
                            input.disabled = true;

                            const icon = label.querySelector('.quiz-review-icon');
                            const optId = String(label.dataset.optionId);

                            if (optId === correctId) {
                                label.classList.add('is-review-correct');
                                icon.classList.add('bi-check-circle-fill');
                            } else if (optId === String(optionId)) {
                                label.classList.add('is-review-wrong');
                                icon.classList.add('bi-x-circle-fill');
                            }
                        });
                    });

                    reviewNote.classList.remove('d-none');
                    submitBtn.classList.add('d-none');

                    await markModuleComplete();

                    document.getElementById('quizSuccessModalBody').textContent =
                        `Selamat, kamu berhasil menyelesaikan ${moduleLabel}!`;
                    new bootstrap.Modal(document.getElementById('quizSuccessModal')).show();
                } catch (err) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-check-fill" aria-hidden="true"></i> Ayo Kumpulkan!';
                    alertBox.classList.remove('d-none');
                    alertBox.innerHTML =
                        '<i class="bi bi-wifi-off" aria-hidden="true"></i><span>Gagal terhubung ke server. Coba lagi ya.</span>';
                }
            });
        });
    </script>
@endsection