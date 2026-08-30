@extends('layouts.admin')

@section('title', 'Progres Belajar Anak - AKRAB Admin')

@section('admin_content')
    {{-- Flash Message Error jika belum pilih modul --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Progres Belajar Anak</h1>
            <p class="text-muted small mb-0">Pantau perkembangan belajar, kuis evaluasi, dan modul yang diselesaikan oleh anak/pengguna.</p>
        </div>

        <div class="d-flex gap-2">
            @if(request('module_id'))
                <a href="{{ route('admin.progress.export.rekap', ['module_id' => request('module_id'), 'search' => request('search')]) }}"
                    class="btn btn-akrab-primary">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Rekap Modul Ini (.xlsx)
                </a>
            @else
                <button type="button" class="btn btn-akrab-primary" data-bs-toggle="modal" data-bs-target="#selectModuleModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Rekap Nilai Modul
                </button>
            @endif
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-3 p-md-4">
            <form id="progressFilterForm" method="GET" action="{{ route('admin.progress') }}" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted" id="searchIcon"></i>
                            <span class="spinner-border spinner-border-sm text-primary d-none" id="searchSpinner" role="status" aria-hidden="true"></span>
                        </span>
                        <input type="text" id="progressSearchInput" name="search" class="form-control border-start-0"
                            placeholder="Cari nama, email, atau username pengguna..." value="{{ request('search') }}"
                            autocomplete="off" aria-label="Cari nama, email, atau username pengguna">
                    </div>
                </div>

                <div class="col-md-4">
                    <select id="progressModuleSelect" name="module_id" class="form-select" aria-label="Filter modul pembelajaran">
                        <option value="">Semua Modul Pembelajaran ({{ $allModules->count() }})</option>
                        @foreach($allModules as $mod)
                            <option value="{{ $mod->id }}" {{ request('module_id') == $mod->id ? 'selected' : '' }}>
                                {{ $mod->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-akrab-primary flex-fill">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.progress') }}" id="btnResetFilter" class="btn btn-light border {{ request('search') || request('module_id') ? '' : 'd-none' }}" title="Reset filter" aria-label="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Notifikasi Live Search Gagal (Aria-live) -->
    <div id="searchErrorAlert" class="alert alert-warning alert-dismissible fade show rounded-3 mb-4 d-none" role="alert" aria-live="polite">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Pencarian otomatis gagal. Silakan coba lagi atau tekan tombol Filter.
        <button type="button" class="btn-close" onclick="document.getElementById('searchErrorAlert').classList.add('d-none')" aria-label="Tutup"></button>
    </div>

    <!-- Container Tabel & Pagination Live Search -->
    <div id="progressTableContainer" aria-live="polite">
        <!-- Tabel Daftar Pengguna & Progres -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="progressTable">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="fw-semibold pb-3" style="width: 50px;">No.</th>
                                <th class="fw-semibold pb-3">Nama Pengguna</th>
                                <th class="fw-semibold pb-3">Email / Akun</th>
                                <th class="fw-semibold pb-3 text-center">Kuis Dikerjakan</th>
                                <th class="fw-semibold pb-3 text-center">Rata-Rata Nilai</th>
                                <th class="fw-semibold pb-3 text-center">Modul Selesai</th>
                                <th class="fw-semibold pb-3 text-center">Aktivitas Terakhir</th>
                                <th class="fw-semibold pb-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $u)
                                <tr>
                                    <td class="fw-semibold text-muted">{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $u->email ?? $u->username ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3 py-2">
                                            {{ $u->metrics->total_attempts }} Soal
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($u->metrics->total_attempts > 0)
                                            <span class="fw-bold {{ $u->metrics->average_score >= 70 ? 'text-success' : ($u->metrics->average_score >= 50 ? 'text-warning' : 'text-danger') }}">
                                                {{ $u->metrics->average_score }}%
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $u->metrics->completed_count >= $totalActiveModules && $totalActiveModules > 0 ? 'bg-success' : 'bg-primary' }} bg-opacity-10 text-{{ $u->metrics->completed_count >= $totalActiveModules && $totalActiveModules > 0 ? 'success' : 'primary' }} px-3 py-2 rounded-pill fw-semibold">
                                            {{ $u->metrics->completed_count }} / {{ $totalActiveModules }} Modul
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted small">
                                            {{ $u->metrics->last_activity ? $u->metrics->last_activity->diffForHumans() : 'Belum Ada' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.progress.show', $u->id) }}" class="btn-akrab-outline">
                                            <i class="bi bi-eye me-1"></i> Detail Progres
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-2 text-light"></i>
                                        Tidak ada pengguna yang sesuai dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="mt-4 d-flex justify-content-end" id="paginationWrapper">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Pemilihan Modul untuk Ekspor Rekap -->
    <div class="modal fade" id="selectModuleModal" tabindex="-1" aria-labelledby="selectModuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form method="GET" action="{{ route('admin.progress.export.rekap') }}">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark" id="selectModuleModalLabel">
                            <i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i> Ekspor Rekap Nilai Per Modul
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="text-muted small mb-3">
                            Pilih modul pembelajaran yang ingin diunduh rekap nilainya. File Excel akan berisi perbandingan nilai Pre-Test dan Post-Test per anak untuk modul terpilih.
                        </p>
                        <div class="mb-3">
                            <label for="export_module_id" class="form-label fw-semibold text-dark">Pilih Modul Pembelajaran <span class="text-danger">*</span></label>
                            <select name="module_id" id="export_module_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih salah satu modul --</option>
                                @foreach($allModules as $mod)
                                    <option value="{{ $mod->id }}">Modul {{ $mod->order }}: {{ $mod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary px-4">
                            <i class="bi bi-download me-1"></i> Unduh Excel (.xlsx)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('additional_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('progressSearchInput');
            const moduleSelect = document.getElementById('progressModuleSelect');
            const filterForm = document.getElementById('progressFilterForm');
            const tableContainer = document.getElementById('progressTableContainer');
            const searchIcon = document.getElementById('searchIcon');
            const searchSpinner = document.getElementById('searchSpinner');
            const errorAlert = document.getElementById('searchErrorAlert');
            const btnReset = document.getElementById('btnResetFilter');

            let debounceTimer = null;
            let currentController = null;
            let activeRequestId = 0;

            function updateResetButton(searchVal, moduleVal) {
                if (btnReset) {
                    if (searchVal.trim() !== '' || (moduleVal && moduleVal !== '')) {
                        btnReset.classList.remove('d-none');
                    } else {
                        btnReset.classList.add('d-none');
                    }
                }
            }

            function fetchResults(pageUrl = null) {
                const searchVal = searchInput ? searchInput.value.trim() : '';
                const moduleVal = moduleSelect ? moduleSelect.value : '';

                updateResetButton(searchVal, moduleVal);

                // Buat target URL
                let targetUrl;
                if (pageUrl) {
                    targetUrl = new URL(pageUrl, window.location.origin);
                } else {
                    targetUrl = new URL("{{ route('admin.progress') }}", window.location.origin);
                    if (searchVal !== '') {
                        targetUrl.searchParams.set('search', searchVal);
                    }
                    if (moduleVal !== '') {
                        targetUrl.searchParams.set('module_id', moduleVal);
                    }
                }

                // Abort request sebelumnya yang belum selesai
                if (currentController) {
                    currentController.abort();
                }
                currentController = new AbortController();
                const currentRequestId = ++activeRequestId;

                // Tampilkan loading indicator halus
                if (searchIcon && searchSpinner) {
                    searchIcon.classList.add('d-none');
                    searchSpinner.classList.remove('d-none');
                }
                if (tableContainer) {
                    tableContainer.style.opacity = '0.5';
                    tableContainer.style.pointerEvents = 'none';
                    tableContainer.style.transition = 'opacity 0.2s ease';
                }
                if (errorAlert) {
                    errorAlert.classList.add('d-none');
                }

                fetch(targetUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html, application/xhtml+xml'
                    },
                    signal: currentController.signal
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response not ok: ' + response.status);
                    }
                    return response.text();
                })
                .then(html => {
                    // Abaikan respons jika bukan request paling baru
                    if (currentRequestId !== activeRequestId) {
                        return;
                    }

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('progressTableContainer');

                    if (newContainer && tableContainer) {
                        tableContainer.innerHTML = newContainer.innerHTML;
                        attachPaginationListeners();
                    }

                    // Sinkronisasi hidden search input pada modal ekspor
                    const exportModalSearch = document.querySelector('#selectModuleModal input[name="search"]');
                    if (exportModalSearch) {
                        exportModalSearch.value = searchVal;
                    }

                    // Update URL browser tanpa reload
                    window.history.replaceState(null, '', targetUrl.toString());
                })
                .catch(err => {
                    if (err.name === 'AbortError') {
                        return; // Request dibatalkan secara normal oleh input baru
                    }
                    if (errorAlert) {
                        errorAlert.classList.remove('d-none');
                    }
                })
                .finally(() => {
                    if (currentRequestId === activeRequestId) {
                        if (searchIcon && searchSpinner) {
                            searchIcon.classList.remove('d-none');
                            searchSpinner.classList.add('d-none');
                        }
                        if (tableContainer) {
                            tableContainer.style.opacity = '1';
                            tableContainer.style.pointerEvents = 'auto';
                        }
                    }
                });
            }

            function attachPaginationListeners() {
                if (!tableContainer) return;
                const paginationLinks = tableContainer.querySelectorAll('#paginationWrapper a.page-link');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        if (href && href !== '#') {
                            fetchResults(href);
                        }
                    });
                });
            }

            // Input event live search dengan debounce 350ms
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchResults();
                    }, 350);
                });
            }

            // Change event pada filter modul
            if (moduleSelect) {
                moduleSelect.addEventListener('change', function () {
                    clearTimeout(debounceTimer);
                    fetchResults();
                });
            }

            // Fallback submit form
            if (filterForm) {
                filterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearTimeout(debounceTimer);
                    fetchResults();
                });
            }

            // Bind listener pagination awal
            attachPaginationListeners();
        });
    </script>
@endsection
