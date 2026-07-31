<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Ke Akun - AKRAB</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons untuk menggantikan emoji -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Tema Warna Baru selaras dengan Layout Utama */
            --primary-color: #6A4C93;
            /* Ungu Utama */
            --primary-hover: #543A75;
            --accent-color: #FFCA3A;
            /* Kuning Cerah untuk Tombol */
            --accent-hover: #E5B534;
            --bg-pink: #FFF0F5;
            /* Pink Lembut untuk Background */
            --text-dark: #1A1A1A;
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

        /* Indikator Fokus Keyboard WCAG Level AA */
        *:focus {
            outline: 3px solid var(--accent-color) !important;
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
            /* Target sentuhan ramah disabilitas mobile */
            border-radius: 12px;
            border: 2px solid #EEEEEE;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        /* Wrapper untuk Remember Me + Forgot Password */
        .password-remember-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Wrapper untuk label + forgot password link */
        .password-label-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .form-label {
            margin-bottom: 0;
        }

        /* Forgot Password Link */
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

        /* Remember Me Checkbox */
        .form-check {
            margin: 0;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #CCCCCC;
            /* Border visible */
            border-radius: 4px;
            cursor: pointer;
            flex-shrink: 0;
            accent-color: var(--primary-color);
            /* Warna ungu saat checked */
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.95rem;
            margin-left: 0.75rem;
            user-select: none;
            margin-bottom: 0;
        }

        .btn-submit {
            background-color: var(--accent-color);
            color: var(--text-dark);
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
        .btn-submit:focus {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Secondary Link - Belum punya akun */
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

        /* Back Button - Pill Style dengan Border */
        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 2px solid var(--accent-color);
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
            background-color: var(--accent-color);
            color: var(--text-dark);
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
            <!-- Ikon Bootstrap menggantikan emoji gembok -->
            <i class="bi bi-person-circle" style="font-size: 3.5rem; color: var(--primary-color);"
                aria-hidden="true"></i>
            <h1 class="h3 fw-bold mt-2 mb-1" style="color: var(--primary-color);">Masuk Aplikasi</h1>
            <p class="text-secondary small">Silakan masukkan identitas terdaftar Anda</p>
        </div>

        <form action="#" method="POST">
            <!-- INPUT EMAIL -->
            <div class="mb-3">
                <label for="email_input" class="form-label fw-semibold">Email</label>
                <input type="email" id="email_input" class="form-control" placeholder="Contoh: nama@email.com" required
                    autocomplete="username">
            </div>

            <!-- INPUT PASSWORD + FORGOT PASSWORD LINK -->
            <div class="mb-3">
                <div class="password-label-wrapper">
                    <label for="password_input" class="form-label fw-semibold">Kata Sandi</label>

                </div>
                <input type="password" id="password_input" class="form-control" placeholder="Masukkan kata sandi"
                    required autocomplete="current-password">
            </div>

            <!-- REMEMBER ME CHECKBOX -->
            <div class="password-remember-wrapper">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Ingat saya
                    </label>
                </div>
                <a href="#" class="forgot-password-link">Lupa Kata Sandi?</a>
            </div>

            <!-- TOMBOL SUBMIT -->
            <button type="submit" class="btn-submit">
                Masuk Sekarang <i class="bi bi-box-arrow-in-right fs-5" aria-hidden="true"></i>
            </button>
        </form>

        <!-- BELUM PUNYA AKUN LINK -->
        <div class="secondary-link-wrapper">
            <span class="secondary-link-text">
                Belum punya akun?
                <a href="{{ route('register') }}" class="secondary-link">Daftar</a>
            </span>
        </div>

        <!-- BACK BUTTON -->
        <div class="back-button-wrapper">
            <a href="{{ route('home') }}" class="back-button">
                <i class="bi bi-arrow-left-short" aria-hidden="true"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>