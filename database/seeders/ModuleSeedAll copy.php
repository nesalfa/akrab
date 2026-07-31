<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ModuleSeedAll
 * 
 * Seeder lengkap untuk semua 12 modul AKRAB
 * Mencakup: konten multimedia, glosarium, kuis, dan FAQ
 * 
 * Cara pakai:
 * 1. Simpan di: database/seeders/ModuleSeedAll.php
 * 2. Edit database/seeders/DatabaseSeeder.php:
 *    $this->call(ModuleSeedAll::class);
 * 3. Jalankan: php artisan db:seed
 */
class ModuleSeedAll extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('questions')->delete();
        DB::table('faq')->delete();
        DB::table('glossary')->delete();
        DB::table('quiz_options')->delete();
        DB::table('quizzes')->delete();
        DB::table('module_content')->delete();
        DB::table('modules')->delete();

        echo "🧹 Data lama dihapus.\n";

        // ========== MODUL 1: MENGENAL TUBUHKU ==========
        $this->seedMengenalTubuhku();

        // ========== MODUL 2: PUBERTAS ==========
        $this->seedPubertas();

        // ========== MODUL 3: MENSTRUASI ==========
        $this->seedMenstruasi();

        // ========== MODUL 4: MIMPI BASAH ==========
        $this->seedMimpiBasah();

        // ========== MODUL 5: KEBERSIHAN ORGAN REPRODUKSI ==========
        $this->seedKebersihan();

        // ========== MODUL 6: RELASI SEHAT ==========
        $this->seedRelasiSehat();

        // ========== MODUL 7: BATASAN TUBUH ==========
        $this->seedBatasanTubuh();

        // ========== MODUL 8: CONSENT (PERSETUJUAN) ==========
        $this->seedConsent();

        // ========== MODUL 9: SENTUHAN AMAN DAN TIDAK AMAN ==========
        $this->seedSentuhanAman();

        // ========== MODUL 10: PENCEGAHAN KEKERASAN SEKSUAL ==========
        $this->seedPencegahanKekerasan();

        // ========== MODUL 11: CITRA TUBUH (BODY IMAGE) ==========
        $this->seedCitraTubuh();

        // ========== MODUL 12: KEAMANAN DIGITAL ==========
        $this->seedKeamananDigital();

        $this->command->info('✅ Semua 12 modul AKRAB sudah di-seed!');
    }

    private function seedMengenalTubuhku()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'mengenal-tubuhku',
            'title' => 'Mengenal Tubuhku',
            'description' => 'Belajar tentang bagian-bagian tubuh dan fungsinya, termasuk organ reproduksi',
            'section' => 'mulai-belajar',
            'order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Content
        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Tubuh kita berharga. Bagian tubuh pribadi perlu dijaga dan tidak boleh disentuh tanpa izin.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'video_isyarat',
            'title' => 'Video Pengenalan Tubuh',
            'content' => 'Penjelasan organ reproduksi dalam bahasa isyarat',
            'media_url' => '/videos/placeholder-mengenal-tubuh.mp4',
            'alt_text' => 'Video penyaji bahasa isyarat menjelaskan anatomi tubuh laki-laki dan perempuan',
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Penjelasan Tubuh',
            'content' => '<p>Tubuh setiap orang berharga dan unik. Kita perlu mengenal nama bagian tubuh dengan benar agar dapat menjaga kesehatan dan melindungi diri.</p><p><strong>Organ reproduksi laki-laki:</strong> Penis, Skrotum, Testis</p><p><strong>Organ reproduksi perempuan:</strong> Vulva, Vagina, Rahim, Ovarium</p>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Organ reproduksi', 'definition' => 'Bagian tubuh yang berkaitan dengan pubertas dan fungsi reproduksi.'],
            ['term' => 'Penis', 'definition' => 'Organ luar laki-laki tempat keluarnya urine dan semen.'],
            ['term' => 'Vulva', 'definition' => 'Bagian luar organ reproduksi perempuan.'],
            ['term' => 'Rahim', 'definition' => 'Organ di dalam tubuh perempuan tempat janin tumbuh.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kuis
        $quiz1 = DB::table('quizzes')->insertGetId([
            'module_id' => $moduleId,
            'question' => 'Apakah penis adalah bagian tubuh pribadi yang harus dilindungi?',
            'type' => 'benar_salah',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quiz_options')->insert([
            ['quiz_id' => $quiz1, 'label' => 'A', 'text' => 'Benar', 'is_correct' => true, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['quiz_id' => $quiz1, 'label' => 'B', 'text' => 'Salah', 'is_correct' => false, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        echo "✓ Modul 1: Mengenal Tubuhku\n";
    }

    private function seedPubertas()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'pubertas',
            'title' => 'Pubertas: Perubahan pada Tubuhku',
            'description' => 'Pahami perubahan tubuh dan emosi saat pubertas',
            'section' => 'mulai-belajar',
            'order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Pubertas adalah proses normal dan sehat. Perubahan tubuh yang terjadi adalah tanda bahwa tubuh kamu sedang berkembang menuju dewasa.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Apa itu Pubertas?',
            'content' => '<p><strong>Pubertas</strong> adalah masa ketika tubuh berubah dari tubuh anak menjadi tubuh remaja. Perubahan ini terjadi karena hormon dalam tubuh.</p><p><strong>Perubahan pada laki-laki:</strong></p><ul><li>Tinggi badan bertambah cepat</li><li>Suara berubah lebih dalam</li><li>Tumbuh kumis dan jenggot</li><li>Otot bertambah besar</li><li>Tumbuh rambut di ketiak dan area kemaluan</li></ul><p><strong>Perubahan pada perempuan:</strong></p><ul><li>Tinggi badan bertambah cepat</li><li>Payudara berkembang</li><li>Pinggul melebar</li><li>Menstruasi mulai terjadi</li><li>Tumbuh rambut di ketiak dan area kemaluan</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Pubertas', 'definition' => 'Masa perubahan tubuh dari anak menuju remaja dan dewasa.'],
            ['term' => 'Hormon', 'definition' => 'Zat dalam tubuh yang menyebabkan perubahan saat pubertas.'],
            ['term' => 'Menstruasi', 'definition' => 'Keluarnya darah dari vagina setiap bulan pada perempuan.'],
            ['term' => 'Jerawat', 'definition' => 'Bintik atau luka di kulit yang sering muncul saat pubertas.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kuis
        $quiz1 = DB::table('quizzes')->insertGetId([
            'module_id' => $moduleId,
            'question' => 'Pubertas adalah hal yang normal?',
            'type' => 'benar_salah',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quiz_options')->insert([
            ['quiz_id' => $quiz1, 'label' => 'A', 'text' => 'Benar', 'is_correct' => true, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['quiz_id' => $quiz1, 'label' => 'B', 'text' => 'Salah', 'is_correct' => false, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        echo "✓ Modul 2: Pubertas\n";
    }

    private function seedMenstruasi()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'menstruasi',
            'title' => 'Menstruasi: Siklus Bulanan yang Normal',
            'description' => 'Pahami menstruasi dan cara merawat diri saat menstruasi',
            'section' => 'mulai-belajar',
            'order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Menstruasi adalah proses alami yang dialami hampir semua perempuan. Ini bukan penyakit, melainkan tanda bahwa tubuhmu sehat dan berkembang.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Apa itu Menstruasi?',
            'content' => '<p><strong>Menstruasi</strong> adalah keluarnya darah dari vagina yang terjadi setiap bulan pada perempuan.</p><p><strong>Siklus menstruasi rata-rata 28 hari, tapi bisa berkisar 21-35 hari.</strong></p><p><strong>Perdarahan biasanya berlangsung 3-7 hari.</strong></p><p><strong>Hal-hal yang normal saat menstruasi:</strong></p><ul><li>Kram perut atau nyeri payudara</li><li>Perubahan mood</li><li>Kelelahan</li><li>Jerawat</li></ul><p><strong>Cara merawat diri saat menstruasi:</strong></p><ul><li>Ganti pembalut atau tampon setiap 4-6 jam</li><li>Bersihkan area kemaluan dengan air bersih</li><li>Minum cukup air</li><li>Istirahat yang cukup</li><li>Olahraga ringan bisa membantu kram perut</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Menstruasi', 'definition' => 'Keluarnya darah dari vagina setiap bulan.'],
            ['term' => 'Siklus menstruasi', 'definition' => 'Periode berulang setiap bulan yang dimulai dari hari pertama menstruasi hingga hari pertama menstruasi berikutnya.'],
            ['term' => 'Pembalut', 'definition' => 'Produk untuk menyerap darah menstruasi.'],
            ['term' => 'Kram', 'definition' => 'Rasa sakit atau nyeri di perut saat menstruasi.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 3: Menstruasi\n";
    }

    private function seedMimpiBasah()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'mimpi-basah',
            'title' => 'Mimpi Basah dan Emisi Sperma',
            'description' => 'Memahami mimpi basah sebagai bagian normal pubertas laki-laki',
            'section' => 'mulai-belajar',
            'order' => 4,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Mimpi basah adalah hal yang normal terjadi pada laki-laki saat pubertas. Ini bukan hal yang memalukan atau berbahaya.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Apa itu Mimpi Basah?',
            'content' => '<p><strong>Mimpi basah (nocturnal emission)</strong> adalah keluarnya semen dari penis saat tidur, biasanya disertai mimpi yang bersifat seksual.</p><p><strong>Fakta tentang mimpi basah:</strong></p><ul><li>Terjadi pada mayoritas laki-laki saat pubertas</li><li>Ini adalah proses fisiologis tubuh yang normal</li><li>Terjadi karena produksi testosteron yang meningkat</li><li>Tidak ada yang salah atau memalukan tentang ini</li><li>Biasanya terjadi 1-2 kali per bulan, tapi bisa lebih sering atau lebih jarang</li></ul><p><strong>Apa yang bisa dilakukan:</strong></p><ul><li>Ganti celana dalam dan sprei yang basah</li><li>Cuci celana dan sprei dengan air bersih</li><li>Ini adalah bagian dari pertumbuhan yang sehat</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Mimpi basah', 'definition' => 'Keluarnya semen saat tidur, biasanya terjadi saat pubertas.'],
            ['term' => 'Semen', 'definition' => 'Cairan yang dihasilkan organ reproduksi laki-laki yang mengandung sperma.'],
            ['term' => 'Sperma', 'definition' => 'Sel reproduksi laki-laki yang diperlukan untuk pembuahan.'],
            ['term' => 'Testosteron', 'definition' => 'Hormon laki-laki yang menyebabkan perubahan pada pubertas.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 4: Mimpi Basah\n";
    }

    private function seedKebersihan()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'kebersihan-organ-reproduksi',
            'title' => 'Kebersihan Organ Reproduksi',
            'description' => 'Cara menjaga kebersihan dan kesehatan organ reproduksi',
            'section' => 'mulai-belajar',
            'order' => 5,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Menjaga kebersihan organ reproduksi adalah bagian penting dari merawat kesehatan tubuhmu secara menyeluruh.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Cara Menjaga Kebersihan',
            'content' => '<p><strong>Untuk semua orang (laki-laki dan perempuan):</strong></p><ul><li>Cuci tangan sebelum dan sesudah ke kamar mandi</li><li>Bersihkan area kemaluan dengan air bersih setiap hari</li><li>Ganti celana dalam setiap hari, lebih sering jika basah atau berkeringat</li><li>Cuci celana dalam dengan sabun dan air yang bersih</li></ul><p><strong>Tips khusus perempuan:</strong></p><ul><li>Bersihkan vulva dengan gerakan dari depan ke belakang (tidak sebaliknya)</li><li>Jangan menggunakan sabun kuat untuk area kemaluan</li><li>Ganti pembalut secara teratur saat menstruasi</li></ul><p><strong>Tips khusus laki-laki:</strong></p><ul><li>Retrak kulit penutup penis (fimosis) saat membersihkan</li><li>Cuci penis dengan lembut saat mandi</li><li>Keringkan dengan baik setelah mandi</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Kebersihan intim', 'definition' => 'Praktik menjaga kebersihan area kemaluan.'],
            ['term' => 'Vulva', 'definition' => 'Bagian luar organ reproduksi perempuan.'],
            ['term' => 'Fimosis', 'definition' => 'Kulit penutup penis pada laki-laki.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 5: Kebersihan Organ Reproduksi\n";
    }

    private function seedRelasiSehat()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'relasi-sehat',
            'title' => 'Relasi Sehat dan Pertemanan',
            'description' => 'Membangun hubungan yang sehat dengan orang lain',
            'section' => 'jaga-diri',
            'order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Relasi sehat dibangun atas dasar saling menghormati, percaya, dan mendukung satu sama lain. Kamu berhak memilih teman dan pacar yang menghargai dirimu.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Ciri Relasi yang Sehat',
            'content' => '<p><strong>Relasi sehat memiliki ciri-ciri:</strong></p><ul><li>Saling menghormati perbedaan pendapat</li><li>Saling mendukung dalam mencapai tujuan</li><li>Jujur dan terbuka dalam komunikasi</li><li>Memberikan ruang pribadi dan privasi</li><li>Tidak ada kekerasan atau intimidasi</li><li>Kedua belah pihak memiliki suara yang sama</li></ul><p><strong>Tanda relasi yang tidak sehat:</strong></p><ul><li>Satu pihak selalu mengontrol yang lain</li><li>Ada ancaman atau kekerasan</li><li>Tidak ada kepercayaan</li><li>Komunikasi tertutup atau menyerang</li><li>Membuat kamu merasa sedih atau takut</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Relasi sehat', 'definition' => 'Hubungan yang dibangun atas dasar saling menghormati dan mendukung.'],
            ['term' => 'Kepercayaan', 'definition' => 'Keyakinan bahwa orang lain akan bersikap jujur dan bertanggung jawab.'],
            ['term' => 'Komunikasi', 'definition' => 'Proses berbagi informasi dan perasaan dengan orang lain.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 6: Relasi Sehat\n";
    }

    private function seedBatasanTubuh()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'batasan-tubuh',
            'title' => 'Batasan Pribadi dan Privasi Tubuh',
            'description' => 'Memahami hak atas privasi dan batasan tubuh kamu',
            'section' => 'jaga-diri',
            'order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Tubuhmu adalah milikmu sendiri. Kamu memiliki hak penuh untuk menentukan siapa yang boleh menyentuh tubuhmu dan bagaimana cara mereka menyentuhnya.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Memahami Batasan Pribadi',
            'content' => '<p><strong>Batasan pribadi adalah aturan yang kamu buat untuk melindungi tubuh dan perasaanmu.</strong></p><p><strong>Area tubuh pribadi yang harus dilindungi:</strong></p><ul><li>Area yang tertutup pakaian dalam (payudara, alat kelamin, bokong)</li><li>Bagian tubuh yang membuatmu tidak nyaman jika disentuh</li></ul><p><strong>Kamu berhak mengatakan:</strong></p><ul><li>"Tidak" jika tidak ingin disentuh</li><li>"Berhenti" jika disentuh dengan cara yang membuatmu tidak nyaman</li><li>"Saya perlu waktu untuk berpikir" jika belum siap</li></ul><p><strong>Orang yang boleh menyentuh area pribadi kamu:</strong></p><ul><li>Orang tua atau wali untuk keperluan kesehatan</li><li>Dokter atau tenaga medis untuk pemeriksaan kesehatan</li><li>Pacar kamu dengan persetujuan (setelah cukup umur)</li><li>Tidak ada orang lain yang boleh menyentuh area pribadi tanpa izin</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Batasan pribadi', 'definition' => 'Aturan yang kamu buat untuk melindungi tubuh dan perasaanmu.'],
            ['term' => 'Privasi', 'definition' => 'Hak untuk memiliki ruang pribadi dan tidak diganggu.'],
            ['term' => 'Area pribadi', 'definition' => 'Bagian tubuh yang biasanya tertutup pakaian dalam.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 7: Batasan Tubuh\n";
    }

    private function seedConsent()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'consent',
            'title' => 'Persetujuan (Consent)',
            'description' => 'Memahami arti persetujuan dalam hubungan dan interaksi',
            'section' => 'jaga-diri',
            'order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Consent (persetujuan) adalah perjanjian yang jelas dan sadar antara dua orang untuk melakukan sesuatu. Tanpa persetujuan, tindakan apa pun adalah salah dan bisa menjadi kekerasan.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Apa itu Consent?',
            'content' => '<p><strong>Consent adalah persetujuan yang jelas, terbuka, dan dapat dibatalkan setiap saat.</strong></p><p><strong>Ciri persetujuan yang valid:</strong></p><ul><li>Diberikan dengan sukarela, tanpa paksaan</li><li>Diberikan oleh orang yang sadar (tidak sedang mabuk atau tidur)</li><li>Diberikan dengan jelas ("Ya, saya setuju" atau bentuk komunikasi lainnya)</li><li>Bisa dibatalkan kapan saja</li><li>Spesifik untuk aktivitas tertentu (setuju untuk satu hal tidak berarti setuju untuk hal lain)</li></ul><p><strong>Contoh yang BUKAN persetujuan yang sah:</strong></p><ul><li>Diam = persetujuan (diam bisa berarti takut, bingung, atau tidak setuju)</li><li>Persetujuan sebelumnya = persetujuan sekarang (orang bisa berubah pikiran)</li><li>Pakaian tertentu = persetujuan (cara berpakaian bukan undangan)</li><li>Dalam hubungan = otomatis persetujuan (setiap aktivitas tetap perlu persetujuan)</li></ul><p><strong>Hal-hal yang perlu ada persetujuan:</strong></p><ul><li>Menyentuh bagian tertentu tubuh</li><li>Berciuman atau kegiatan seksual</li><li>Mengambil foto atau video</li><li>Membagikan informasi pribadi</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Consent/Persetujuan', 'definition' => 'Perjanjian yang jelas dan sadar untuk melakukan sesuatu.'],
            ['term' => 'Sukarela', 'definition' => 'Dilakukan atas keinginan sendiri tanpa paksaan.'],
            ['term' => 'Membatalkan', 'definition' => 'Mengubah keputusan dan mengatakan tidak lagi setuju.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 8: Consent\n";
    }

    private function seedSentuhanAman()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'sentuhan-aman-dan-tidak-aman',
            'title' => 'Sentuhan Aman dan Tidak Aman',
            'description' => 'Membedakan sentuhan yang aman dan tidak aman',
            'section' => 'jaga-diri',
            'order' => 4,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Tidak semua sentuhan adalah baik. Kamu berhak merasa nyaman dengan tubuh kamu. Jika ada yang menyentuh kamu dengan cara yang tidak nyaman, itu bukan salah kamu.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Jenis-jenis Sentuhan',
            'content' => '<p><strong>Sentuhan AMAN (Healthy Touch):</strong></p><ul><li>Pelukan dari orang yang kamu percaya</li><li>Genggaman tangan</li><li>Tepukan di bahu</li><li>Sentuhan tangan di kepala (jika kamu mau)</li><li>Sentuhan yang membuat kamu merasa aman dan dicintai</li></ul><p><strong>Sentuhan TIDAK AMAN (Unsafe Touch):</strong></p><ul><li>Sentuhan pada area pribadi tanpa izin</li><li>Sentuhan yang membuat kamu merasa takut atau tidak nyaman</li><li>Sentuhan yang dilakukan dalam situasi rahasia</li><li>Sentuhan yang disertai kekerasan atau paksaan</li><li>Sentuhan yang membuat kamu merasa malu atau bersalah</li></ul><p><strong>Tanda bahwa sentuhan tidak aman:</strong></p><ul><li>Kamu merasa tidak nyaman atau takut</li><li>Orang tersebut menyuruh kamu diam tentang hal itu</li><li>Orang tersebut mengancam atau memaksa</li><li>Kamu merasa bersalah atau malu</li></ul><p><strong>Jika mengalami sentuhan yang tidak aman:</strong></p><ul><li>Katakan "TIDAK" dengan tegas</li><li>Pergi dari tempat tersebut</li><li>Ceritakan kepada orang yang kamu percaya (orang tua, guru, konselor)</li><li>Ini bukan salah kamu</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Sentuhan aman', 'definition' => 'Sentuhan yang membuat kamu merasa aman, nyaman, dan dicintai.'],
            ['term' => 'Sentuhan tidak aman', 'definition' => 'Sentuhan yang membuat kamu merasa takut, tidak nyaman, atau bersalah.'],
            ['term' => 'Area pribadi', 'definition' => 'Bagian tubuh yang biasanya tertutup pakaian dalam.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 9: Sentuhan Aman dan Tidak Aman\n";
    }

    private function seedPencegahanKekerasan()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'pencegahan-kekerasan-seksual',
            'title' => 'Pencegahan Kekerasan Seksual',
            'description' => 'Cara melindungi diri dari kekerasan seksual',
            'section' => 'jaga-diri',
            'order' => 5,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Kekerasan seksual BUKAN salah kamu. Kamu berhak dilindungi. Ada orang yang siap membantu jika kamu membutuhkan bantuan.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Apa itu Kekerasan Seksual?',
            'content' => '<p><strong>Kekerasan seksual adalah setiap tindakan yang bersifat seksual tanpa persetujuan atau dengan paksaan.</strong></p><p><strong>Bentuk-bentuk kekerasan seksual:</strong></p><ul><li>Menyentuh area pribadi tanpa izin</li><li>Memaksa untuk kegiatan seksual</li><li>Menunjukkan area pribadi tanpa persetujuan</li><li>Membuat komentar seksual yang tidak diinginkan</li><li>Pelecehan online (chat atau foto seksual)</li><li>Memperkosa atau mencoba memperkosa</li></ul><p><strong>Cara melindungi diri:</strong></p><ul><li>Percayai instingmu - jika terasa tidak aman, kemungkinan besar memang tidak aman</li><li>Hindari situasi yang membuatmu merasa tidak nyaman</li><li>Tetap bersama teman yang dapat dipercaya</li><li>Beri tahu orang dewasa yang terpercaya jika merasa tidak aman</li><li>Jangan khawatir tentang "sopan" jika itu mengancam keselamatan kamu</li><li>Pelajari teknik pertahanan diri jika memungkinkan</li></ul><p><strong>Jika mengalami kekerasan seksual:</strong></p><ul><li>Ini BUKAN salah kamu</li><li>Segera pergi ke tempat yang aman</li><li>Hubungi orang yang kamu percaya (orang tua, guru, polisi)</li><li>Ada layanan konseling gratis yang bisa membantu</li><li>Jangan mandi atau membersihkan bukti sampai diperiksa dokter</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Kekerasan seksual', 'definition' => 'Tindakan seksual apa pun yang dilakukan tanpa persetujuan atau dengan paksaan.'],
            ['term' => 'Perkosaan', 'definition' => 'Kekerasan seksual dalam bentuk penetrasi tubuh tanpa persetujuan.'],
            ['term' => 'Pelecehan seksual', 'definition' => 'Perilaku seksual yang tidak diinginkan dan membuat orang merasa tidak aman.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 10: Pencegahan Kekerasan Seksual\n";
    }

    private function seedCitraTubuh()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'citra-tubuh',
            'title' => 'Citra Tubuh dan Kepuasan Diri',
            'description' => 'Menerima tubuh kamu seperti apa adanya',
            'section' => 'jaga-diri',
            'order' => 6,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Semua tubuh adalah tubuh yang baik. Tidak ada satu bentuk tubuh yang "sempurna". Keindahan datang dalam berbagai bentuk, ukuran, dan warna.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Memahami Citra Tubuh',
            'content' => '<p><strong>Citra tubuh adalah bagaimana kamu melihat dan merasa tentang tubuh kamu sendiri.</strong></p><p><strong>Citra tubuh yang positif berarti:</strong></p><ul><li>Kamu menerima tubuh kamu seperti apa adanya</li><li>Kamu menghargai apa yang bisa dilakukan tubuh kamu</li><li><li>Kamu tidak membanding-bandingkan tubuh kamu dengan tubuh orang lain</li><li>Kamu merasa nyaman di dalam kulit kamu</li></ul><p><strong>Tantangan yang sering dihadapi remaja:</strong></p><ul><li>Perbandingan dengan media sosial (foto yang sudah diedit)</li><li>Komentar negatif dari teman atau keluarga</li><li>Tekanan untuk memiliki bentuk tubuh tertentu</li><li>Perubahan tubuh saat pubertas yang membuat merasa aneh</li></ul><p><strong>Cara membangun citra tubuh yang positif:</strong></p><ul><li>Fokus pada hal yang bisa dilakukan tubuh (lari, menari, bermain)</li><li>Hindari media sosial yang membuat kamu merasa buruk</li><li>Mengelilingi diri dengan orang yang membuat kamu merasa baik tentang diri kamu</li><li>Praktek self-compassion (bersikap baik kepada diri sendiri)</li><li>Ingat bahwa foto di media sosial tidak real</li><li>Cari role model yang menampilkan keragaman tubuh</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Citra tubuh', 'definition' => 'Bagaimana kamu melihat dan merasa tentang tubuh kamu sendiri.'],
            ['term' => 'Body image positif', 'definition' => 'Penerimaan dan penghargaan terhadap tubuh kamu.'],
            ['term' => 'Self-compassion', 'definition' => 'Bersikap baik dan pengertian kepada diri sendiri.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 11: Citra Tubuh\n";
    }

    private function seedKeamananDigital()
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => 'keamanan-digital',
            'title' => 'Keamanan Digital dan Online',
            'description' => 'Cara aman menggunakan internet dan media sosial',
            'section' => 'jaga-diri',
            'order' => 7,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => 'Internet adalah alat yang hebat, tapi juga memiliki risiko. Belajar cara menggunakan internet dengan aman adalah keterampilan penting di era digital.',
            'media_url' => null,
            'alt_text' => null,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'text_mudah_dibaca',
            'title' => 'Tips Keamanan Digital',
            'content' => '<p><strong>Lindungi informasi pribadi kamu:</strong></p><ul><li>Jangan bagikan alamat rumah, nomor telepon, atau nama sekolah</li><li>Buat password yang kuat (kombinasi huruf, angka, simbol)</li><li>Jangan gunakan password yang sama untuk semua akun</li><li>Berhati-hati dengan "phishing" (pesan palsu yang minta password)</li></ul><p><strong>Aman di media sosial:</strong></p><ul><li>Atur privasi akun kamu (hanya teman yang bisa lihat)</li><li>Pikirkan sebelum posting (sekali upload, sulit dihapus)</li><li>Jangan percaya orang yang tidak kamu kenal</li><li>Jika ada yang minta foto pribadi, itu adalah red flag</li></ul><p><strong>Hindari cyberbullying:</strong></p><ul><li>Jangan kirim pesan kasar atau mengejek orang lain</li><li>Jika dikerjain online, jangan balas dengan kasar</li><li>Screenshot bukti jika ada bullying dan lapor</li><li>Block orang yang membuat kamu merasa tidak nyaman</li></ul><p><strong>Awal grooming online (orang yang ingin mengambil keuntungan):</strong></p><ul><li>Orang dewasa yang ingin chat pribadi</li><li>Minta foto pribadi atau seksual</li><li>Menyuruh kamu diam tentang hubungan mereka</li><li>Memberikan pujian berlebihan atau hadiah</li><li>Jika ini terjadi, STOP, lapor, dan cerita ke orang tua</li></ul>',
            'media_url' => null,
            'alt_text' => null,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Glosarium
        $glossaryItems = [
            ['term' => 'Cyberbullying', 'definition' => 'Penindasan yang terjadi di internet atau media sosial.'],
            ['term' => 'Phishing', 'definition' => 'Penipuan online untuk mendapatkan informasi pribadi seperti password.'],
            ['term' => 'Grooming', 'definition' => 'Proses orang dewasa membangun kepercayaan dengan anak untuk eksploitasi.'],
            ['term' => 'Privacy', 'definition' => 'Pengaturan untuk membatasi siapa yang bisa melihat informasi kamu.'],
        ];

        foreach ($glossaryItems as $item) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $item['term'],
                'definition' => $item['definition'],
                'order' => array_search($item, $glossaryItems) + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ Modul 12: Keamanan Digital\n";
    }
}