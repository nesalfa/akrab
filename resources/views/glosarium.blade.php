@extends('layouts.app')

@section('title', 'Glosarium Visual - AKRAB')

@section('content')
    <div class="py-2 py-md-4">
        <!-- Breadcrumb Navigasi -->
        <nav aria-label="Jalur navigasi" class="mb-4">
            <ol class="breadcrumb-pill">
                <li>
                    <a href="{{ route('home') }}">
                        <i class="bi bi-house-door-fill" aria-hidden="true"></i> Beranda
                    </a>
                </li>
                <li>
                    <span class="current" aria-current="page">Glosarium</span>
                </li>
            </ol>
        </nav>

        <!-- Judul & Deskripsi -->
        <div class="mb-4 text-center text-md-start">
            <h1 class="display-6 fw-bold text-dark mb-2">Glosarium Visual</h1>
            <p class="text-muted fs-5">Temukan penjelasan istilah-istilah penting kesehatan reproduksi dengan bahasa yang
                mudah dipahami.</p>
        </div>

        <!-- Kolom Pencarian (Search Bar) -->
        <div class="mb-3">
            <form action="{{ route('glosarium') }}" method="GET" role="search">
                <div class="input-group input-group-lg search-bar-container shadow-sm">
                    <span class="input-group-text bg-white border-0 text-muted px-4" id="search-icon">
                        <i class="bi bi-search fs-5" aria-hidden="true"></i>
                    </span>
                    <label for="glosarium-search" class="visually-hidden">Cari kata atau istilah</label>
                    <input type="text" id="glosarium-search" name="q" value="{{ request('q') }}"
                        class="form-control border-0 shadow-none ps-2 py-3" placeholder="Cari kata atau istilah..."
                        aria-describedby="search-icon">
                    <button type="submit" class="btn btn-akrab-primary px-4" style="border-radius: 0 12px 12px 0;">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Info jumlah hasil (membantu orientasi pengguna, termasuk pengguna screen reader) -->
        @php
            // ->total() hanya ada kalau $glosariums hasil paginate(); kalau hanya
            // Collection biasa (->get()), gunakan ->count() sebagai fallback.
            $glosariumCount = method_exists($glosariums, 'total') ? $glosariums->total() : $glosariums->count();
        @endphp
        <p class="text-muted small mb-4" role="status" aria-live="polite">
            @if(request('q'))
                Menampilkan {{ $glosariumCount }} hasil untuk &ldquo;{{ request('q') }}&rdquo;
            @else
                Menampilkan {{ $glosariumCount }} istilah
            @endif
        </p>

        <!-- Grid Kartu Glosarium -->
        @if($glosariums->count() > 0)
            <ul class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4 list-unstyled glossary-grid" role="list"
                aria-label="Daftar istilah glosarium">
                @foreach ($glosariums as $item)
                    @php
                        $itemTitle = $item->title ?? $item->term ?? $item->nama_istilah;
                        $itemDesc = $item->description ?? $item->definition ?? $item->deskripsi;

                        $related = $item->related_terms ?? $item->related ?? $item->istilah_terkait ?? null;
                        if (is_string($related)) {
                            $related = json_decode($related, true) ?? array_map('trim', explode(',', $related));
                        }

                        $descPlain = trim(strip_tags($itemDesc ?? ''));
                        $descShort = \Illuminate\Support\Str::limit($descPlain, 140);
                        $isTruncated = $descShort !== $descPlain;
                    @endphp

                    <li class="col">
                        <article class="glossary-card h-100 d-flex flex-column">
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="term-icon-box" aria-hidden="true">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <h2 class="h5 fw-bold mb-0" style="color: var(--primary-color);">
                                        {{ $itemTitle }}
                                    </h2>
                                </div>

                                <div class="text-muted glossary-card-desc" style="line-height: 1.7;">
                                    @if($isTruncated)
                                        <p class="mb-1">{{ $descShort }}</p>
                                        <details class="glossary-details">
                                            <summary class="glossary-details-toggle">
                                                Baca selengkapnya
                                                <span class="visually-hidden">tentang {{ $itemTitle }}</span>
                                            </summary>
                                            <p class="mt-2 mb-0">{{ $descPlain }}</p>
                                        </details>
                                    @else
                                        <p class="mb-0">{{ $descPlain }}</p>
                                    @endif
                                </div>

                                @if(!empty($related) && is_array($related) && count(array_filter($related)) > 0)
                                    <div class="d-flex align-items-center gap-2 flex-wrap mt-3 pt-3 border-top border-light mt-auto">
                                        <span class="visually-hidden">Istilah terkait:</span>
                                        <i class="bi bi-tags text-muted small" aria-hidden="true"></i>
                                        @foreach (array_filter($related) as $tag)
                                            <a href="{{ route('glosarium', ['q' => $tag]) }}"
                                                class="badge tag-terkait text-decoration-none">
                                                {{ $tag }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    </li>
                @endforeach
            </ul>
        @else
            <!-- Tampilan Kalau Data Kosong -->
            <div class="text-center py-5 my-5 glossary-empty-state">
                <div class="mb-4" style="color: #D1C4E9;">
                    <i class="bi bi-journal-x" style="font-size: 4rem;" aria-hidden="true"></i>
                </div>
                <h2 class="h4 fw-bold text-dark mb-2">Istilah Tidak Ditemukan</h2>
                <p class="text-muted fs-5">Coba gunakan kata kunci lain yang lebih umum.</p>
                <a href="{{ route('glosarium') }}" class="btn btn-akrab-outline mt-3">
                    <i class="bi bi-arrow-clockwise me-1"></i> Lihat Semua Istilah
                </a>
            </div>
        @endif

        <!-- Pagination -->
        @if(method_exists($glosariums, 'links'))
            <div class="d-flex justify-content-center mt-5">
                {{ $glosariums->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@section('additional_css')
    <style>
        /* Breadcrumb Pill Styles */
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
            transition: all 0.2s ease;
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
            /* Bootstrap icon chevron-right */
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

        /* Search Bar */
        .search-bar-container {
            border-radius: 12px;
            background-color: #FFFFFF;
            border: 2px solid #EAEAEA;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .search-bar-container:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(255, 202, 58, 0.2) !important;
        }

        /* ===== Grid Kartu Glosarium ===== */
        .glossary-grid {
            margin: 0;
        }

        .glossary-card {
            background-color: #FFFFFF;
            border: 1px solid #EAEAEA;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .glossary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color);
        }

        /* Indikator fokus jelas untuk elemen interaktif di dalam kartu (WCAG 2.4.7) */
        .glossary-card:focus-within {
            outline: 3px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* Ikon kotak di sebelah judul istilah */
        .term-icon-box {
            width: 44px;
            height: 44px;
            min-width: 44px;
            background-color: var(--bg-pink);
            color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .glossary-card-desc {
            font-size: 0.98rem;
        }

        /* Elemen <details>/<summary> native: accessible tanpa JS tambahan,
               otomatis keyboard-operable dan mengekspos status buka/tutup ke pembaca layar */
        .glossary-details-toggle {
            cursor: pointer;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.92rem;
            list-style: none;
        }

        .glossary-details-toggle::-webkit-details-marker {
            display: none;
        }

        .glossary-details-toggle::before {
            content: '\F282';
            font-family: 'bootstrap-icons';
            margin-right: 6px;
            font-size: 0.8rem;
        }

        .glossary-details[open] .glossary-details-toggle::before {
            content: '\F286';
        }

        .glossary-details-toggle:focus-visible {
            outline: 3px solid var(--accent-color);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Tag Terkait */
        .tag-terkait {
            background-color: #F0F2F5;
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            border: 1px solid #E4E6E9;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .tag-terkait:hover,
        .tag-terkait:focus-visible {
            background-color: var(--bg-pink);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .tag-terkait:focus-visible {
            outline: 3px solid var(--accent-color);
            outline-offset: 2px;
        }

        .glossary-empty-state {
            border: 1px dashed #EAEAEA;
            border-radius: 20px;
        }

        /* Hormati preferensi pengguna yang mengurangi animasi */
        @media (prefers-reduced-motion: reduce) {
            .glossary-card {
                transition: none;
            }

            .glossary-card:hover {
                transform: none;
            }
        }
    </style>
@endsection