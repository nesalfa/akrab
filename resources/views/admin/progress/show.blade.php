@extends('layouts.admin')

@section('title', 'Detail Progres Belajar: ' . $user->name . ' - AKRAB Admin')

@section('admin_content')
    <!-- Tombol Kembali & Ekspor -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <a href="{{ route('admin.progress') }}" class="btn-akrab-outline">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Progres
        </a>

        <a href="{{ route('admin.progress.export.detail', $user->id) }}" class="btn btn-akrab-primary">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Detail Nilai Anak
        </a>
    </div>

    <!-- Identitas Pengguna & Ringkasan Metrik -->
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-lg-4 d-flex">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 54px; height: 54px; font-size: 1.25rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="h5 fw-bold text-dark mb-0 text-truncate" title="{{ $user->name }}">{{ $user->name }}</h2>
                            <span class="text-muted small text-truncate d-block">{{ $user->email ?? $user->username ?? 'User' }}</span>
                        </div>
                    </div>

                    <hr class="border-light my-3">
                </div>

                <div class="d-flex flex-column gap-2 small mt-auto">
                    <div class="d-flex justify-content-between text-muted">
                        <span>Role Akun:</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Tanggal Terdaftar:</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Modul Selesai:</span>
                        <span class="fw-bold text-success">{{ $completedCount }} / {{ $totalActiveModules }} Modul</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 d-flex">
            <div class="row g-3 w-100 align-items-stretch">
                <div class="col-md-4 col-sm-6 d-flex">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 w-100 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block" style="letter-spacing: 0.05em; min-height: 20px;">Total Jawaban Kuis</span>
                            <h3 class="fw-bold fs-2 text-dark mt-2 mb-0" style="line-height: 1.2;">{{ $totalAttempts }}</h3>
                        </div>
                        <div class="mt-3 pt-2 border-top border-light-subtle">
                            <span class="text-muted small">Soal telah dikerjakan</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 d-flex">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 w-100 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block" style="letter-spacing: 0.05em; min-height: 20px;">Jawaban Benar</span>
                            <h3 class="fw-bold fs-2 text-success mt-2 mb-0" style="line-height: 1.2;">{{ $correctAttempts }}</h3>
                        </div>
                        <div class="mt-3 pt-2 border-top border-light-subtle">
                            <span class="text-muted small">Dari total {{ $totalAttempts }} soal</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 d-flex">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 w-100 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block" style="letter-spacing: 0.05em; min-height: 20px;">Rata-Rata Nilai</span>
                            <h3 class="fw-bold fs-2 mt-2 mb-0 {{ $averageScore >= 70 ? 'text-success' : ($averageScore >= 50 ? 'text-warning' : 'text-danger') }}" style="line-height: 1.2;">
                                {{ $averageScore }}%
                            </h3>
                        </div>
                        <div class="mt-3 pt-2 border-top border-light-subtle">
                            <span class="text-muted small">Akurasi jawaban kuis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Progres Per Modul -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4">
            <h3 class="h5 fw-bold text-dark mb-4">
                <i class="bi bi-journal-check me-2" style="color: var(--primary-color);"></i> Status Pengerjaan Per Modul
            </h3>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="fw-semibold pb-3">Modul Pembelajaran</th>
                            <th class="fw-semibold pb-3 text-center">Status Modul</th>
                            <th class="fw-semibold pb-3 text-center">Pre-Test (Benar / Soal)</th>
                            <th class="fw-semibold pb-3 text-center">Nilai Pre-Test</th>
                            <th class="fw-semibold pb-3 text-center">Post-Test (Benar / Soal)</th>
                            <th class="fw-semibold pb-3 text-center">Nilai Post-Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyByModule as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->module->title }}</div>
                                    <div class="text-muted small">{{ $item->total_questions }} Total Pertanyaan Kuis</div>
                                </td>
                                <td class="text-center">
                                    @if($item->status === 'completed' || $item->is_completed)
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                        </span>
                                    @elseif($item->status === 'in_progress' || $item->pre_attempts_count > 0 || $item->post_attempts_count > 0)
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                            <i class="bi bi-clock-history me-1"></i> Sedang Belajar
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-muted px-3 py-2 rounded-pill">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->pre_attempts_count > 0)
                                        <span class="fw-semibold text-dark">{{ $item->pre_correct }} / {{ $item->total_questions }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->pre_attempts_count > 0)
                                        <span class="fw-bold {{ $item->pre_score >= 70 ? 'text-success' : 'text-warning' }}">{{ $item->pre_score }}%</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->post_attempts_count > 0)
                                        <span class="fw-semibold text-dark">{{ $item->post_correct }} / {{ $item->total_questions }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->post_attempts_count > 0)
                                        <span class="fw-bold {{ $item->post_score >= 70 ? 'text-success' : 'text-warning' }}">{{ $item->post_score }}%</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Seluruh Jawaban Kuis -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <h3 class="h5 fw-bold text-dark mb-4">
                <i class="bi bi-list-check me-2" style="color: var(--primary-color);"></i> Log Detail Riwayat Pengerjaan Kuis
            </h3>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="fw-semibold pb-3" style="width: 50px;">No.</th>
                            <th class="fw-semibold pb-3">Modul</th>
                            <th class="fw-semibold pb-3">Tipe Kuis</th>
                            <th class="fw-semibold pb-3">Pertanyaan</th>
                            <th class="fw-semibold pb-3">Jawaban Pengguna</th>
                            <th class="fw-semibold pb-3 text-center">Status</th>
                            <th class="fw-semibold pb-3 text-end">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attempts as $idx => $att)
                            <tr>
                                <td class="fw-semibold text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $att->module->title ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $att->type === 'pre' ? 'bg-info bg-opacity-10 text-info' : 'bg-primary bg-opacity-10 text-primary' }} rounded-pill px-2 py-1">
                                        {{ $att->type === 'pre' ? 'Pre-Test' : 'Post-Test' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark d-inline-block text-truncate" style="max-width: 260px;" title="{{ $att->quiz->question ?? '' }}">
                                        {{ $att->quiz->question ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark">{{ $att->selectedOption->text ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($att->is_correct)
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i> Benar
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">
                                            <i class="bi bi-x-circle-fill me-1"></i> Salah
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="text-muted small">
                                        {{ $att->created_at ? $att->created_at->format('d M Y, H:i') : '—' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Belum ada log pengerjaan kuis yang tercatat untuk pengguna ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
