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

        .otp-input {
            letter-spacing: 8px;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
        }

        .password-field-wrapper {
            position: relative;
        }

        .password-field-wrapper .form-control {
            padding-right: 3rem;
        }

        .password-toggle-btn {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            border-radius: 0 12px 12px 0;
        }

        .password-toggle-btn:hover,
        .password-toggle-btn:focus-visible {
            color: var(--primary-color);
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
            <p class="text-secondary small mb-0">Cek Email Pendamping kamu, lalu masukkan kode 6 digit dan kata sandi baru.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill me-1"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <input type="hidden" name="phone_number" value="{{ old('phone_number', $phone_number) }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor HP Terdaftar</label>
                <input type="text" class="form-control" value="{{ old('phone_number', $phone_number) }}" disabled>
                <div class="form-text">
                    Salah nomor?
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
                <div class="password-field-wrapper">
                    <input type="password"
                           id="password_input"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter"
                           minlength="8"
                           required
                           autocomplete="new-password">
                    <button type="button"
                            class="password-toggle-btn"
                            data-toggle-target="password_input"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false">
                        <i class="bi bi-eye-fill" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation_input" class="form-label fw-semibold">Ulangi Kata Sandi Baru</label>
                <div class="password-field-wrapper">
                    <input type="password"
                           id="password_confirmation_input"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Ketik ulang kata sandi baru"
                           minlength="8"
                           required
                           autocomplete="new-password">
                    <button type="button"
                            class="password-toggle-btn"
                            data-toggle-target="password_confirmation_input"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false">
                        <i class="bi bi-eye-fill" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Ganti Kata Sandi <i class="bi bi-check-lg fs-5" aria-hidden="true"></i>
            </button>
        </form>

        <div class="mb-3 mt-3 text-center">
            <span class="text-muted small">Tidak menerima kode atau sudah kedaluwarsa?</span><br>
            <form method="POST" action="{{ route('password.email') }}" class="d-inline mt-1">
                @csrf
                <input type="hidden" name="phone_number" value="{{ old('phone_number', $phone_number) }}">
                <button type="submit" class="btn btn-link btn-sm p-0 fw-semibold text-decoration-none"
                        style="color: var(--primary-color);">
                    <i class="bi bi-arrow-repeat" aria-hidden="true"></i> Kirim Ulang Kode
                </button>
            </form>
        </div>

        <div class="back-button-wrapper">
            <a href="{{ route('login') }}" class="back-button">
                <i class="bi bi-arrow-left-short" aria-hidden="true"></i> Kembali ke Masuk
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.password-toggle-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.toggleTarget);
                const icon = this.querySelector('i');
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                icon.className = isHidden ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
                this.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            });
        });
    </script>

</body>

</html>