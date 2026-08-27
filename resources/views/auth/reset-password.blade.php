<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - AKRAB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --bg-pink: #FFF0F5;
            --text-dark: #1A1A1A;
            --text-light: #4A4A4A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-pink);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        *:focus-visible {
            outline: 3px solid var(--primary-hover) !important;
            outline-offset: 2px !important;
        }

        .login-card {
            background-color: #FFFFFF;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 30px rgba(106, 76, 147, 0.08);
            width: 100%;
            max-width: 450px;
            border: 1px solid #EAEAEA;
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

        .form-text {
            font-size: 0.8rem;
        }

        /* Input kode OTP dibuat menonjol & mudah dibaca — huruf besar, berjarak */
        .otp-input {
            letter-spacing: 8px;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 700;
            width: 100%;
            min-height: 48px;
            border-radius: 12px;
            border: none;
            margin-top: 1.5rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover,
        .btn-submit:focus-visible {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .back-button:hover,
        .back-button:focus-visible {
            background-color: var(--primary-color);
            color: #FFFFFF;
        }

        .back-button-wrapper {
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: var(--primary-color);" aria-hidden="true"></i>
            <h1 class="h3 fw-bold mt-2 mb-1" style="color: var(--primary-color);">Masukkan Kode OTP</h1>
            <p class="text-secondary small mb-0">Cek email kamu, lalu masukkan kode 6 digit dan kata sandi baru.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="text" class="form-control" value="{{ old('email', $email) }}" disabled>
                <div class="form-text">
                    Salah email?
                    <a href="{{ route('password.request') }}" style="color: var(--primary-color); font-weight: 600;">Ulangi dari awal</a>.
                </div>
            </div>

            <div class="mb-3">
                <label for="otp_input" class="form-label fw-semibold">Kode OTP</label>
                <input type="text"
                       id="otp_input"
                       name="otp"
                       class="form-control otp-input @error('otp') is-invalid @enderror"
                       placeholder="000000"
                       inputmode="numeric"
                       pattern="[0-9]{6}"
                       maxlength="6"
                       autocomplete="one-time-code"
                       required
                       autofocus>
                @error('otp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Kode berlaku 15 menit sejak dikirim.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_input" class="form-label fw-semibold">Kata Sandi Baru</label>
                <input type="password"
                       id="password_input"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter"
                       minlength="8"
                       required
                       autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation_input" class="form-label fw-semibold">Ulangi Kata Sandi Baru</label>
                <input type="password"
                       id="password_confirmation_input"
                       name="password_confirmation"
                       class="form-control"
                       placeholder="Ketik ulang kata sandi baru"
                       minlength="8"
                       required
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn-submit">
                Ganti Kata Sandi <i class="bi bi-check-lg fs-5" aria-hidden="true"></i>
            </button>
        </form>

        <div class="back-button-wrapper">
            <a href="{{ route('login') }}" class="back-button">
                <i class="bi bi-arrow-left-short" aria-hidden="true"></i> Kembali ke Masuk
            </a>
        </div>
    </div>

</body>

</html>