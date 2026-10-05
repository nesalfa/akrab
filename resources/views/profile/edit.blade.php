<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - AKRAB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            --bg-pink: #FFF0F5;
            --text-dark: #1A1A1A;
            --text-light: #4A4A4A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-pink);
            color: var(--text-dark);
            min-height: 100vh;
        }

        *:focus-visible {
            outline: 3px solid var(--primary-hover) !important;
            outline-offset: 2px !important;
        }

        /* Satu kartu di tengah */
        .profile-container {
            max-width: 640px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            background-color: #FFFFFF;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(106, 76, 147, 0.08);
            border: 1px solid #EAEAEA;
        }

        .profile-header {
            text-align: center;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #EEEEEE;
        }

        .profile-header .avatar {
            font-size: 4.5rem;
            line-height: 1;
            color: var(--primary-color);
        }

        .profile-badge {
            display: inline-block;
            background-color: var(--bg-pink);
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            margin-top: 0.5rem;
        }

        .form-control {
            min-height: 48px;
            border-radius: 12px;
            border: 2px solid #EEEEEE;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .form-control.is-invalid {
            border-color: #C7365F;
        }

        /* Kartu Progress Belajar */
        .progress-box {
            background: linear-gradient(135deg, #6A4C93 0%, #543A75 100%);
            color: #FFFFFF;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.75rem;
        }

        .progress {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            height: 12px;
        }

        .progress-bar {
            background-color: var(--accent-color);
            border-radius: 10px;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 700;
            min-height: 48px;
            width: 100%;
            border-radius: 12px;
            border: none;
            padding: 0.75rem 2rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover,
        .btn-submit:focus-visible {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .link-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            min-height: 48px;
        }

        .link-back:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        @media (max-width: 575.98px) {
            .profile-card {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>
</head>

<body>

    <main class="profile-container">
        <div class="profile-card">

            <!-- 1. Nama + badge -->
            <div class="profile-header">
                <i class="bi bi-person-circle avatar" aria-hidden="true"></i>
                <h1 class="h4 fw-bold mt-2 mb-0">{{ $user->name }}</h1>
                <span class="profile-badge">Remaja / Pengguna</span>
            </div>

            <!-- 2. Profil & Progres Belajar -->
            <h2 class="h5 fw-bold mb-3" style="color: var(--primary-color);">Profil &amp; Progres Belajar</h2>

            <div class="progress-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Progres Modul Belajar</span>
                    <span class="badge bg-warning text-dark fw-bold">{{ $progressPercentage }}% Selesai</span>
                </div>
                <div class="progress mb-2" role="progressbar" aria-label="Progres belajar"
                    aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
                </div>
                <small class="text-light opacity-75">Kamu telah menyelesaikan {{ $completedModules }} dari total
                    {{ $totalModules }} modul pembelajaran.</small>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i> {{ session('status') }}
                </div>
            @endif

            <!-- 3. Form: nama, no HP, email, tombol -->
            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label fw-semibold">Nomor HP (ID Utama)</label>
                    <input type="text" inputmode="numeric" pattern="[0-9]*" id="phone_number" name="phone_number"
                        class="form-control @error('phone_number') is-invalid @enderror"
                        value="{{ old('phone_number', $user->phone_number) }}" required autocomplete="tel">
                    <div class="form-text mt-1 text-muted">Nomor HP yang digunakan untuk masuk ke aplikasi.</div>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email Pendamping Terpercaya</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required autocomplete="email">
                    <div class="form-text mt-1 text-muted">Digunakan untuk menerima kode OTP saat lupa kata sandi.
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Simpan Perubahan <i class="bi bi-save-fill" aria-hidden="true"></i>
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('belajar') }}" class="link-back">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Belajar
                    </a>
                </div>
            </form>

        </div>
    </main>

</body>

</html>