<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel quiz_attempts: mencatat setiap jawaban kuis (Pre-Test & Post-Test)
     * untuk keperluan riset "learning gain" (peningkatan pemahaman sebelum
     * vs sesudah belajar materi) — sesuai footer situs: "Studi Research and
     * Development WCAG 2.2 Level AA".
     *
     * Desain:
     * - `type` membedakan apakah jawaban ini dari Pre-Test atau Post-Test.
     *   Soal & option-nya identik (lihat show.blade.php), jadi `type` inilah
     *   satu-satunya pembeda saat menganalisis "naik dari berapa ke berapa".
     * - `respondent_token` = session ID Laravel, BUKAN data pribadi/akun.
     *   Ini konsisten dengan pola yang sudah dipakai di
     *   ModuleController::submitQuestion() ("Tidak menyimpan data pribadi
     *   user!"). Satu respondent_token yang sama dipakai untuk Pre-Test dan
     *   Post-Test SELAMA dilakukan dalam satu sesi browser yang sama
     *   (durasi session Laravel diatur di config/session.php — default
     *   120 menit). Kalau jaraknya lebih lama dari itu / beda perangkat,
     *   token akan beda dan tidak bisa otomatis dikorelasikan.
     * - Tabel ini APPEND-ONLY (tidak ada unique constraint per
     *   respondent+quiz+type) — sengaja, supaya tidak ada data hilang kalau
     *   ada percobaan ulang. Deduplikasi (mis. ambil attempt pertama/
     *   terakhir per responden) dilakukan saat analisis, bukan di database.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('selected_option_id')->constrained('quiz_options')->onDelete('cascade');
            $table->boolean('is_correct');
            $table->enum('type', ['pre', 'post']);
            $table->string('respondent_token', 100);
            $table->timestamps();

            $table->index(['module_id', 'type']);
            $table->index(['respondent_token', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
