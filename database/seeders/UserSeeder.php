<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Membuat 1 akun user & 1 akun admin sesuai permintaan.
     * Password di-hash lewat Hash::make() — TIDAK PERNAH disimpan plain text.
     *
     * Pakai updateOrCreate() (bukan create()) supaya seeder ini aman
     * dijalankan berkali-kali (idempotent) — tidak akan error "duplicate
     * entry" kalau ke-run ulang, dan kalau datanya sudah ada, cukup
     * di-update ke nilai terbaru di sini.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'agnesalfa04@gmail.com'],
            [
                'name' => 'Agnes Alfa',
                'username' => null,
                'password' => Hash::make('agnesCantikCetar'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['username' => 'P1234'],
            [
                'name' => 'Ajeng Galuh W.',
                'email' => null,
                'password' => Hash::make('RNDakrabS3'),
                'role' => 'admin',
            ]
        );

        $this->command->info('✓ Akun user (agnesalfa04@gmail.com) dan admin (P1234) berhasil dibuat/diperbarui.');
    }
}
