<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan nomor HP yang wajib unik
            $table->string('phone_number')->unique()->after('name');
            // $table->string('phone_number')->nullable()->unique()->after('name');

            // Ubah email agar boleh kosong (nullable) dan hapus aturan unik (unique) jika sebelumnya ada
            $table->string('email')->nullable()->change();

            // Opsional: Drop index unique email jika Laravel bawaanmu sebelumnya menguncinya
            // $table->dropUnique(['email']); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->string('email')->nullable(false)->change();
        });
    }
};
