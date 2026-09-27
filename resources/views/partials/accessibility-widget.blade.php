<!-- =========================================================
     CSS KHUSUS WIDGET & KONTRAS TINGGI
     ========================================================= -->
<style>
    /* 1. Tombol & Panel Widget (Selalu Floating) */
    .a11y-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: #6A4C93;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        z-index: 9999 !important;
        border: 2px solid #FFFFFF;
        transition: all 0.3s ease;
    }

    .a11y-fab:hover {
        transform: scale(1.08);
    }

    /* Ukuran & Jarak Teks */
    html.a11y-text-large {
        font-size: 18px !important;
    }

    html.a11y-wide-spacing * {
        letter-spacing: 0.05em !important;
        line-height: 2 !important;
    }

    /* 2. MODE KONTRAS TINGGI "SAPU JAGAT" (Navy & Putih) */
    body.a11y-high-contrast,
    body.a11y-high-contrast div,
    body.a11y-high-contrast nav,
    body.a11y-high-contrast header,
    body.a11y-high-contrast footer,
    body.a11y-high-contrast main,
    body.a11y-high-contrast section,
    body.a11y-high-contrast form,
    body.a11y-high-contrast ul,
    body.a11y-high-contrast li {
        background-color: #001122 !important;
        background-image: none !important;
    }

    /* Paksa SEMUA teks menjadi putih */
    body.a11y-high-contrast * {
        color: #FFFFFF !important;
        /* Baris border-color cyan telah dihapus dari sini agar teks tidak ada kotaknya */
    }

    /* Kecualikan background untuk gambar dan ikon */
    body.a11y-high-contrast img,
    body.a11y-high-contrast i,
    body.a11y-high-contrast span {
        background-color: transparent !important;
    }

    /* Tautan (Link) & Ikon menjadi Cyan terang */
    body.a11y-high-contrast a,
    body.a11y-high-contrast a *,
    body.a11y-high-contrast i::before,
    body.a11y-high-contrast .nav-link {
        color: #4DD0E1 !important;
    }

    /* FIX 1: Perbaikan Kotak Kuis (Memaksa background kotak pilihan ganda jadi gelap) */
    body.a11y-high-contrast .list-group-item,
    body.a11y-high-contrast label,
    body.a11y-high-contrast .form-check {
        background-color: #001a33 !important;
        border: 2px solid #4DD0E1 !important;
        color: #FFFFFF !important;
    }

    /* FIX 2: Perbaikan Ikon Widget agar tidak belang (Cyan di atas Cyan) */
    body.a11y-high-contrast .a11y-fab {
        background-color: #4DD0E1 !important;
        border-color: #001122 !important;
    }

    body.a11y-high-contrast .a11y-fab i::before {
        color: #001122 !important;
    }

    /* Tombol Interaktif lainnya */
    body.a11y-high-contrast button:not(.a11y-fab),
    body.a11y-high-contrast .btn,
    body.a11y-high-contrast input[type="submit"] {
        background-color: #4DD0E1 !important;
        color: #001122 !important;
        border: none !important;
    }

    body.a11y-high-contrast button:not(.a11y-fab) *,
    body.a11y-high-contrast .btn * {
        color: #001122 !important;
    }

    /* Form Input */
    body.a11y-high-contrast input,
    body.a11y-high-contrast textarea,
    body.a11y-high-contrast select {
        background-color: #001a33 !important;
        color: #FFFFFF !important;
        border: 2px solid #FFFFFF !important;
    }

    /* FIX 3: Teks Placeholder (bayangan dalam form) menjadi Cyan Muda terang */
    body.a11y-high-contrast input::placeholder,
    body.a11y-high-contrast textarea::placeholder {
        color: #B2EBF2 !important;
        opacity: 0.9 !important;
    }

    /* FIX 4: Ikon Mata Password & Kotak Input Group */
    body.a11y-high-contrast .input-group-text,
    body.a11y-high-contrast .input-group .btn {
        background-color: #4DD0E1 !important;
        border: 2px solid #FFFFFF !important;
        border-left: none !important;
        /* Menyatukan border dengan form input */
    }

    /* Paksa ikon di dalam tombol password menjadi Navy gelap agar terlihat */
    body.a11y-high-contrast .input-group-text i,
    body.a11y-high-contrast .input-group-text i::before,
    body.a11y-high-contrast .input-group .btn i,
    body.a11y-high-contrast .input-group .btn i::before {
        color: #001122 !important;
    }

    /* FIX 5: Standardisasi Semua Tombol (Termasuk Masuk, Daftar, dan tombol utama) */
    body.a11y-high-contrast button:not(.a11y-fab),
    body.a11y-high-contrast .btn,
    body.a11y-high-contrast .btn-nav-solid,
    body.a11y-high-contrast .btn-akrab-primary,
    body.a11y-high-contrast .btn-akrab-accent,
    body.a11y-high-contrast input[type="submit"] {
        background-color: #4DD0E1 !important;
        /* Latar Cyan terang */
        color: #001122 !important;
        /* Teks Navy gelap */
        border: 2px solid #4DD0E1 !important;
    }

    /* Pastikan ikon dan teks di dalam tombol solid juga ikut Navy gelap */
    body.a11y-high-contrast button:not(.a11y-fab) *,
    body.a11y-high-contrast .btn *,
    body.a11y-high-contrast .btn-nav-solid *,
    body.a11y-high-contrast .btn-akrab-primary *,
    body.a11y-high-contrast .btn-akrab-accent * {
        color: #001122 !important;
    }

    /* Paksa ikon (pseudo-element) di dalam tombol solid agar ikut berwarna Navy Gelap */
    body.a11y-high-contrast button:not(.a11y-fab) i::before,
    body.a11y-high-contrast .btn i::before,
    body.a11y-high-contrast .btn-nav-solid i::before,
    body.a11y-high-contrast .btn-akrab-primary i::before,
    body.a11y-high-contrast .btn-akrab-accent i::before {
        color: #001122 !important;
    }

    /* Tombol Garis Tepi / Outline (seperti tombol Masuk) */
    body.a11y-high-contrast .btn-nav-outline,
    body.a11y-high-contrast .btn-akrab-outline {
        background-color: #001122 !important;
        /* Latar Navy gelap */
        color: #4DD0E1 !important;
        /* Teks Cyan terang */
        border: 2px solid #4DD0E1 !important;
    }

    body.a11y-high-contrast .btn-nav-outline *,
    body.a11y-high-contrast .btn-akrab-outline * {
        color: #4DD0E1 !important;
    }

    /* Perbaikan Menu Navigasi Aktif (Border kuning diubah jadi Cyan) */
    body.a11y-high-contrast .nav-link-akrab.active-page {
        border-color: #4DD0E1 !important;
        color: #4DD0E1 !important;
    }
