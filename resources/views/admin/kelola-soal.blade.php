@extends('layouts.admin')

@section('title', 'Kelola Soal Kuis - AKRAB Admin')

@section('admin_content')
    <!-- Tombol Kembali yang Jauh Lebih Baik -->
    <div class="mb-4">
        <a href="{{ route('admin.kuis-glosarium') }}" class="btn btn-white border shadow-sm rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Modul
        </a>
    </div>

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Kuis: <span
                    style="color: var(--primary-color);">{{ $module->title }}</span></h1>
            <p class="text-muted small mb-0">Total: {{ $quizzes->count() }} Pertanyaan</p>
        </div>
        <button class="btn btn-akrab-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSoalModal"
            style="background-color: var(--primary-color); color: white; border-radius: 12px;">
            <i class="bi bi-plus-lg me-2"></i> Tambah Pertanyaan Baru
        </button>
    </div>

    <!-- List Soal Bergaya Google Forms (Tanpa Tabel) -->
    <div class="row d-flex flex-column gap-3">
        @forelse($quizzes as $index => $quiz)
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white"
                    style="border-radius: 16px; border-left: 6px solid var(--primary-color) !important;">
                    <div class="card-body p-4">

                        <!-- Header Kartu Soal & Tombol Edit -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold text-dark mb-0" style="line-height: 1.5;">
                                <span class="text-muted me-1">{{ $index + 1 }}.</span> {{ $quiz->question }}
                            </h5>

                            <!-- Aksi yang lebih subtle (tidak seperti tabel) -->
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                    <!-- Tombol Edit -->
                                    <li>
                                        <button class="dropdown-item text-primary fw-semibold" data-bs-toggle="modal"
                                            data-bs-target="#editSoalModal"
                                            data-action="{{ route('admin.kuis.update', $quiz->id) }}"
                                            data-question="{{ $quiz->question }}"
                                            data-options="{{ json_encode($quiz->options) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit Soal
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <!-- Tombol Hapus -->
                                    <li>
                                        <button class="dropdown-item text-danger fw-semibold" data-bs-toggle="modal"
                                            data-bs-target="#deleteSoalModal"
                                            data-action="{{ route('admin.kuis.destroy', $quiz->id) }}">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Menampilkan Pilihan Ganda -->
                        <div class="ps-4">
                            @forelse($quiz->options as $option)
                                <div
                                    class="d-flex align-items-center mb-2 p-2 rounded-3 {{ $option->is_correct ? 'bg-success bg-opacity-10' : '' }}">
                                    <!-- Ikon Checklist jika benar, Ikon lingkaran kosong jika salah -->
                                    <i
                                        class="bi {{ $option->is_correct ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-3 fs-5"></i>

                                    <span
                                        class="fw-semibold me-2 {{ $option->is_correct ? 'text-success' : 'text-muted' }}">{{ $option->label }}.</span>
                                    <span
                                        class="{{ $option->is_correct ? 'fw-bold text-success' : 'text-dark' }}">{{ $option->text }}</span>

                                    @if($option->is_correct)
                                        <span class="badge bg-success ms-auto">Kunci Jawaban</span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-muted small fst-italic">
                                    <i class="bi bi-exclamation-triangle text-warning me-1"></i> Pilihan jawaban belum ditambahkan.
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-ui-radios fs-1 d-block mb-3 text-light"></i>
                Belum ada pertanyaan. Klik "Tambah Pertanyaan Baru" untuk mulai membuat kuis.
            </div>
        @endforelse
    </div>

    {{-- ================= MODAL TAMBAH SOAL ================= --}}
    <div class="modal fade" id="addSoalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="{{ route('admin.kuis.store', $module->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Tambah Pertanyaan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan</label>
                            <textarea name="question" class="form-control" rows="3"
                                placeholder="Tuliskan pertanyaan kuis di sini..." required></textarea>
                        </div>

                        <label class="form-label fw-semibold mb-2">Pilihan Jawaban</label>
                        <p class="text-muted small mb-3">Isi pilihan ganda dan pilih salah satu <i>radio button</i> sebagai
                            kunci jawaban yang benar.</p>

                        <!-- Opsi A -->
                        <div class="input-group mb-2">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0 border-primary" type="radio" name="correct_option"
                                    value="0" required style="cursor:pointer;">
                                <span class="ms-2 fw-bold text-dark">A</span>
                            </div>
                            <input type="text" name="options[0]" class="form-control" placeholder="Tulis jawaban A..."
                                required>
                        </div>

                        <!-- Opsi B -->
                        <div class="input-group mb-2">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0 border-primary" type="radio" name="correct_option"
                                    value="1" required style="cursor:pointer;">
                                <span class="ms-2 fw-bold text-dark">B</span>
                            </div>
                            <input type="text" name="options[1]" class="form-control" placeholder="Tulis jawaban B..."
                                required>
                        </div>

                        <!-- Opsi C -->
                        <div class="input-group mb-2">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0 border-primary" type="radio" name="correct_option"
                                    value="2" required style="cursor:pointer;">
                                <span class="ms-2 fw-bold text-dark">C</span>
                            </div>
                            <input type="text" name="options[2]" class="form-control" placeholder="Tulis jawaban C..."
                                required>
                        </div>

                        <!-- Opsi D (Opsional, hapus 'required' agar tidak wajib diisi) -->
                        <div class="input-group mb-2">
                            <div class="input-group-text bg-white">
                                <input class="form-check-input mt-0 border-primary" type="radio" name="correct_option"
                                    value="3" style="cursor:pointer;">
                                <span class="ms-2 fw-bold text-dark">D</span>
                            </div>
                            <input type="text" name="options[3]" class="form-control"
                                placeholder="Tulis jawaban D (Opsional)...">
                        </div>

                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary rounded-pill px-4"
                            style="background-color: var(--primary-color); color: white;">Simpan Pertanyaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= MODAL EDIT SOAL ================= --}}
    <div class="modal fade" id="editSoalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <form id="editSoalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Pertanyaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan</label>
                            <textarea name="question" id="edit_question" class="form-control" rows="3" required></textarea>
                        </div>
                        <label class="form-label fw-semibold mb-2">Pilihan Jawaban (Pilih salah satu sebagai kunci)</label>
                        <!-- Container untuk opsi jawaban yang dimuat via JS -->
                        <div id="edit_options_container"></div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-akrab-primary rounded-pill px-4"
                            style="background-color: var(--primary-color); color: white;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL HAPUS SOAL ================= --}}
    <div class="modal fade" id="deleteSoalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow text-center p-4">
                <form id="deleteSoalForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mb-2">Hapus Soal Ini?</h5>
                    <p class="text-muted mb-4">Soal beserta pilihan jawabannya akan dihapus permanen dari database.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script untuk mengisi Modal Edit
            const editModal = document.getElementById('editSoalModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const options = JSON.parse(button.getAttribute('data-options'));

                    document.getElementById('editSoalForm').action = button.getAttribute('data-action');
                    document.getElementById('edit_question').value = button.getAttribute('data-question');

                    const optionsContainer = document.getElementById('edit_options_container');
                    optionsContainer.innerHTML = ''; // Kosongkan container

                    // Looping data opsi jawaban untuk membuat input field
                    options.forEach(opt => {
                        const isChecked = opt.is_correct === 1 ? 'checked' : '';
                        optionsContainer.innerHTML += `
                                        <div class="input-group mb-2">
                                            <div class="input-group-text bg-white">
                                                <input class="form-check-input mt-0 border-primary" type="radio" name="correct_option" value="${opt.id}" ${isChecked} required style="cursor:pointer;">
                                                <span class="ms-2 fw-bold text-dark">${opt.label}</span>
                                            </div>
                                            <input type="text" name="options[${opt.id}][text]" class="form-control" value="${opt.text}" required>
                                        </div>
                                    `;
                    });
                });
            }

            // Script untuk Modal Hapus
            const deleteModal = document.getElementById('deleteSoalModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    document.getElementById('deleteSoalForm').action = event.relatedTarget.getAttribute('data-action');
                });
            }
        });
    </script>
@endsection