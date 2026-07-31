<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKRAB - Akses Kesehatan Reproduksi Remaja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            /* Mandat WCAG: Font clean & sans-serif */
            color: #1A202C;
            /* Navy gelap untuk kontras tinggi ramah low-vision */
            background-color: #FAFAFA;
        }

        /* Fokus keyboard yang sangat kentara (WCAG 2.2 Success Criteria) */
        *:focus {
            outline: 4px solid #7F77DD !important;
            outline-offset: 2px !important;
        }

        .navbar {
            background-color: #FFFFFF;
            border-bottom: 2px solid #E2E8F0;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #7F77DD !important;
            font-size: 24px;
        }

        .btn-login {
            background-color: #7F77DD;
            color: white !important;
            font-weight: 600;
            min-height: 44px;
            /* Target sentuhan ramah disabilitas mobile */
            padding: 0.5rem 1.5rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background-color: #635AC7;
            transform: translateY(-1px);
        }

        .hero-section {
            background: linear-gradient(180deg, #F3F0FF 0%, #FFFFFF 100%);
            padding: 4rem 0;
        }

        .badge-info {
            background-color: #EBF8FF;
            color: #2B6CB0;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            display: inline-block;
        }

        .role-card {
            border: 2px solid #E2E8F0;
            border-radius: 24px;
            padding: 2rem;
            background-color: #FFFFFF;
            transition: all 0.3s ease;
            text-decoration: none !important;
            color: inherit;
            display: block;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(127, 119, 221, 0.1);
        }

        /* Pembungkus warna pastel sesuai referensi gambar */
        .card-remaja {
            border-color: #E2D9F3;
            background-color: #F9F6FE;
        }

        .card-orangtua {
            border-color: #FBD5E5;
            background-color: #FFF5F7;
        }

        .card-guru {
            border-color: #BEE3F8;
            background-color: #EBF8FF;
        }

        .card-nakes {
            border-color: #B2F5EA;
            background-color: #E6FFFA;
        }

        footer {
            background-color: #1A202C;
            color: #EDF2F7;
            padding: 2rem 0;
            margin-top: 5rem;
        }

        .emergency-link {
            background-color: #E53E3E;
            color: white !important;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            display: inline-block;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- NAVIGASI UTAMA -->
    <nav class="navbar sticky-top" aria-label="Navigasi Utama">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">👋 AKRAB</a>
            <!-- Tombol Masuk Akun Konsep Satu Pintu di Kanan Atas -->
            <a href="#link-ke-halaman-login" class="btn btn-login">Masuk Akun</a>
        </div>
    </nav>

    <!-- HERO SECTION & PENGANTAR PROGRAM -->
    <header class="hero-section text-center">
        <div class="container max-width-700">
            <span class="badge-info mb-3">✨ Platform Edukasi Inklusif</span>
            <h1 class="display-5 fw-bold mb-3">Selamat Datang di AKRAB</h1>
            <p class="lead text-secondary px-lg-5">
                Informasi pubertas yang mudah dipahami, visual, dan inklusif—dirancang khusus untuk teman-teman
                tunarungu, orang tua, guru, dan tenaga kesehatan. Mari berpartisipasi dalam pengembangan
                prototipe R&D ini.
            </p>
        </div>
    </header>

    <!-- PILIHAN PERAN (ROLE SELECTION GRAPHIC) -->
    <main class="container my-5">
        <h2 class="text-center fw-bold mb-2">Kamu siapa? 👋</h2>
        <p class="text-center text-secondary mb-5">Pilih peranmu agar kami bisa memberikan informasi yang tepat.</p>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="role-card card-remaja">
                    <div class="fs-1 mb-2">👦</div>
                    <h3 class="h4 fw-bold text-purple">Remaja</h3>
                    <p class="small text-secondary mb-0">Usia 10–18 tahun yang ingin tahu tentang perubahan masa
                        pubertas.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="role-card card-orangtua">
                    <div class="fs-1 mb-2">👨‍👩‍👦</div>
                    <h3 class="h4 fw-bold text-danger">Orang Tua</h3>
                    <p class="small text-secondary mb-0">Pendamping terbaik anak dalam melewati masa-masa pubertas di
                        rumah[cite: 6].</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="role-card card-guru">
                    <div class="fs-1 mb-2">📚</div>
                    <h3 class="h4 fw-bold text-primary">Guru</h3>
                    <p class="small text-secondary mb-0">Pendidik yang memberikan pemahaman adaptif kepada siswa di
                        sekolah[cite: 6].</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="role-card card-nakes">
                    <div class="fs-1 mb-2">⚕️</div>
                    <h3 class="h4 fw-bold text-success">Tenaga Kesehatan</h3>
                    <p class="small text-secondary mb-0">Profesional medis yang mendampingi konsultasi kesehatan
                        remaja[cite: 6].</p>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER INKLUSIF + NOMOR KEMENKES -->
    <footer>
        <div class="container text-center text-md-start">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <p class="fw-bold mb-1">AKRAB (Akses Kesehatan Reproduksi Remaja yang Adaptif dan Bersahabat)[cite:
                        5]</p>
                    <p class="small text-secondary mb-0">Studi Research and Development mengacu pada standar
                        aksesibilitas konten WCAG 2.2 Level AA[cite: 5].</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <!-- Link Telepon Darurat Kemenkes Ber-Hyperlink -->
                    <a href="tel:119" class="emergency-link">📞 Kontak Darurat: Kemenkes 119</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>