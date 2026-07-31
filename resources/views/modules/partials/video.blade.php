{{-- resources/views/modules/partials/video.blade.php --}}
@if($content->hasMedia())
    <div class="ratio ratio-16x9 mb-3" style="background-color: #E1F5EE; border-radius: 8px;">
        <video controls playsinline>
            <source src="{{ asset($content->media_url) }}" type="video/mp4">
            <p>Video bahasa isyarat tidak dapat ditampilkan. Silakan baca transkrip di bawah.</p>
        </video>
    </div>
    <p class="text-muted small">
        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
        <strong>Tips:</strong> Video ini dilengkapi dengan subtitle. Nyalakan subtitle untuk kemudahan membaca.
    </p>
@else
    <div class="bg-warning bg-opacity-10 p-5 rounded text-center" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
        <p class="text-muted mb-0">
            <i class="bi bi-camera-reels-fill" aria-hidden="true"></i> Video bahasa isyarat sedang disiapkan.
        </p>
    </div>
@endif

@if($content->content)
    <p class="text-muted small mt-2">{{ $content->content }}</p>
@endif
