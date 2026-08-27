@extends('layouts.admin')

@section('title', 'Ringkasan Dashboard - AKRAB Admin')

@section('admin_content')
    <!-- Baris 1: 4 Kartu Statistik (Poin 2: Sekarang Bisa Diklik!) -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.modules') }}" class="card card-hover border-0 shadow-sm p-3 rounded-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Modul</span>
                        <h2 class="fw-bold fs-2 text-dark mt-1 mb-0">{{ $totalModules }}</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background-color: var(--bg-pink); color: var(--primary-color);">
                        <i class="bi bi-book fs-3"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="#" class="card card-hover border-0 shadow-sm p-3 rounded-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Pengguna Aktif</span>
                        <h2 class="fw-bold fs-2 text-dark mt-1 mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background-color: #E8F0FE; color: #1967D2;">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="#" class="card card-hover border-0 shadow-sm p-3 rounded-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Rata-Rata Selesai</span>
                        <h2 class="fw-bold fs-2 text-dark mt-1 mb-0">{{ $percentSelesai }}%</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background-color: #E6F4EA; color: #137333;">
                        <i class="bi bi-graph-up-arrow fs-3"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <!-- Asumsikan ini route sementara, ubah # menjadi route('admin.consultations') jika sudah siap -->
            <a href="#" class="card card-hover border-0 shadow-sm p-3 rounded-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Tanya Ahli Masuk</span>
                        <h2 class="fw-bold fs-2 text-dark mt-1 mb-0">{{ $pendingConsultations }}</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background-color: #FEF7E0; color: #B06000;">
                        <i class="bi bi-chat-quote fs-3"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Baris 2: Tabel Pesan & Grafik Donut -->
    <div class="row g-4">
        <!-- Poin 3: Mengganti Teks Kaku dengan Tabel Pesan Tanya Ahli Terbaru -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="h5 fw-bold text-dark mb-0">Antrean Pertanyaan Terbaru</h3>
                    <a href="#" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>

                @if($recentConsultations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small uppercase">
                                <tr>
                                    <th class="fw-semibold">Nama Pengirim</th>
                                    <th class="fw-semibold">Cuplikan Pertanyaan</th>
                                    <th class="fw-semibold text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentConsultations as $pesan)
                                    <tr>
                                        <td><span class="fw-semibold text-dark">{{ $pesan->name }}</span></td>
                                        <td><span class="text-muted text-truncate d-inline-block"
                                                style="max-width: 250px;">{{ $pesan->question }}</span></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-akrab-primary"
                                                style="background-color: var(--primary-color); color: white;">Jawab</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-light"></i>
                        Belum ada pertanyaan masuk.
                    </div>
                @endif
            </div>
        </div>

        <!-- Poin 4: Distribusi Penyelesaian dengan Donut Dinamis (Akan Abu-abu jika 0%) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white h-100">
                <h3 class="h5 fw-bold text-dark mb-3">Distribusi Penyelesaian</h3>
                <div class="text-center py-4">
                    <!-- Donut Chart Dinamis dengan CSS Conic Gradient -->
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center shadow-sm position-relative"
                        style="width: 150px; height: 150px; background: conic-gradient(var(--primary-color) 0% {{ $percentSelesai }}%, var(--accent-color) {{ $percentSelesai }}% {{ $percentSelesai + $percentProses }}%, #EAEAEA {{ $percentSelesai + $percentProses }}% 100%);">
                        <!-- Lingkaran putih di tengah -->
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 110px; height: 110px;">
                            <span class="fw-bold fs-3 text-dark">{{ $percentSelesai }}%</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-2">
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="d-flex align-items-center gap-2"><span class="rounded-circle"
                                style="width: 10px; height: 10px; background-color: var(--primary-color);"></span>
                            Selesai</span>
                        <span class="fw-bold">{{ $percentSelesai }}%</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="d-flex align-items-center gap-2"><span class="rounded-circle"
                                style="width: 10px; height: 10px; background-color: var(--accent-color);"></span> Proses
                            Belajar</span>
                        <span class="fw-bold">{{ $percentProses }}%</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small text-muted">
                        <span class="d-flex align-items-center gap-2"><span class="rounded-circle"
                                style="width: 10px; height: 10px; background-color: #EAEAEA;"></span> Belum Mulai</span>
                        <span class="fw-bold">{{ $percentBelum }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection