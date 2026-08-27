<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Ke Akun - AKRAB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6A4C93;
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            --accent-hover: #E5B534;
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

        /* Standar Aksesibilitas: Indikator Fokus */
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

        .form-label {
            margin-bottom: 0.5rem;
            /* Perbaikan jarak label */
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

        /* Styling Khusus Input Group Kata Sandi */
        .input-group .form-control {
            border-right: none;
        }

        .btn-toggle-password {
            border: 2px solid #EEEEEE;
            border-left: none;
            background-color: transparent;
            color: var(--text-light);
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1rem;
            transition: border-color 0.15s ease-in-out, background-color 0.2s;
        }

        .input-group:focus-within .form-control,
        .input-group:focus-within .btn-toggle-password {
            border-color: var(--primary-color);
        }

        .btn-toggle-password:hover {
            background-color: var(--bg-pink);
            color: var(--primary-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        .password-label-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .forgot-password-link {
            font-size: 0.875rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .forgot-password-link:hover,
        .forgot-password-link:focus-visible {
            text-decoration: underline;
            color: var(--primary-hover);
        }

        /* Tombol Utama - Warna Ungu */
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

        .secondary-link-wrapper {
            text-align: center;
            margin-top: 1.25rem;
        }

        .secondary-link-text {
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .secondary-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .secondary-link:hover,
        .secondary-link:focus-visible {
            color: var(--primary-hover);
            text-decoration: underline;
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
            cursor: pointer;
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
            <i class="bi bi-person-circle" style="font-size: 3.5rem; color: var(--primary-color);"
                aria-hidden="true"></i>
            <h1 class="h3 fw-bold mt-2 mb-1" style="color: var(--primary-color);">Masuk Aplikasi</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="login" class="form-label fw-semibold">Email</label>
                <input type="text" id="login" name="login" class="form-control @error('login') is-invalid @enderror"
                    placeholder="Contoh: nama@email.com" value="{{ old('login') }}" autocomplete="username" required
                    autofocus>
                @error('login')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="password-label-wrapper">
                    <label for="password_input" class="form-label fw-semibold mb-0">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="forgot-password-link">Lupa Kata Sandi?</a>
                </div>

                <div class="input-group">
                    <input type="password" id="password_input" name="password" class="form-control"
                        placeholder="Masukkan kata sandi" required autocomplete="current-password">
                    <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="togglePassword"
                        aria-label="Tampilkan kata sandi">
                        <i class="bi bi-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Teks dan Ikon Tombol Diperbarui -->
            <button type="submit" class="btn-submit">
                Masuk <i class="bi bi-box-arrow-in-right fs-5" aria-hidden="true"></i>
            </button>
        </form>

        <div class="secondary-link-wrapper">
            <span class="secondary-link-text">
                Belum punya akun?
                <a href="{{ route('register') }}" class="secondary-link">Daftar</a>
            </span>
        </div>

        <div class="back-button-wrapper">
            <a href="{{ route('home') }}" class="back-button">
                <i class="bi bi-arrow-left-short" aria-hidden="true"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Script Tampilkan/Sembunyikan Kata Sandi -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password_input');
            const icon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                    togglePassword.setAttribute('aria-label', 'Sembunyikan kata sandi');
                } else {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                    togglePassword.setAttribute('aria-label', 'Tampilkan kata sandi');
                }

                passwordInput.focus();
            });
        });
    </script>
</body>

</html>