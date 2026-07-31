<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model ModuleContent
 *
 * Representasi tabel 'module_content'
 * Menyimpan berbagai tipe konten dalam satu modul.
 *
 * Field `content`      : dipakai untuk konten berbentuk teks/HTML sederhana
 *                         (pesan_kunci, text_mudah_dibaca, transkrip, subtitle).
 * Field `content_data` : dipakai untuk konten yang punya struktur data (JSON),
 *                         misalnya komik (array halaman), flipcard (array
 *                         pasangan mitos-fakta), atau checklist_interaktif
 *                         (template rencana tindakan 7 hari).
 *
 * Type: video_isyarat, subtitle, transkrip, text_mudah_dibaca, infographic,
 *       glosarium, contoh_situasi, pesan_kunci, komik, flipcard, checklist_interaktif
 *
 * Lokasi file: app/Models/ModuleContent.php
 */
class ModuleContent extends Model
{
    protected $table = 'module_content';

    protected $fillable = [
        'module_id',
        'type',
        'title',
        'content',
        'content_data',
        'media_url',
        'alt_text',
        'order',
    ];

    protected $casts = [
        'content_data' => 'array',
    ];

    /**
     * Relasi: Banyak content dimiliki oleh satu module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Scope: Filter konten berdasarkan tipe
     * Contoh: ModuleContent::byType('video_isyarat')->get();
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Ambil konten dalam urutan tertentu
     * Contoh: ModuleContent::ordered()->get();
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Method: Cek apakah konten ini memiliki media (video, gambar, dll)
     */
    public function hasMedia()
    {
        return !is_null($this->media_url);
    }

    /**
     * Method: Cek apakah konten ini memiliki data terstruktur (JSON)
     */
    public function hasStructuredData()
    {
        return !is_null($this->content_data);
    }

    public function isVideo()
    {
        return $this->type === 'video_isyarat';
    }

    public function isInfographic()
    {
        return $this->type === 'infographic';
    }

    public function isText()
    {
        return $this->type === 'text_mudah_dibaca';
    }

    public function isKomik()
    {
        return $this->type === 'komik';
    }

    public function isFlipcard()
    {
        return $this->type === 'flipcard';
    }

    public function isChecklist()
    {
        return $this->type === 'checklist_interaktif';
    }

    /**
     * Nama file partial Blade yang harus dipakai untuk merender tipe ini.
     * Dipakai oleh modules/show.blade.php supaya switch/case besar
     * tidak perlu ditulis ulang di view.
     */
    public function partialView(): ?string
    {
        return match ($this->type) {
            'pesan_kunci' => 'modules.partials.pesan-kunci',
            'video_isyarat' => 'modules.partials.video',
            'subtitle' => 'modules.partials.subtitle',
            'text_mudah_dibaca' => 'modules.partials.text',
            'infographic' => 'modules.partials.infographic',
            'transkrip' => 'modules.partials.transkrip',
            'komik' => 'modules.partials.komik',
            'flipcard' => 'modules.partials.flipcard',
            'checklist_interaktif' => 'modules.partials.checklist',
            default => null,
        };
    }

    /**
     * Label teks untuk header section (tanpa emoji — ikon diambil terpisah
     * lewat sectionIcon() supaya blade bisa pakai Bootstrap Icons, bukan emoji).
     */
    public function sectionLabel(): string
    {
        return match ($this->type) {
            'pesan_kunci' => $this->title ?: 'Pesan Kunci',
            'video_isyarat' => $this->title ?: 'Video Bahasa Isyarat',
            'subtitle' => $this->title ?: 'Subtitle',
            'text_mudah_dibaca' => $this->title ?: 'Penjelasan',
            'infographic' => $this->title ?: 'Infografis',
            'transkrip' => $this->title ?: 'Transkrip',
            'komik' => $this->title ?: 'Komik Edukatif',
            'flipcard' => $this->title ?: 'Mitos vs Fakta',
            'checklist_interaktif' => $this->title ?: 'Rencana Tindakan & Catatan',
            default => $this->title ?: 'Konten',
        };
    }

    /**
     * Kelas Bootstrap Icons (bi-*) yang mewakili tipe konten ini.
     * Dipakai di blade sebagai: <i class="bi {{ $content->sectionIcon() }}" aria-hidden="true"></i>
     */
    public function sectionIcon(): string
    {
        return match ($this->type) {
            'pesan_kunci' => 'bi-lightbulb-fill',
            'video_isyarat' => 'bi-play-btn-fill',
            'subtitle' => 'bi-badge-cc-fill',
            'text_mudah_dibaca' => 'bi-file-text-fill',
            'infographic' => 'bi-image-fill',
            'transkrip' => 'bi-file-earmark-text-fill',
            'komik' => 'bi-book-fill',
            'flipcard' => 'bi-arrow-repeat',
            'checklist_interaktif' => 'bi-check2-square',
            default => 'bi-file-earmark-fill',
        };
    }
}