@extends('layouts.admin')

@section('title', 'Kelola Modul & Isi - AKRAB Admin')

@section('admin_content')
    {{-- Flash message dari redirect setelah edit/hapus --}}
    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal menyimpan perubahan:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Kelola Modul & Isi</h1>
            <p class="text-muted small mb-0">Tambah, ubah, atau hapus modul pembelajaran di platform AKRAB.</p>
        </div>
        <div class="flex-shrink-0">
            <button type="button" class="btn-akrab-primary w-100" style="min-width: 190px; max-width: 230px;" data-bs-toggle="modal" data-bs-target="#addModuleModal" aria-label="Tambah Modul Baru">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Modul Baru</span>
            </button>
        </div>
    </div>

    <!-- Tabel Daftar Modul -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <div class="mb-3">
                <div class="input-group" style="max-width: 320px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="moduleSearchInput" class="form-control border-start-0"
                        placeholder="Cari judul modul..." aria-label="Cari modul">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="modulesTable">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="fw-semibold pb-3" style="width: 56px;">No.</th>
                            <th class="fw-semibold pb-3">Judul Modul</th>
                            <th class="fw-semibold pb-3">Status</th>
                            <th class="fw-semibold pb-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $modul)
                            <tr class="module-row" data-title="{{ strtolower($modul->title) }}">
                                <td>
                                    <span
                                        class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold small module-order-badge"
                                        style="width: 32px; height: 32px; background-color: var(--bg-pink, #FFF0F5); color: var(--primary-color);">
                                        {{ $modul->order }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $modul->title }}</div>
                                    <div class="text-muted small text-truncate d-block" style="max-width: 380px;">
                                        {{ $modul->description ?? 'Tidak ada deskripsi' }}
                                    </div>
                                    {{-- "Terakhir diubah" — versi ringkas, hover buat lihat tanggal persis --}}
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;"
                                        title="{{ $modul->updated_at?->format('d M Y, H:i') }}">
                                        <i class="bi bi-clock-history"></i>
                                        Diperbarui {{ $modul->updated_at?->diffForHumans() ?? '—' }}
                                    </div>
                                </td>
                                <td>
                                    @if($modul->is_active)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Aktif</span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="Aksi modul">
                                        <a href="{{ route('module.show', $modul->slug) }}" target="_blank"
                                            class="btn btn-sm btn-light" title="Lihat halaman publik">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light" title="Edit" data-bs-toggle="modal"
                                            data-bs-target="#editModuleModal" data-module-title="{{ $modul->title }}"
                                            data-module-description="{{ $modul->description }}"
                                            data-module-order="{{ $modul->order }}"
                                            data-module-active="{{ $modul->is_active ? '1' : '0' }}"
                                            data-module-updated="{{ $modul->updated_at?->format('d M Y, H:i') ?? '—' }}"
                                            data-module-action="{{ route('admin.modules.update', $modul) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#deleteModuleModal"
                                            data-module-title="{{ $modul->title }}"
                                            data-module-action="{{ route('admin.modules.destroy', $modul) }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2 text-light"></i>
                                    Belum ada modul yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <p id="moduleSearchEmpty" class="text-center text-muted py-4 d-none">
                    <i class="bi bi-search"></i> Tidak ada modul yang cocok dengan pencarian.
                </p>
            </div>
        </div>
    </div>


    {{-- ================= MODAL TAMBAH MODUL ================= --}}
    <div class="modal fade" id="addModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <form action="{{ route('admin.modules.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title h5 fw-bold mb-0">
                            <i class="bi bi-journal-plus me-1" style="color: var(--primary-color);"></i> Tambah Modul Baru
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <!-- URUTAN DIPINDAH KE ATAS DAN DIKUNCI -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Urutan Modul</label>
                            <!-- Atribut readonly dan style cursor: not-allowed agar tidak bisa diubah -->
                            <input type="number" name="order" class="form-control bg-light text-muted fw-bold"
                                style="width: 120px; cursor: not-allowed;" value="{{ ($modules->max('order') ?? 0) + 1 }}"
                                readonly>
                            <div class="form-text small">Urutan dibuat otomatis oleh sistem dan tidak dapat diubah.</div>
                        </div>

                        <!-- JUDUL MODUL -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Modul</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="Contoh: Kesehatan Mental Remaja" required>
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Tuliskan deskripsi singkat tentang modul ini..."></textarea>
                        </div>

                        <!-- STATUS (Sendirian di bawah karena urutan sudah di atas) -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold d-block">Status Akses</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                    id="is_active_new" value="1" checked style="cursor: pointer;">
                                <label class="form-check-label" for="is_active_new" style="cursor: pointer;">Aktif (Langsung
                                    tampil ke pengguna)</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= Modal Edit (1 modal dipakai ulang untuk semua baris) ================= --}}
    <div class="modal fade" id="editModuleModal" tabindex="-1" aria-labelledby="editModuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <form id="editModuleForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h2 class="modal-title h5 fw-bold mb-0" id="editModuleModalLabel">
                            <i class="bi bi-pencil-square me-1" style="color: var(--primary-color);"></i> Edit Modul
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label fw-semibold">Judul Modul</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_order" class="form-label fw-semibold">Urutan</label>
                                <input type="number" name="order" id="edit_order" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                        id="edit_is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <span class="text-muted small" id="editModuleUpdatedNote">
                            <i class="bi bi-clock-history"></i> —
                        </span>
                        <div>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-akrab-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= Modal Konfirmasi Hapus ================= --}}
    <div class="modal fade" id="deleteModuleModal" tabindex="-1" aria-labelledby="deleteModuleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-body text-center p-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 64px; height: 64px; border-radius: 50%; background-color: #FBEAF0;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.75rem; color: #C7365F;"></i>
                    </div>
                    <h2 class="h5 fw-bold mb-2" id="deleteModuleModalLabel">Hapus Modul Ini?</h2>
                    <p class="text-muted mb-4">
                        Apakah Anda yakin akan menghapus modul <strong id="deleteModuleTitle">ini</strong>?
                        Tindakan ini tidak bisa dibatalkan — seluruh konten, kuis, dan glosarium di
                        dalamnya akan ikut terhapus.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Tidak</button>
                        <form id="deleteModuleForm" method="POST" action="" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-trash me-1"></i> Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_js')
    <script>
        // Filter tabel client-side
        document.getElementById('moduleSearchInput')?.addEventListener('input', function (e) {
            const keyword = e.target.value.trim().toLowerCase();
            const rows = document.querySelectorAll('#modulesTable .module-row');
            let visibleCount = 0;

            rows.forEach((row) => {
                const match = row.dataset.title.includes(keyword);
                row.classList.toggle('d-none', !match);
                if (match) visibleCount++;
            });

            document.getElementById('moduleSearchEmpty').classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        });

        // Isi Modal Edit dari data-attribute tombol yang diklik
        document.getElementById('editModuleModal')?.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const form = document.getElementById('editModuleForm');

            form.action = btn.dataset.moduleAction;
            form.querySelector('#edit_title').value = btn.dataset.moduleTitle || '';
            form.querySelector('#edit_description').value = btn.dataset.moduleDescription || '';
            form.querySelector('#edit_order').value = btn.dataset.moduleOrder || '';
            form.querySelector('#edit_is_active').checked = btn.dataset.moduleActive === '1';

            document.getElementById('editModuleUpdatedNote').innerHTML =
                '<i class="bi bi-clock-history"></i> Terakhir diubah: ' + (btn.dataset.moduleUpdated || '—');
        });

        // Isi Modal Hapus dari data-attribute tombol yang diklik
        document.getElementById('deleteModuleModal')?.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            document.getElementById('deleteModuleTitle').textContent = btn.dataset.moduleTitle || 'ini';
            document.getElementById('deleteModuleForm').action = btn.dataset.moduleAction;
        });
    </script>
@endsection