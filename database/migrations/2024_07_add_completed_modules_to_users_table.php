<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ganti `respondent_token` (session ID anonim) jadi `user_id` (akun
     * sungguhan) di quiz_attempts — sekarang halaman modul mewajibkan
     * login, jadi setiap kuis yang disubmit SELALU punya user yang jelas.
     * Ini juga yang memungkinkan Pre-Test & Post-Test dikorelasikan
     * lintas hari/perangkat (sebelumnya, dengan session ID, itu cuma
     * nyambung kalau dilakukan dalam satu sesi browser yang sama).
     *
     * ASUMSI PENTING: migration ini saya buat dengan anggapan tabel
     * quiz_attempts belum benar-benar dipakai untuk pengumpulan data
     * riset sungguhan (fitur pre/post-test baru saja dipasang, belum
     * live). Kalau ternyata SUDAH ada data respondent_token yang perlu
     * dipertahankan, JANGAN langsung jalankan migration ini — beri tahu
     * saya dulu, supaya saya buatkan migration yang memetakan
     * respondent_token lama ke user_id (kalau memang bisa dipetakan)
     * alih-alih menghapusnya langsung.
     */
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('module_id')->constrained('users')->onDelete('cascade');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['respondent_token', 'type']);
            $table->dropColumn('respondent_token');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type']);
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->string('respondent_token', 100)->after('module_id');
            $table->index(['respondent_token', 'type']);
        });
    }
};