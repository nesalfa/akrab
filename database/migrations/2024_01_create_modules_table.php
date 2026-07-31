<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel modules: menyimpan informasi 12 modul pembelajaran AKRAB
     * 
     * Logika:
     * - id: UUID atau incremental int (primary key)
     * - slug: URL-friendly identifier (misal: "mengenal-tubuhku", "pubertas")
     * - title: Nama modul untuk tampilan (misal: "Mengenal Tubuhku")
     * - description: Penjelasan singkat modul
     * - section: Kategori utama ("mulai-belajar" atau "jaga-diri")
     * - order: Urutan tampilan di menu
     * - is_active: Soft publish (untuk prototipe, semua bisa true)
     * - created_at, updated_at: Timestamps untuk audit
     */
    
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // untuk route: /modul/pubertas
            $table->string('title'); // "Pubertas"
            $table->text('description')->nullable(); // Penjelasan singkat
            $table->enum('section', ['mulai-belajar', 'jaga-diri', 'lainnya']); // Kategori
            $table->integer('order')->default(0); // Urutan di menu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