</style>

<!-- =========================================================
     HTML WIDGET
     ========================================================= -->
<!-- Tombol Trigger (Tanpa data-bs-toggle agar bisa jalan walau tanpa Bootstrap JS) -->
<button class="a11y-fab" type="button" id="a11yManualTrigger" aria-label="Buka Pengaturan Aksesibilitas">
    <i class="bi bi-universal-access-circle"></i>
</button>

<!-- Panel Offcanvas -->
<div class="offcanvas offcanvas-end shadow" tabindex="-1" id="a11yOffcanvas"
    style="z-index: 10000; transition: transform 0.3s ease-in-out;">
    <div class="offcanvas-header border-bottom">
        <h5 class="fw-bold mb-0"><i class="bi bi-person-wheelchair me-2"></i> Aksesibilitas</h5>
        <button type="button" class="btn-close" id="a11yManualClose" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body">

        <!-- Video Isyarat -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="fw-bold mb-0">Video Isyarat</label>
                <span class="badge bg-secondary" style="font-size: 0.7rem;">Segera Hadir</span>
            </div>
            <div class="form-check form-switch mt-1">
                <input class="form-check-input" type="checkbox" role="switch" disabled>
            </div>
        </div>

        <hr class="my-3">

        <!-- Kontras Tinggi -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="fw-bold mb-0" for="a11yContrast">Kontras Tinggi</label>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input a11y-control" type="checkbox" role="switch" id="a11yContrast">
                </div>
            </div>
        </div>

        <!-- Ukuran Teks -->
        <div class="mb-4">
            <label class="fw-bold mb-2 d-block">Ukuran Teks</label>
            <div class="form-check">
                <input class="form-check-input a11y-control" type="radio" name="a11ySize" id="sizeNormal" value="normal"
                    checked>
                <label class="form-check-label" for="sizeNormal">Normal</label>
            </div>
            <div class="form-check">
                <input class="form-check-input a11y-control" type="radio" name="a11ySize" id="sizeLarge" value="large">
                <label class="form-check-label" for="sizeLarge">Besar</label>
            </div>
        </div>

        <!-- Jarak Teks -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="fw-bold mb-0" for="a11ySpacing">Jarak Antar Baris</label>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input a11y-control" type="checkbox" role="switch" id="a11ySpacing">
                </div>
            </div>
        </div>

        <!-- Reset -->
        <button type="button" class="btn btn-outline-secondary w-100 mt-3" id="a11yReset">Reset ke Awal</button>
    </div>
