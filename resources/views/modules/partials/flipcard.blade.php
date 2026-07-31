{{-- resources/views/modules/partials/flipcard.blade.php --}}
{{--
content_data diharapkan berbentuk array pasangan mitos-fakta:
[
['mitos' => '...', 'fakta' => '...'],
...
]
Satu kartu = satu mitos, sesuai catatan di PDF breakdown ("Satu kartu hanya
memuat satu mitos agar mudah dipahami").
--}}
@php
    $cards = $content->content_data ?? [];
    $wrapId = 'flipcard-' . $content->id;
@endphp

@if(count($cards) > 0)
    <div id="{{ $wrapId }}" class="flipcard-widget" data-total="{{ count($cards) }}">
        <p class="text-muted small mb-3">
            Kartu <span class="fc-current">1</span> dari {{ count($cards) }}.
            Klik <strong>Buka Fakta</strong> untuk melihat penjelasan sebenarnya.
        </p>

        <div class="card mb-3" style="border-left: 4px solid #7F77DD; background-color: #FBEAF0; min-height: 160px;">
            <div class="card-body">
                <p class="text-uppercase small fw-bold mb-2" style="color: #7F77DD;">❌ Mitos</p>
                <p class="fc-mitos mb-0 fs-5"></p>

                <div class="fc-fakta-box mt-3 pt-3 border-top" style="display:none;">
                    <p class="text-uppercase small fw-bold mb-2" style="color: #1D9E75;">✅ Fakta</p>
                    <p class="fc-fakta mb-0"></p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-success fc-btn-open">Buka Fakta</button>
            <button type="button" class="btn btn-outline-secondary fc-btn-close" style="display:none;">Tutup Kartu</button>
            <a href="#glossaryAccordion" class="btn btn-outline-primary">Lihat Glosarium</a>
            <button type="button" class="btn btn-primary fc-btn-next">Kartu Berikutnya →</button>
        </div>
    </div>

    <script>
        (function () {
            var cards = @json($cards);
            var wrap = document.getElementById(@json($wrapId));
            if (!wrap || !cards.length) return;

            var idx = 0;
            var mitosEl = wrap.querySelector('.fc-mitos');
            var faktaEl = wrap.querySelector('.fc-fakta');
            var faktaBox = wrap.querySelector('.fc-fakta-box');
            var currentEl = wrap.querySelector('.fc-current');
            var btnOpen = wrap.querySelector('.fc-btn-open');
            var btnClose = wrap.querySelector('.fc-btn-close');
            var btnNext = wrap.querySelector('.fc-btn-next');

            function render() {
                var card = cards[idx];
                mitosEl.textContent = card.mitos || '';
                faktaEl.textContent = card.fakta || '';
                currentEl.textContent = idx + 1;
                faktaBox.style.display = 'none';
                btnOpen.style.display = 'inline-block';
                btnClose.style.display = 'none';
            }

            btnOpen.addEventListener('click', function () {
                faktaBox.style.display = 'block';
                btnOpen.style.display = 'none';
                btnClose.style.display = 'inline-block';
            });

            btnClose.addEventListener('click', function () {
                faktaBox.style.display = 'none';
                btnOpen.style.display = 'inline-block';
                btnClose.style.display = 'none';
            });

            btnNext.addEventListener('click', function () {
                idx = (idx + 1) % cards.length;
                render();
            });

            render();
        })();
    </script>
@else
    <div class="bg-warning bg-opacity-10 p-5 rounded text-center">
        <p class="text-muted mb-0">🔄 Kartu mitos & fakta sedang disiapkan.</p>
    </div>
@endif