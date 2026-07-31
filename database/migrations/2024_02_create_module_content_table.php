<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel module_content: menyimpan berbagai tipe konten dalam setiap modul
     * 
     * Logika:
     * - Satu modul bisa punya multiple content (video isyarat, teks, infografis, dll)
     * - type: enum untuk membedakan jenis konten (video, text, infographic, glossary, transcript, dll)
     * - content: field JSON atau text biasa untuk menyimpan data konten
     * - media_url: URL placeholder/CDN untuk video, infografis (optional)
     * - order: urutan tampilan konten dalam modul
     * 
     * Contoh data:
     * - type=video_isyarat, media_url="/videos/pubertas.mp4", content={subtitle_text}
     * - type=text, content={paragraf panjang teks mudah dibaca}
     * - type=infographic, media_url="/images/pubertas.svg", content={alt_text + deskripsi}
     */
    
    public function up(): void
    {
        Schema::create('module_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            
            // Tipe konten: video_isyarat, text, infographic, glosarium, transkrip, dll
            $table->enum('type', [
                'video_isyarat',      // Video dengan bahasa isyarat
                'subtitle',           // Subtitle/caption
                'transkrip',          // Teks transkrip video
                'text_mudah_dibaca',  // Paragraf teks utama
                'infographic',        // Gambar/diagram
                'glosarium',          // Istilah + definisi
                'contoh_situasi',     // Cerita/skenario pendek
                'pesan_kunci'         // Highlight pesan utama
            ]);
            
            $table->string('title')->nullable(); // Label: "Penjelasan Video", "Infografis Pubertas"
            $table->longText('content'); // Konten utama (HTML, text, atau JSON)
            $table->string('media_url')->nullable(); // URL video, gambar, dsb
            $table->string('alt_text')->nullable(); // Deskripsi untuk aksesibilitas
            $table->integer('order')->default(0); // Urutan dalam modul
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['module_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_content');
    }
};
