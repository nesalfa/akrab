@extends('layouts.admin')

@section('title', 'Pesan Tanya Ahli - AKRAB Admin')

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal memproses data:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Pesan Tanya Ahli</h1>
            <p class="text-muted small mb-0">Kelola dan jawab pertanyaan masuk dari pengguna seputar kesehatan reproduksi.
            </p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-3 p-md-4">
            <form id="consultationFilterForm" method="GET" action="{{ route('admin.consultations') }}"
                class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted" id="searchIcon"></i>
                            <span class="spinner-border spinner-border-sm text-primary d-none" id="searchSpinner"
                                role="status" aria-hidden="true"></span>
                        </span>
                        <input type="text" id="consultationSearchInput" name="search" class="form-control border-start-0"
                            placeholder="Cari pengirim, email, pertanyaan..." value="{{ request('search') }}"
                            autocomplete="off" aria-label="Cari pengirim, email, atau isi pertanyaan">
                    </div>
                </div>

                <div class="col-md-4">
                    <select id="consultationStatusSelect" name="status" class="form-select"
                        aria-label="Filter status pertanyaan">
                        <option value="">Semua Status ({{ $pendingCount + $answeredCount }})</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Belum Dijawab ({{ $pendingCount }})
                        </option>
                        <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>
                            Sudah Dijawab ({{ $answeredCount }})
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-akrab-primary flex-fill">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.consultations') }}" id="btnResetFilter"
                        class="btn btn-light border {{ request('search') || request('status') ? '' : 'd-none' }}"
                        title="Reset filter" aria-label="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Notifikasi Live Search Gagal (Aria-live) -->
    <div id="searchErrorAlert" class="alert alert-warning alert-dismissible fade show rounded-3 mb-4 d-none" role="alert"
        aria-live="polite">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Pencarian otomatis gagal. Silakan coba lagi atau tekan tombol
        Filter.
        <button type="button" class="btn-close"
            onclick="document.getElementById('searchErrorAlert').classList.add('d-none')" aria-label="Tutup"></button>
    </div>

    <!-- Container Tabel & Pagination Live Search -->
    <div id="consultationTableContainer" aria-live="polite">
        <!-- Tabel Daftar Pertanyaan -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="fw-semibold pb-3" style="width: 50px;">No.</th>
                                <th class="fw-semibold pb-3">Pengirim</th>
                                <th class="fw-semibold pb-3">Pertanyaan</th>
                                <th class="fw-semibold pb-3">Tanggal Kirim</th>
                                <th class="fw-semibold pb-3 text-center">Status</th>
                                <th class="fw-semibold pb-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $index => $item)
                                <tr>
                                    <td class="fw-semibold text-muted">{{ $consultations->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                        @if($item->email)
                                            <div class="text-muted small" style="font-size: 0.78rem;">
                                                <i class="bi bi-envelope me-1"></i>{{ $item->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark d-inline-block text-truncate" style="max-width: 320px;"
                                            title="{{ $item->question }}">
                                            {{ $item->question }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status === 'answered')
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Sudah Dijawab
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">
                                                <i class="bi bi-clock-history me-1"></i> Belum Dijawab
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($item->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-akrab-primary" data-bs-toggle="modal"
                                                data-bs-target="#answerModal"
                                                data-action="{{ route('admin.consultations.answer', $item->id) }}"
                                                data-name="{{ $item->name }}"
                                                data-created="{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '—' }}"
                                                data-question="{{ $item->question }}">
                                                <i class="bi bi-reply-fill me-1"></i> Jawab
                                            </button>
                                        @else
                                            <button type="button" class="btn-akrab-outline" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-name="{{ $item->name }}"
                                                data-email="{{ $item->email ?? '—' }}"
                                                data-created="{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '—' }}"
                                                data-question="{{ $item->question }}" data-answer="{{ $item->answer }}"
                                                data-responder="{{ $item->responder->name ?? 'Admin' }}"
                                                data-answered="{{ $item->answered_at ? $item->answered_at->format('d M Y, H:i') : '—' }}">
                                                <i class="bi bi-eye me-1"></i> Detail
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-chat-quote fs-1 d-block mb-2 text-light"></i>
                                        Tidak ada pertanyaan yang sesuai dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($consultations->hasPages())
                    <div class="mt-4 d-flex justify-content-end" id="paginationWrapper">
                        {{ $consultations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= MODAL JAWAB PERTANYAAN ================= --}}
    <div class="modal fade" id="answerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <form id="answerForm" method="POST" action="">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h2 class="modal-title h5 fw-bold mb-0">
                            <i class="bi bi-reply-fill me-2" style="color: var(--primary-color);"></i> Jawab Pertanyaan
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark small" id="modalSenderName">Pengirim</span>
                                <span class="text-muted small" id="modalQuestionDate"
                                    style="font-size: 0.78rem;">Tanggal</span>
                            </div>
                            <div class="text-dark small" id="modalQuestionText"
                                style="line-height: 1.6; white-space: pre-wrap;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="answerInput" class="form-label fw-semibold">Jawaban Anda <span
                                    class="text-danger">*</span></label>
                            <textarea name="answer" id="answerInput" class="form-control" rows="6"
                                placeholder="Tuliskan jawaban yang ramah, informatif, dan akurat..." required></textarea>
                            <div class="form-text small">Jawaban ini akan langsung dapat dilihat oleh pengguna yang
                                bertanya.</div>
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary">
                            <i class="bi bi-send me-1"></i> Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DETAIL PERTANYAAN & JAWABAN ================= --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h2 class="modal-title h5 fw-bold mb-0">
                        <i class="bi bi-chat-left-check-fill me-2" style="color: var(--primary-color);"></i> Detail Tanya
                        Ahli
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <div class="small text-muted fw-semibold text-uppercase">Pengirim</div>
                        <div class="fw-bold text-dark" id="detailSender"></div>
                        <div class="small text-muted" id="detailDate"></div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="small text-muted fw-semibold mb-1">Pertanyaan:</div>
                        <div class="text-dark small" id="detailQuestion" style="line-height: 1.6; white-space: pre-wrap;">
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-2 border"
                        style="background-color: #F8F4FC; border-color: rgba(106, 76, 147, 0.2) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold" style="color: var(--primary-color);">
                                <i class="bi bi-person-check-fill me-1"></i> Jawaban Ahli:
                            </span>
                            <span class="text-muted small" id="detailAnsweredAt" style="font-size: 0.78rem;"></span>
                        </div>
                        <div class="text-dark small" id="detailAnswer" style="line-height: 1.6; white-space: pre-wrap;">
                        </div>
                        <div class="text-muted small mt-2" style="font-size: 0.75rem;" id="detailResponder"></div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Jawab
            const answerModal = document.getElementById('answerModal');
            if (answerModal) {
                answerModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    document.getElementById('answerForm').action = btn.dataset.action;
                    document.getElementById('modalSenderName').textContent = btn.dataset.name;
                    document.getElementById('modalQuestionDate').textContent = btn.dataset.created;
                    document.getElementById('modalQuestionText').textContent = btn.dataset.question;
                    document.getElementById('answerInput').value = '';
                });
            }

            // Modal Detail
            const detailModal = document.getElementById('detailModal');
            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    document.getElementById('detailSender').textContent = btn.dataset.name + (btn.dataset.email !== '—' ? ' (' + btn.dataset.email + ')' : '');
                    document.getElementById('detailDate').textContent = 'Dikirim pada: ' + btn.dataset.created;
                    document.getElementById('detailQuestion').textContent = btn.dataset.question;
                    document.getElementById('detailAnswer').textContent = btn.dataset.answer;
                    document.getElementById('detailAnsweredAt').textContent = 'Dijawab pada: ' + btn.dataset.answered;
                    document.getElementById('detailResponder').textContent = 'Dijawab oleh: ' + btn.dataset.responder;
                });
            }

            // ================= LIVE SEARCH & FILTER AJAX =================
            const searchInput = document.getElementById('consultationSearchInput');
            const statusSelect = document.getElementById('consultationStatusSelect');
            const filterForm = document.getElementById('consultationFilterForm');
            const tableContainer = document.getElementById('consultationTableContainer');
            const searchIcon = document.getElementById('searchIcon');
            const searchSpinner = document.getElementById('searchSpinner');
            const errorAlert = document.getElementById('searchErrorAlert');
            const btnReset = document.getElementById('btnResetFilter');

            let debounceTimer = null;
            let currentController = null;
            let activeRequestId = 0;

            function updateResetButton(searchVal, statusVal) {
                if (btnReset) {
                    if (searchVal.trim() !== '' || (statusVal && statusVal !== '')) {
                        btnReset.classList.remove('d-none');
                    } else {
                        btnReset.classList.add('d-none');
                    }
                }
            }

            function fetchResults(pageUrl = null) {
                const searchVal = searchInput ? searchInput.value.trim() : '';
                const statusVal = statusSelect ? statusSelect.value : '';

                updateResetButton(searchVal, statusVal);

                // Buat target URL
                let targetUrl;
                if (pageUrl) {
                    targetUrl = new URL(pageUrl, window.location.origin);
                } else {
                    targetUrl = new URL("{{ route('admin.consultations') }}", window.location.origin);
                    if (searchVal !== '') {
                        targetUrl.searchParams.set('search', searchVal);
                    }
                    if (statusVal !== '') {
                        targetUrl.searchParams.set('status', statusVal);
                    }
                }

                // Abort request sebelumnya yang belum selesai
                if (currentController) {
                    currentController.abort();
                }
                currentController = new AbortController();
                const currentRequestId = ++activeRequestId;

                // Tampilkan loading indicator halus tanpa mengaburkan fokus input
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
                        const newContainer = doc.getElementById('consultationTableContainer');

                        if (newContainer && tableContainer) {
                            tableContainer.innerHTML = newContainer.innerHTML;
                            attachPaginationListeners();
                        }

                        // Update URL browser tanpa reload halaman
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

            // Change event pada filter status
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
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