<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah dukungan 2 peran (user & admin) ke tabel `users` bawaan Laravel.
     *
     * Kenapa desainnya begini:
     * - `username` (nullable, unique) -> dipakai KHUSUS untuk akun admin,
     *   isinya kode staf seperti "P1234" (bukan format email).
     * - `email` dibuat nullable -> akun admin tidak wajib punya email untuk
     *   bisa login (mereka login pakai `username`). Akun user tetap WAJIB
     *   isi email saat registrasi (ditegakkan lewat validasi form, bukan
     *   constraint database, supaya admin tanpa email tetap bisa dibuat).
     * - `role` (enum: user/admin, default 'user') -> penentu jalur login &
     *   proteksi halaman admin.
     *
     * CATATAN TEKNIS: mengubah `email` jadi nullable pakai raw SQL
     * (bukan ->nullable()->change()) supaya TIDAK perlu install paket
     * doctrine/dbal — konsisten dengan pendekatan raw SQL yang sudah
     * dipakai di migration `2024_04_add_content_types_and_data_column.php`.
     * Query ini untuk MySQL/MariaDB; kalau project ini pakai PostgreSQL,
     * kabari saya supaya saya sesuaikan sintaksnya.
     *
     * PRASYARAT: migration ini mengasumsikan tabel `users` bawaan Laravel
     * SUDAH ADA (dari migration default `0001_01_01_000000_create_users_table`
     * atau sejenisnya). Kalau project ini masih benar-benar baru dan belum
     * pernah `php artisan migrate`, jalankan migration bawaan itu dulu.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('email');
            $table->enum('role', ['user', 'admin'])->default('user')->after('username');
        });

        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role']);
        });

        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
    }
};
