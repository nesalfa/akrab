<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ganti `respondent_token` (session ID anonim) jadi `user_id` (akun
     * sungguhan) di quiz_attempts.
     *
     * VERSI DEFENSIF: setiap langkah dicek dulu sebelum dijalankan
     * (hasColumn / try-catch untuk index), supaya migration ini AMAN
     * dijalankan ulang kalau sebelumnya sempat gagal di tengah jalan
     * (mis. kolom user_id sudah kadung ditambahkan tapi langkah
     * berikutnya gagal, sehingga migration tercatat belum selesai).
     * Tanpa ini, `php artisan migrate` ulang akan error "column already
     * exists" karena mencoba menambah kolom yang sebenarnya sudah ada.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('quiz_attempts', 'user_id')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('module_id')->constrained('users')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('quiz_attempts', 'respondent_token')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                try {
                    $table->dropIndex(['respondent_token', 'type']);
                } catch (\Throwable $e) {
                    // Index mungkin sudah ter-drop di percobaan sebelumnya — aman diabaikan.
                }
            });

            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropColumn('respondent_token');
            });
        }

        try {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->index(['user_id', 'type']);
            });
        } catch (\Throwable $e) {
            // Index user_id+type mungkin sudah ada dari percobaan sebelumnya — aman diabaikan.
        }
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            try {
                $table->dropIndex(['user_id', 'type']);
            } catch (\Throwable $e) {
                //
            }
        });

        if (Schema::hasColumn('quiz_attempts', 'user_id')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (!Schema::hasColumn('quiz_attempts', 'respondent_token')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->string('respondent_token', 100)->after('module_id');
                $table->index(['respondent_token', 'type']);
            });
        }
    }
};