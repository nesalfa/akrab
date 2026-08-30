{{-- resources/views/modules/partials/video.blade.php --}}
@if($content->hasMedia())
    <div class="ratio ratio-16x9 mb-3" style="background-color: #E1F5EE; border-radius: 8px;">
        <!-- Menggunakan aria-label sebagai pengganti alt text khusus untuk video -->
        <video controls playsinline aria-label="{{ $content->alt_text ?? 'Video Pembelajaran Mengenal Bagian Tubuh' }}">
            <source src="{{ asset($content->media_url) }}" type="video/mp4">
            <p>Video bahasa isyarat tidak dapat ditampilkan. Silakan baca transkrip di bawah.</p>
        </video>
    </div>
@else
    <div class="bg-warning bg-opacity-10 p-5 rounded text-center"
        style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
        <p class="text-muted mb-0">
            <i class="bi bi-camera-reels-fill" aria-hidden="true"></i> Video bahasa isyarat sedang disiapkan.
        </p>
    </div>
@endif

@if($content->content)
    <p class="small text-dark mt-2">{{ $content->content }}</p>
@endif