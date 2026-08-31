@extends('layouts.admin')

@section('title', 'Kelola Kuis & Glosarium - AKRAB Admin')

@section('additional_css')
    <style>
        /* Styling khusus untuk Nav Pills agar senada dengan tema AKRAB */
        .nav-pills-akrab .nav-link {
            color: #6c757d;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-pills-akrab .nav-link:hover {
            background-color: var(--bg-light);
        }

        .nav-pills-akrab .nav-link.active {
            background-color: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(106, 76, 147, 0.2);
        }
    </style>
@endsection

@section('admin_content')
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Kuis & Glosarium</h1>
            <p class="text-muted small mb-0">Kelola soal evaluasi per modul dan perbarui kamus istilah kesehatan reproduksi.
            </p>
        </div>

        <!-- Tombol Aksi (Hanya muncul saat tab Glosarium aktif) -->
        <button type="button" id="btnTambahGlosarium" class="btn btn-akrab-primary d-none" data-bs-toggle="modal"
            data-bs-target="#glosariumModal" style="background-color: var(--primary-color); color: white;">
            <i class="bi bi-plus-lg me-2"></i> Tambah Istilah Baru
        </button>
    </div>

    <!-- Navigasi Tab (Pills) -->
    <ul class="nav nav-pills nav-pills-akrab mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-kuis-tab" data-bs-toggle="pill" data-bs-target="#pills-kuis"
                type="button" role="tab" aria-controls="pills-kuis" aria-selected="true">
                <i class="bi bi-ui-checks-grid me-1"></i> Manajemen Kuis
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-glosarium-tab" data-bs-toggle="pill" data-bs-target="#pills-glosarium"
                type="button" role="tab" aria-controls="pills-glosarium" aria-selected="false">
                <i class="bi bi-book-half me-1"></i> Kamus Glosarium
            </button>
        </li>
    </ul>

    <!-- Konten Tab -->
    <div class="tab-content" id="pills-tabContent">

        {{-- ==================== TAB 1: MANAJEMEN KUIS ==================== --}}
        <div class="tab-pane fade show active" id="pills-kuis" role="tabpanel" aria-labelledby="pills-kuis-tab"
            tabindex="0">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 bg-light rounded-3 small mb-4">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Pilih modul di bawah ini untuk menambah, mengedit, atau menghapus soal kuis pilihan ganda yang
                        terkait.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="fw-semibold pb-3" style="width: 50px;">No.</th>
                                    <th class="fw-semibold pb-3">Judul Modul</th>
                                    <th class="fw-semibold pb-3 text-center">Jumlah Soal</th>
                                    <th class="fw-semibold pb-3 text-center">Status Kuis</th>
                                    <th class="fw-semibold pb-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Simulasi Looping Data Modul -->
                                @forelse($modules ?? [] as $index => $modul)
                                    <tr>
                                        <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                                        <td class="fw-semibold text-dark">{{ $modul->title }}</td>
                                        <td class="text-center">
                                            <!-- Menampilkan jumlah kuis dari database -->
                                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                                                {{ $modul->quizzes_count }} Soal
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Logika status berdasarkan jumlah soal -->
                                            @if($modul->quizzes_count > 0)
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">
                                                    <i class="bi bi-exclamation-circle me-1"></i> Belum Ada
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <!-- Tombol yang mengarah ke halaman baru khusus soal kuis -->
                                            <a href="{{ route('admin.kuis-kelola', $modul->id) }}" class="btn-akrab-outline">
                                                Kelola Soal
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada modul yang ditambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 2: KAMUS GLOSARIUM ==================== --}}
        <div class="tab-pane fade" id="pills-glosarium" role="tabpanel" aria-labelledby="pills-glosarium-tab" tabindex="0">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <!-- Kolom Pencarian -->
                    <div class="mb-4" style="max-width: 320px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" id="glosariumSearch" class="form-control bg-light border-start-0"
                                placeholder="Cari istilah...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="glosariumTable">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="fw-semibold pb-3" style="width: 25%;">Kata / Istilah</th>
                                    <th class="fw-semibold pb-3">Penjelasan (Definisi)</th>
                                    <th class="fw-semibold pb-3 text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Simulasi Looping Data Glosarium -->
                                @forelse($glossaries ?? [] as $glosarium)
                                    <tr class="glosarium-row" data-term="{{ strtolower($glosarium->term) }}">
                                        <td>
                                            <span class="fw-bold text-dark px-2 py-1 rounded"
                                                style="background-color: var(--bg-pink); color: var(--primary-color);">
                                                {{ $glosarium->term }}
                                            </span>
                                        </td>
                                        <td><span class="text-muted small"
                                                style="line-height: 1.5;">{{ $glosarium->definition }}</span></td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <!-- Tombol Edit -->
                                                <button type="button" class="btn btn-sm btn-light text-primary" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editGlosariumModal"
                                                    data-action="{{ route('admin.glosarium.update', $glosarium->id) }}"
                                                    data-module="{{ $glosarium->module_id }}" data-term="{{ $glosarium->term }}"
                                                    data-definition="{{ $glosarium->definition }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <button type="button" class="btn btn-sm btn-light text-danger" title="Hapus"
                                                    data-bs-toggle="modal" data-bs-target="#deleteGlosariumModal"
                                                    data-action="{{ route('admin.glosarium.destroy', $glosarium->id) }}"
                                                    data-term="{{ $glosarium->term }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-book fs-1 d-block mb-2 text-light"></i>
                                            Belum ada istilah glosarium yang ditambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Modal Tambah Glosarium ================= --}}
    <div class="modal fade" id="glosariumModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <!-- Cari baris ini dan ubah action-nya -->
                <form action="{{ route('admin.glosarium.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title h5 fw-bold mb-0"><i class="bi bi-spellcheck me-2"
                                style="color: var(--primary-color);"></i> Form Glosarium</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Modul Terkait</label>
                            <select name="module_id" class="form-select" required>
                                <option value="">-- Pilih Modul --</option>
                                @foreach($modules as $mod)
                                    <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kata / Istilah</label>
                            <input type="text" name="term" class="form-control" placeholder="Contoh: Pubertas" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penjelasan</label>
                            <textarea name="definition" class="form-control" rows="4"
                                placeholder="Tuliskan definisi sederhana..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary"
                            style="background-color: var(--primary-color); color: white;">Simpan Istilah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT GLOSARIUM ================= --}}
    <div class="modal fade" id="editGlosariumModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <form id="editGlosariumForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom-0 pb-0">
                        <h2 class="modal-title h5 fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit
                            Istilah</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Modul Terkait</label>
                            <!-- Perhatikan ada tambahan id="edit_module_id" di sini -->
                            <select name="module_id" id="edit_module_id" class="form-select" required>
                                <option value="">-- Pilih Modul --</option>
                                @foreach($modules as $mod)
                                    <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kata / Istilah</label>
                            <input type="text" name="term" id="edit_term" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penjelasan</label>
                            <textarea name="definition" id="edit_definition" class="form-control" rows="4"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary rounded-pill px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL HAPUS GLOSARIUM ================= --}}
    <div class="modal fade" id="deleteGlosariumModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow text-center p-4">
                <form id="deleteGlosariumForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mb-2">Hapus Istilah <span id="delete_term_text" class="text-danger"></span>?</h5>
                    <p class="text-muted mb-4">Tindakan ini akan menghapus istilah dari kamus secara permanen.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MENGISI DATA MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Edit Glosarium
            const editModal = document.getElementById('editGlosariumModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    document.getElementById('editGlosariumForm').action = button.getAttribute('data-action');
                    document.getElementById('edit_term').value = button.getAttribute('data-term');
                    document.getElementById('edit_definition').value = button.getAttribute('data-definition');
                    document.getElementById('edit_module_id').value = button.getAttribute('data-module');
                });
            }

            // Modal Hapus Glosarium
            const deleteModal = document.getElementById('deleteGlosariumModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    document.getElementById('deleteGlosariumForm').action = button.getAttribute('data-action');
                    document.getElementById('delete_term_text').textContent = "'" + button.getAttribute('data-term') + "'";
                });
            }
        });
    </script>

@endsection

@section('additional_js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Tampilkan tombol "Tambah Istilah" HANYA ketika tab Glosarium aktif
            const btnTambah = document.getElementById('btnTambahGlosarium');

            document.getElementById('pills-glosarium-tab').addEventListener('shown.bs.tab', function () {
                btnTambah.classList.remove('d-none');
            });

            document.getElementById('pills-kuis-tab').addEventListener('shown.bs.tab', function () {
                btnTambah.classList.add('d-none');
            });

            // Fitur Pencarian Real-time Glosarium
            document.getElementById('glosariumSearch')?.addEventListener('input', function (e) {
                const keyword = e.target.value.toLowerCase();
                document.querySelectorAll('.glosarium-row').forEach(row => {
                    const isMatch = row.dataset.term.includes(keyword);
                    row.classList.toggle('d-none', !isMatch);
                });
            });
        });
    </script>
@endsection