@extends('layouts.admin')

@section('title', 'Ubah Profil Admin - AKRAB')

@section('admin_content')
    <!-- Notifikasi Sukses / Error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi kesalahan validasi input:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Ubah Profil Admin</h1>
            <p class="text-muted small mb-0">Kelola informasi data akun admin yang sedang aktif digunakan.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-white border shadow-sm rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Card Form Edit Profil -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                <h2 class="h5 fw-bold text-dark mb-4 pb-2 border-bottom">
                    <i class="bi bi-person-gear me-2" style="color: var(--primary-color);"></i> Data Akun Administrator
                </h2>

                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-dark">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" id="name" name="name"
                                class="form-control border-start-0 @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required autocomplete="name"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small text-muted">Nama ini akan ditampilkan pada dashboard sapaan dan sidebar.</div>
                    </div>

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="form-label fw-semibold text-dark">
                            Username / Kode Staf <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-at"></i>
                            </span>
                            <input type="text" id="username" name="username"
                                class="form-control border-start-0 @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" required autocomplete="username"
                                placeholder="Masukkan username admin">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small text-muted">Digunakan untuk login ke panel administrator AKRAB.</div>
                    </div>

                    <!-- Email (Opsional) -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-dark">
                            Alamat Email <span class="text-muted small fw-normal">(Opsional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <input type="email" id="email" name="email"
                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" autocomplete="email"
                                placeholder="nama@email.com (boleh dikosongkan)">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small text-muted">Email kontak administratif atau notifikasi sistem.</div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top justify-content-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-akrab-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Card Info Akun -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="text-center py-3">
                    <div class="rounded-circle bg-warning text-dark fw-bold d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 72px; height: 72px; font-size: 1.75rem;">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-1 text-truncate">{{ $user->name }}</h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
                        <i class="bi bi-shield-check me-1"></i> {{ ucfirst($user->role) }}
                    </span>
                </div>

                <hr class="border-light">

                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between text-muted">
                        <span>Username:</span>
                        <span class="fw-semibold text-dark">{{ $user->username ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Email:</span>
                        <span class="fw-semibold text-dark text-truncate" style="max-width: 170px;">{{ $user->email ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Terdaftar Sejak:</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
