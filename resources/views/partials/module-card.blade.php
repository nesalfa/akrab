<div class="col-md-6 col-lg-4">
    <a href="{{ route('module.show', $module->slug) }}" class="learning-card h-100 p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <!-- Penomoran Dinamis Modul -->
            <span class="badge rounded-pill px-3 py-1.5 fw-bold"
                style="background-color: #D4537E; color: #FFFFFF; font-size: 0.8rem;">
                Modul {{ $module->order ?? $module->id }}
            </span>

            <!-- Badge Tervalidasi Khusus Modul Selain Modul 1 (Sesuai Screenshot Anda) -->
            @if(isset($module->is_validated) && $module->is_validated == true)
                <span
                    class="badge bg-success-subtle text-success px-2.5 py-1.5 border border-success-subtle fw-semibold rounded"
                    style="font-size: 0.75rem;">
                    Tervalidasi
                </span>
            @endif
        </div>

        <h3 class="h5 fw-bold mb-2">{{ $module->title }}</h3>
        <p class="text-muted small mb-4 lh-base" style="min-height: 44px;">
            {{ $module->description }}
        </p>

        <div class="d-flex align-items-center gap-1.5 fw-bold mt-auto" style="color: #D4537E; font-size: 0.95rem;">
            <i class="bi bi-book-half"></i> Mulai Belajar
        </div>
    </a>
</div>