<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama atau nama samaran
            $table->string('email')->nullable(); // Opsional, untuk identifikasi/notifikasi jika diperlukan nanti
            $table->text('question'); // Pertanyaan dari user
            $table->text('answer')->nullable(); // Jawaban dari admin
            $table->enum('status', ['pending', 'answered'])->default('pending'); // Status pertanyaan
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
