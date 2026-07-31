{{-- resources/views/modules/partials/komik.blade.php --}}
{{--
    content_data diharapkan berbentuk array halaman/panel komik:
    [
        ['image' => '/images/komik/xxx-1.jpg', 'caption' => 'Teks panel 1'],
        ['image' => '/images/komik/xxx-2.jpg', 'caption' => 'Teks panel 2'],
        ...
    ]
--}}
@php $pages = $content->content_data ?? []; @endphp

@if(count($pages) > 0)
    <div class="comic-reader d-flex flex-column gap-3">
        @foreach($pages as $i => $page)
            <figure class="mb-0 text-center">
                @if(!empty($page['image']))
                    <img src="{{ asset($page['image']) }}"
                         alt="{{ $page['caption'] ?? ('Panel komik ' . ($i + 1)) }}"
                         class="img-fluid rounded shadow-sm">
                @else
                    <div class="bg-warning bg-opacity-10 p-5 rounded" style="min-height: 180px; display: flex; align-items: center; justify-content: center;">
                        <p class="text-muted mb-0">
                            <i class="bi bi-book-fill" aria-hidden="true"></i> Panel {{ $i + 1 }} — aset komik sedang disiapkan.
                        </p>
                    </div>
                @endif
                @if(!empty($page['caption']))
                    <figcaption class="mt-2 text-muted small">{{ $page['caption'] }}</figcaption>
                @endif
            </figure>
        @endforeach
    </div>
@else
    <div class="bg-warning bg-opacity-10 p-5 rounded text-center">
        <p class="text-muted mb-0">
            <i class="bi bi-book-fill" aria-hidden="true"></i> Komik edukatif sedang disiapkan.
        </p>
    </div>
@endif

@if($content->content)
    <p class="text-muted small mt-3">{{ $content->content }}</p>
@endif
