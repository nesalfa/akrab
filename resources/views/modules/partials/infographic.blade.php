{{-- resources/views/modules/partials/infographic.blade.php --}}
<figure class="text-center mb-3">
    @if($content->hasMedia())
        <img src="{{ asset($content->media_url) }}" alt="{{ $content->alt_text }}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
    @else
        <div class="bg-warning bg-opacity-10 p-5 rounded" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
            <p class="text-muted mb-0">
                <i class="bi bi-image-fill" aria-hidden="true"></i> Infografis sedang disiapkan.
            </p>
        </div>
    @endif
    @if($content->alt_text)
        <figcaption class="mt-2 text-muted small">{{ $content->alt_text }}</figcaption>
    @endif
</figure>

@if($content->content)
    <p class="text-muted small">{{ $content->content }}</p>
@endif