</div>

<!-- =========================================================
     JAVASCRIPT LOGIC (Sangat Mandiri)
     ========================================================= -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bodyEl = document.body;
        const htmlEl = document.documentElement;

        const contrastToggle = document.getElementById('a11yContrast');
        const spacingToggle = document.getElementById('a11ySpacing');
        const resetBtn = document.getElementById('a11yReset');

        // Elemen Trigger Manual Offcanvas
        const triggerBtn = document.getElementById('a11yManualTrigger');
        const panel = document.getElementById('a11yOffcanvas');
        const closeBtn = document.getElementById('a11yManualClose');

        // FIX 3: Logika Buka-Tutup Mandiri (Tanpa Bootstrap JS)
        triggerBtn.addEventListener('click', function (e) {
            e.preventDefault();
            panel.style.visibility = 'visible';
            panel.classList.add('show');
        });

        closeBtn.addEventListener('click', function () {
            panel.classList.remove('show');
            setTimeout(() => { panel.style.visibility = 'hidden'; }, 300);
        });

        function loadSettings() {
            const settings = JSON.parse(localStorage.getItem('akrab_a11y_v6')) || {
                contrast: false,
                size: 'normal',
                spacing: false
            };

            if (contrastToggle) contrastToggle.checked = settings.contrast;
            if (spacingToggle) spacingToggle.checked = settings.spacing;

            const sizeRadio = document.querySelector(`input[name="a11ySize"][value="${settings.size}"]`);
            if (sizeRadio) sizeRadio.checked = true;

            applySettings(settings);
        }

        function applySettings(settings) {
            // Kontras Tinggi
            if (settings.contrast) bodyEl.classList.add('a11y-high-contrast');
            else bodyEl.classList.remove('a11y-high-contrast');

            // Jarak
            if (settings.spacing) htmlEl.classList.add('a11y-wide-spacing');
            else htmlEl.classList.remove('a11y-wide-spacing');

            // Ukuran
            if (settings.size === 'large') htmlEl.classList.add('a11y-text-large');
            else htmlEl.classList.remove('a11y-text-large');
        }

        function saveSettings() {
            const settings = {
                contrast: contrastToggle.checked,
                size: document.querySelector('input[name="a11ySize"]:checked').value,
                spacing: spacingToggle.checked
            };
            localStorage.setItem('akrab_a11y_v6', JSON.stringify(settings));
            applySettings(settings);
        }

        document.querySelectorAll('.a11y-control').forEach(el => {
            el.addEventListener('change', saveSettings);
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                contrastToggle.checked = false;
                spacingToggle.checked = false;
                document.getElementById('sizeNormal').checked = true;
                saveSettings();
            });
        }

        loadSettings();
    });
</script>