<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Migration ini menambahkan:
     * 1. Kolom `content_data` (JSON) di tabel module_content — dipakai untuk
     *    tipe konten yang punya struktur data, bukan sekadar teks/HTML biasa.
     *    Contoh: komik (array halaman/panel), flipcard (array pasangan mitos-fakta),
     *    checklist_interaktif (template rencana tindakan 7 hari).
     *    Kolom `content` yang lama tetap dipakai untuk tipe teks biasa
     *    (pesan_kunci, text_mudah_dibaca, transkrip, subtitle) supaya
     *    data lama tidak perlu dimigrasikan ulang.
     *
     * 2. Menambah 3 nilai baru ke ENUM `type`:
     *    - komik                -> Materi 2 (Pubertas): komik edukatif
     *    - flipcard              -> Materi 12 (Mitos & Fakta): kartu interaktif
     *    - checklist_interaktif -> Materi 15 (Evaluasi & Refleksi): rencana tindakan 7 hari
     */
    public function up(): void
    {
        Schema::table('module_content', function (Blueprint $table) {
            if (!Schema::hasColumn('module_content', 'content_data')) {
                $table->json('content_data')->nullable()->after('content');
            }
        });

        // ALTER ENUM harus pakai raw SQL di MySQL/MariaDB.
        // Kalau database yang dipakai PostgreSQL, ganti bagian ini
        // dengan pendekatan constraint/enum type PostgreSQL yang sesuai.
        DB::statement("ALTER TABLE module_content MODIFY COLUMN type ENUM(
            'video_isyarat',
            'subtitle',
            'transkrip',
            'text_mudah_dibaca',
            'infographic',
            'glosarium',
            'contoh_situasi',
            'pesan_kunci',
            'komik',
            'flipcard',
            'checklist_interaktif'
        )");
    }

    public function down(): void
    {
        Schema::table('module_content', function (Blueprint $table) {
            if (Schema::hasColumn('module_content', 'content_data')) {
                $table->dropColumn('content_data');
            }
        });

        DB::statement("ALTER TABLE module_content MODIFY COLUMN type ENUM(
            'video_isyarat',
            'subtitle',
            'transkrip',
            'text_mudah_dibaca',
            'infographic',
            'glosarium',
            'contoh_situasi',
            'pesan_kunci'
        )");
    }
};
