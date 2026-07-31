<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - AKRAB</title>

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

        /* Secondary Link - Sudah punya akun */
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
            <!-- Ikon Bootstrap menggantikan emoji plus -->
            <i class="bi bi-person-plus-fill" style="font-size: 3.5rem; color: var(--primary-color);"
                aria-hidden="true"></i>
            <h1 class="h3 fw-bold mt-2 mb-1" style="color: var(--primary-color);">Daftar Keanggotaan</h1>
            <p class="text-secondary small">Silakan masukkan identitas Anda</p>
        </div>

        <form action="#" method="POST">
            <!-- INPUT NAMA -->
            <div class="mb-3">
                <label for="name_input" class="form-label fw-semibold">Nama</label>
                <input type="text" id="name_input" class="form-control" placeholder="Contoh: Rina Wijaya" required
                    autocomplete="name">
            </div>

            <!-- INPUT EMAIL -->
            <div class="mb-3">
                <label for="email_input" class="form-label fw-semibold">Email</label>
                <input type="email" id="email_input" class="form-control" placeholder="Contoh: nama@email.com" required
                    autocomplete="email">
            </div>

            <!-- INPUT PASSWORD -->
            <div class="mb-3">
                <label for="password_input" class="form-label fw-semibold">Kata Sandi</label>
                <input type="password" id="password_input" class="form-control" placeholder="Masukkan kata sandi"
                    required autocomplete="new-password">
            </div>

            <!-- TOMBOL SUBMIT -->
            <button type="submit" class="btn-submit">
                Daftar Sekarang <i class="bi bi-box-arrow-in-right fs-5" aria-hidden="true"></i>
            </button>
        </form>

        <!-- SUDAH PUNYA AKUN LINK -->
        <div class="secondary-link-wrapper">
            <span class="secondary-link-text">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="secondary-link">Masuk</a>
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