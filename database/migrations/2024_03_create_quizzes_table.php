<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel quizzes: menyimpan pertanyaan/soal kuis per modul
     * 
     * Struktur:
     * - modules (1) : quizzes (N) = satu modul punya banyak pertanyaan
     * - quizzes (1) : quiz_options (N) = satu pertanyaan punya banyak pilihan jawaban
     * 
     * Type:
     * - benar_salah: 2 pilihan (Benar/Salah)
     * - pilihan_ganda: 3-4 pilihan (A, B, C, D)
     * - skenario: pertanyaan berdasarkan situasi nyata
     * 
     * Contoh implementasi di frontend:
     * - Tampilkan question
     * - Loop setiap quiz_option dan tampilkan label + kalimat
     * - User klik -> update database quiz_responses (opsional, bisa juga hanya di session)
     * - Tampilkan feedback dari is_correct
     */
    
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->longText('question'); // Text pertanyaan, bisa HTML
            $table->enum('type', ['benar_salah', 'pilihan_ganda', 'skenario'])->default('pilihan_ganda');
            $table->integer('order')->default(0); // Urutan pertanyaan dalam modul
            $table->timestamps();
        });

        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->string('label'); // A, B, C, D atau label lain
            $table->text('text'); // Teks pilihan: "Benar", "Ini adalah sentuhan aman", dll
            $table->boolean('is_correct')->default(false); // Jawaban yang benar
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quizzes');
    }
};
