{{-- resources/views/modules/partials/checklist.blade.php --}}
{{--
content_data diharapkan berbentuk template 7 hari:
[
['hari' => 1, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
... sampai hari 7
]

CATATAN PENTING: partial ini menyimpan isian pengguna di localStorage
browser (per modul, per perangkat) karena saat ini belum ada endpoint
backend untuk menyimpan rencana tindakan / catatan pribadi ke database.
Kalau nanti perlu data ini tersimpan di server (misal supaya bisa
dipantau progresnya), perlu ditambah tabel baru (mis. user_action_plans)
+ endpoint submit — belum saya buatkan karena belum diminta secara
eksplisit. Beri tahu saya kalau ini diperlukan.
--}}
@php
    $rows = $content->content_data ?? [];
    if (empty($rows)) {
        $rows = collect(range(1, 7))->map(fn($h) => [
            'hari' => $h,
            'kebiasaan_sehat' => '',
            'sudah_dilakukan' => false,
            'catatan' => '',
        ])->toArray();
    }
    $storageKey = 'akrab-rencana-tindakan-modul-' . $content->module_id;
@endphp

<div id="checklist-{{ $content->id }}" class="checklist-widget">
    <p class="text-muted small mb-3">
        Isian di bawah ini tersimpan otomatis di perangkatmu (belum tersinkron ke akun).
    </p>

    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle chk-table">
            <thead class="table-light">
                <tr>
                    <th style="width: 8%;">Hari</th>
                    <th style="width: 37%;">Kebiasaan Sehat</th>
                    <th style="width: 15%;" class="text-center">Sudah Dilakukan</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr data-hari="{{ $row['hari'] }}">
                        <td class="text-center fw-bold">{{ $row['hari'] }}</td>
                        <td>
                            <input type="text" class="form-control form-control-sm chk-kebiasaan"
                                value="{{ $row['kebiasaan_sehat'] }}" placeholder="Contoh: tidur 8 jam">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input chk-done" {{ !empty($row['sudah_dilakukan']) ? 'checked' : '' }}>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm chk-catatan"
                                value="{{ $row['catatan'] }}" placeholder="Catatan singkat (opsional)">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-2">
        <label for="notes-{{ $content->id }}" class="form-label fw-bold">📝 Catatan Pribadi</label>
        <textarea id="notes-{{ $content->id }}" class="form-control chk-notes" rows="4"
            placeholder="Tulis apa saja yang ingin kamu ingat dari materi ini..."></textarea>
    </div>
    <p class="text-muted small">Catatan ini bersifat pribadi dan hanya tersimpan di perangkatmu.</p>
</div>

<script>
    (function () {
        var wrap = document.getElementById(@json('checklist-' . $content->id));
        if (!wrap) return;

        var storageKey = @json($storageKey);
        var rows = wrap.querySelectorAll('.chk-table tbody tr');
        var notesEl = wrap.querySelector('.chk-notes');

        function load() {
            var saved = null;
            try { saved = JSON.parse(localStorage.getItem(storageKey) || 'null'); } catch (e) { saved = null; }
            if (!saved) return;

            rows.forEach(function (row) {
                var hari = row.dataset.hari;
                var data = saved.rows && saved.rows[hari];
                if (!data) return;
                row.querySelector('.chk-kebiasaan').value = data.kebiasaan_sehat || '';
                row.querySelector('.chk-done').checked = !!data.sudah_dilakukan;
                row.querySelector('.chk-catatan').value = data.catatan || '';
            });

            if (saved.notes) notesEl.value = saved.notes;
        }

        function save() {
            var data = { rows: {}, notes: notesEl.value };
            rows.forEach(function (row) {
                data.rows[row.dataset.hari] = {
                    kebiasaan_sehat: row.querySelector('.chk-kebiasaan').value,
                    sudah_dilakukan: row.querySelector('.chk-done').checked,
                    catatan: row.querySelector('.chk-catatan').value,
                };
            });
            try { localStorage.setItem(storageKey, JSON.stringify(data)); } catch (e) { /* storage penuh/di-nonaktifkan */ }
        }

        wrap.addEventListener('input', save);
        wrap.addEventListener('change', save);
        load();
    })();
</script>