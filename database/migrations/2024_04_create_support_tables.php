<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel glossary: istilah sulit + definisi per modul
     * Contoh:
     * - module_id=2 (Pubertas), term="Hormon", definition="Zat dalam tubuh..."
     */
    
    public function up(): void
    {
        Schema::create('glossary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('term'); // "Pubertas", "Menstruasi", "Hormon"
            $table->text('definition'); // Arti sederhana
            $table->text('example')->nullable(); // Contoh kalimat
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['module_id', 'term']);
        });

        /**
         * Tabel faq: pertanyaan umum + jawaban
         * Digunakan di halaman FAQ yang bisa di-expand
         * Bisa per modul atau global (module_id nullable)
         */
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->text('question'); // "Apakah pubertas normal?"
            $table->text('answer'); // "Ya, pubertas adalah proses normal..."
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        /**
         * Tabel questions: Tanya Ahli - pertanyaan anonim dari remaja
         * 
         * Status:
         * - pending: belum dijawab
         * - answered: sudah dijawab
         * - archived: ditutup/diabaikan
         * 
         * PENTING: Jangan menyimpan data pribadi remaja!
         * - Tidak ada nama lengkap
         * - Tidak ada email pribadi
         * - Tidak ada no HP
         * - Cukup anonymous_id atau session-based tracking
         */
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('set null');
            $table->text('question_text'); // Pertanyaan dari remaja
            $table->text('answer_text')->nullable(); // Jawaban dari ahli/admin
            $table->enum('status', ['pending', 'answered', 'archived'])->default('pending');
            $table->string('anonymous_id')->nullable(); // Session-based ID, bukan personal data
            $table->timestamps();
            
            $table->index(['status', 'module_id']);
        });

        /**
         * Tabel guides: Panduan untuk guru, orang tua, tenaga kesehatan
         * 
         * role:
         * - guru: panduan mengajar di kelas
         * - orang_tua: panduan mendampingi di rumah
         * - nakes: panduan tenaga kesehatan (dokter, bidan, psikolog)
         */
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['guru', 'orang_tua', 'nakes']);
            $table->string('title'); // "Cara Menggunakan AKRAB di Kelas"
            $table->longText('content'); // Panduan lengkap (HTML)
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guides');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('faq');
        Schema::dropIfExists('glossary');
    }
};
