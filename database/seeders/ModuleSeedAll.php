<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * ModuleSeedAll
 *
 * Seeder lengkap untuk 15 modul pembelajaran AKRAB, disusun langsung dari
 * dokumen "Breakdown konten untuk 15 Materi" (PDF yang diberikan client).
 *
 * SEMUA istilah glosarium dan SEMUA soal kuis + kunci jawaban pada dokumen
 * sumber dimasukkan apa adanya (tidak dipotong / disingkat), sesuai
 * permintaan: kalau materi punya 5 soal, 5-5 nya dimasukkan; kalau 10
 * istilah glosarium, 10-10 nya dimasukkan.
 *
 * CATATAN JUJUR soal data sumber (supaya tidak menyesatkan):
 * 1) Beberapa materi di PDF menyebut "N istilah" di judul Glosarium, tapi
 *    baris tabel yang benar-benar diisi jumlahnya beda dari N. Saya
 *    mengikuti ISI TABEL yang benar-benar ada (bukan angka klaim di judul),
 *    karena itu satu-satunya data konkret yang tersedia:
 *      - Materi 4 (Mimpi Basah): judul bilang "7 istilah", tabel berisi 8 baris -> saya masukkan 8.
 *      - Materi 5 (Kebersihan Organ Reproduksi): judul bilang "6 istilah", tabel berisi 5 baris -> saya masukkan 5.
 *      - Materi 10 (Pencegahan Kekerasan Seksual): judul bilang "5 istilah", tabel berisi 3 baris -> saya masukkan 3.
 *      - Materi 14 (Cara Mencari Bantuan): judul bilang "5 istilah", tabel berisi 3 baris -> saya masukkan 3.
 *    Kalau ternyata memang ada istilah tambahan yang belum sempat masuk PDF,
 *    tinggal tambahkan ke array glossary modul terkait di bawah.
 *
 * 2) Untuk tipe media yang aset aslinya belum diberikan client (video,
 *    infografis, komik), field media_url/content_data sengaja saya isi
 *    null / placeholder dengan alt_text yang menjelaskan bahwa aset masih
 *    menunggu — supaya tidak berpura-pura ada file yang sebenarnya belum ada.
 *    Satu-satunya materi dengan struktur JSON yang saya isi PENUH dari PDF
 *    adalah Materi 12 (flipcard mitos vs fakta), karena isinya memang
 *    tercantum lengkap di dokumen sumber.
 *
 * 3) Pembagian `section` (mulai-belajar / jaga-diri / lainnya) TIDAK
 *    dinyatakan eksplisit di PDF. Pengelompokan di bawah ini adalah
 *    interpretasi saya berdasarkan tema masing-masing materi — silakan
 *    sesuaikan kalau strukturnya beda dari yang diinginkan tim AKRAB.
 */
class ModuleSeedAll extends Seeder
{
    public function run(): void
    {
        DB::table('questions')->delete();
        DB::table('faq')->delete();
        DB::table('glossary')->delete();
        DB::table('quiz_options')->delete();
        DB::table('quizzes')->delete();
        DB::table('module_content')->delete();
        DB::table('modules')->delete();

        $this->command->info('🧹 Data lama berhasil dibersihkan.');

        foreach ($this->modulesData() as $data) {
            $this->seedModule($data);
        }

        $this->command->info('✅ Seluruh 15 modul pembelajaran AKRAB sukses disuntikkan!');
    }

    /**
     * Insert satu modul lengkap: module_content (pesan kunci + media),
     * glossary, dan quizzes + quiz_options.
     */
    private function seedModule(array $data): void
    {
        $moduleId = DB::table('modules')->insertGetId([
            'slug' => $data['slug'],
            'title' => $data['title'],
            'description' => $data['description'],
            'section' => $data['section'],
            'order' => $data['order'],
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order = 1;

        // Pesan kunci selalu jadi blok pertama
        DB::table('module_content')->insert([
            'module_id' => $moduleId,
            'type' => 'pesan_kunci',
            'title' => 'Pesan Kunci',
            'content' => $data['key_message'],
            'content_data' => null,
            'media_url' => null,
            'alt_text' => null,
            'order' => $order++,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($data['contents'] as $c) {
            DB::table('module_content')->insert([
                'module_id' => $moduleId,
                'type' => $c['type'],
                'title' => $c['title'] ?? null,
                'content' => $c['content'] ?? '',
                'content_data' => isset($c['content_data']) ? json_encode($c['content_data']) : null,
                'media_url' => $c['media_url'] ?? null,
                'alt_text' => $c['alt_text'] ?? null,
                'order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (($data['glossary'] ?? []) as $i => $g) {
            DB::table('glossary')->insert([
                'module_id' => $moduleId,
                'term' => $g['term'],
                'definition' => $g['definition'],
                'order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (($data['quizzes'] ?? []) as $qi => $q) {
            $quizId = DB::table('quizzes')->insertGetId([
                'module_id' => $moduleId,
                'question' => $q['question'],
                'type' => $q['type'] ?? 'pilihan_ganda',
                'order' => $qi + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($q['options'] as $oi => $opt) {
                DB::table('quiz_options')->insert([
                    'quiz_id' => $quizId,
                    'label' => $opt['label'],
                    'text' => $opt['text'],
                    'is_correct' => $opt['is_correct'],
                    'order' => $oi + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info("✓ Modul {$data['order']}: {$data['title']} (" .
            count($data['glossary'] ?? []) . ' istilah, ' .
            count($data['quizzes'] ?? []) . ' soal)');
    }

    /**
     * Seluruh data 15 modul, diambil dari dokumen "Breakdown konten untuk 15 Materi".
     */
    private function modulesData(): array
    {
        return [

            // ================= MATERI 1 =================
            [
                'order' => 1,
                'slug' => 'mengenal-tubuh-kita',
                'title' => 'Mengenal Tubuh Kita',
                'description' => 'Remaja mampu mengenali bagian tubuh, bagian pribadi, dan organ reproduksi secara benar, ilmiah, dan tidak tabu.',
                'section' => 'mulai-belajar',
                'key_message' => 'Tubuh laki-laki dan perempuan berbeda; belajar nama asli organ reproduksi secara ilmiah dan tanpa rasa malu.',
                'contents' => [
                    [
                        'type' => 'video_isyarat',
                        'title' => 'Video Bahasa Isyarat: Mengenal Tubuh Kita',
                        'content' => 'Video utama (media utama) materi Mengenal Tubuh Kita dalam bahasa isyarat.',
                        'media_url' => null,
                        'alt_text' => 'Video bahasa isyarat — aset menunggu dari client',
                    ],
                    [
                        'type' => 'subtitle',
                        'title' => 'Subtitle Video',
                        'content' => 'Subtitle video menunggu naskah final dari client.',
                    ],
                    [
                        'type' => 'transkrip',
                        'title' => 'Transkrip Video (Media Pendukung)',
                        'content' => 'Transkrip lengkap video menunggu naskah final dari client.',
                    ],
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Anatomi Sederhana (Media Pendukung)',
                        'content' => 'Infografis aksesibel anatomi sederhana.',
                        'media_url' => null,
                        'alt_text' => 'Infografis anatomi sederhana — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Organ reproduksi', 'definition' => 'Bagian tubuh yang berkaitan dengan pubertas dan fungsi reproduksi.'],
                    ['term' => 'Penis', 'definition' => 'Organ luar laki-laki tempat keluarnya urine dan semen.'],
                    ['term' => 'Skrotum', 'definition' => 'Kantong kulit yang melindungi testis.'],
                    ['term' => 'Testis', 'definition' => 'Organ laki-laki yang membuat sperma dan hormon testosteron.'],
                    ['term' => 'Vulva', 'definition' => 'Bagian luar organ reproduksi perempuan.'],
                    ['term' => 'Vagina', 'definition' => 'Saluran tempat darah haid keluar dari tubuh.'],
                    ['term' => 'Rahim/uterus', 'definition' => 'Organ di dalam tubuh perempuan tempat janin tumbuh jika terjadi kehamilan.'],
                    ['term' => 'Ovarium', 'definition' => 'Organ yang menghasilkan sel telur.'],
                    ['term' => 'Tuba falopi', 'definition' => 'Saluran yang menghubungkan ovarium dan rahim.'],
                    ['term' => 'Tubuh pribadi', 'definition' => 'Bagian tubuh yang biasanya tertutup pakaian dalam dan perlu dijaga.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Organ luar laki-laki tempat keluarnya urine disebut...',
                        'options' => [
                            ['label' => 'A', 'text' => 'telinga', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'penis', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'paru-paru', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Skrotum berfungsi untuk...',
                        'options' => [
                            ['label' => 'A', 'text' => 'melindungi testis', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'melihat', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'mendengar', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagian luar organ reproduksi perempuan disebut...',
                        'options' => [
                            ['label' => 'A', 'text' => 'vulva', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'lutut', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'bahu', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Nama organ reproduksi yang benar adalah...',
                        'options' => [
                            ['label' => 'A', 'text' => 'nama ilmiah', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'kata ejekan', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'kata untuk menakut-nakuti', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika bingung tentang tubuh, sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'diam selamanya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'bertanya kepada orang dewasa tepercaya', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'percaya semua informasi teman', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 2 =================
            [
                'order' => 2,
                'slug' => 'pubertas-dan-perubahan-pada-tubuh',
                'title' => 'Pubertas dan Perubahan pada Tubuh',
                'description' => 'Remaja memahami bahwa pubertas adalah proses normal serta mampu mengenali perubahan fisik, emosi, dan sosial.',
                'section' => 'mulai-belajar',
                'key_message' => 'Pubertas itu normal; tanda tubuh sedang berkembang menjadi dewasa.',
                'contents' => [
                    [
                        'type' => 'komik',
                        'title' => 'Komik Edukatif: Pubertas dan Perubahan Tubuh',
                        'content' => 'Komik edukatif singkat (media utama). Naskah/ilustrasi panel menunggu aset dari client.',
                        'content_data' => [
                            ['image' => null, 'caption' => 'Panel komik menunggu naskah & ilustrasi final dari client.'],
                        ],
                    ],
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Pubertas',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis pubertas — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Pubertas', 'definition' => 'Masa perubahan tubuh dari anak menuju remaja dan dewasa.'],
                    ['term' => 'Hormon', 'definition' => 'Zat dalam tubuh yang mengatur pertumbuhan dan perubahan.'],
                    ['term' => 'Jerawat', 'definition' => 'Bintik pada kulit yang sering muncul saat pubertas.'],
                    ['term' => 'Menstruasi', 'definition' => 'Keluarnya darah dari vagina secara berkala sebagai bagian normal pubertas perempuan.'],
                    ['term' => 'Mimpi basah', 'definition' => 'Keluarnya cairan semen saat tidur pada remaja laki-laki.'],
                    ['term' => 'Bau badan', 'definition' => 'Bau yang dapat muncul karena keringat dan perubahan tubuh saat pubertas.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Pubertas adalah...',
                        'options' => [
                            ['label' => 'A', 'text' => 'penyakit', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'proses normal tubuh berkembang', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'hukuman', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perubahan saat pubertas dapat berupa...',
                        'options' => [
                            ['label' => 'A', 'text' => 'tumbuh rambut', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'jerawat', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'bau badan', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'semua benar', 'is_correct' => true],
                        ],
                    ],
                    [
                        'question' => 'Jika bingung tentang pubertas, sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'diam saja', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'bertanya kepada orang dewasa tepercaya', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'percaya mitos', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 3 =================
            [
                'order' => 3,
                'slug' => 'menstruasi',
                'title' => 'Menstruasi',
                'description' => 'Remaja memahami menstruasi sebagai proses normal dan mampu melakukan manajemen kebersihan menstruasi secara aman.',
                'section' => 'mulai-belajar',
                'key_message' => 'Haid adalah proses alami bulanan dan bukan darah kotor.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Menstruasi',
                        'content' => 'Infografis aksesibel menstruasi (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis menstruasi — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Haid/menstruasi', 'definition' => 'Darah dan jaringan dari rahim yang keluar melalui vagina setiap bulan.'],
                    ['term' => 'Rahim', 'definition' => 'Organ di dalam tubuh perempuan tempat janin tumbuh saat hamil.'],
                    ['term' => 'Vagina', 'definition' => 'Saluran tempat darah haid keluar dari tubuh.'],
                    ['term' => 'Pembalut', 'definition' => 'Alat untuk menyerap darah haid.'],
                    ['term' => 'Siklus haid', 'definition' => 'Jarak dari hari pertama haid sampai hari pertama haid berikutnya.'],
                    ['term' => 'Kram', 'definition' => 'Rasa nyeri atau mulas di perut bawah.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Haid adalah...',
                        'options' => [
                            ['label' => 'A', 'text' => 'penyakit', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'proses normal tubuh perempuan', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'luka', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Darah haid berasal dari...',
                        'options' => [
                            ['label' => 'A', 'text' => 'rahim', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'telinga', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'paru-paru', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Pembalut bekas sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'dibuang ke toilet', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'dibungkus lalu dibuang ke tempat sampah', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'disimpan di saku', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 4 =================
            [
                'order' => 4,
                'slug' => 'mimpi-basah',
                'title' => 'Mimpi Basah',
                'description' => 'Remaja memahami mimpi basah sebagai bagian normal pubertas dan mampu menjaga kebersihan diri setelah mengalaminya.',
                'section' => 'mulai-belajar',
                'key_message' => 'Mimpi basah adalah tanda sehat tubuh laki-laki yang sedang berkembang.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Alur Produksi Sperma hingga Ejakulasi',
                        'content' => 'Infografis aksesibel alur produksi sperma hingga ejakulasi (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis mimpi basah — aset menunggu dari client',
                    ],
                ],
                // Judul PDF bilang "7 istilah", tapi tabel berisi 8 baris — 8 baris inilah yang dimasukkan.
                'glossary' => [
                    ['term' => 'Mimpi basah', 'definition' => 'Keluarnya semen dari penis saat tidur.'],
                    ['term' => 'Sperma', 'definition' => 'Sel reproduksi laki-laki yang dibuat di testis.'],
                    ['term' => 'Testis', 'definition' => 'Organ laki-laki yang membuat sperma dan hormon testosteron.'],
                    ['term' => 'Skrotum', 'definition' => 'Kantong kulit yang melindungi testis.'],
                    ['term' => 'Semen', 'definition' => 'Cairan yang membawa sperma.'],
                    ['term' => 'Penis', 'definition' => 'Organ luar laki-laki tempat keluarnya urine dan semen.'],
                    ['term' => 'Ereksi', 'definition' => 'Penis menjadi tegang karena aliran darah meningkat.'],
                    ['term' => 'Ejakulasi', 'definition' => 'Keluarnya semen dari penis.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Mimpi basah adalah...',
                        'options' => [
                            ['label' => 'A', 'text' => 'keluarnya semen saat tidur', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'penyakit berbahaya', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'darah dari penis', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Sperma dibuat di...',
                        'options' => [
                            ['label' => 'A', 'text' => 'paru-paru', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'testis', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'telinga', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah mimpi basah, sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'membersihkan tubuh dan mengganti pakaian dalam', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'memakai celana yang sama', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'menyembunyikan pakaian basah', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 5 =================
            [
                'order' => 5,
                'slug' => 'kebersihan-organ-reproduksi',
                'title' => 'Kebersihan Organ Reproduksi',
                'description' => 'Remaja mampu menerapkan perilaku menjaga kebersihan organ reproduksi dalam kehidupan sehari-hari.',
                'section' => 'mulai-belajar',
                'key_message' => 'Menjaga organ reproduksi agar tetap bersih, sehat, dan nyaman.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Kebersihan Organ Reproduksi',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis kebersihan organ reproduksi — aset menunggu dari client',
                    ],
                ],
                // Judul PDF bilang "6 istilah", tapi tabel berisi 5 baris — 5 baris inilah yang dimasukkan.
                'glossary' => [
                    ['term' => 'Organ reproduksi', 'definition' => 'Bagian tubuh yang berkaitan dengan fungsi reproduksi dan pubertas.'],
                    ['term' => 'Area pribadi', 'definition' => 'Bagian tubuh yang biasanya tertutup pakaian dalam.'],
                    ['term' => 'Cebok', 'definition' => 'Membersihkan area pribadi setelah buang air.'],
                    ['term' => 'Douching', 'definition' => 'Mencuci bagian dalam vagina dengan cairan tertentu; tidak dianjurkan.'],
                    ['term' => 'Iritasi', 'definition' => 'Kulit menjadi merah, perih, atau gatal.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Sebelum membersihkan area pribadi, kita harus...',
                        'options' => [
                            ['label' => 'A', 'text' => 'bermain dulu', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'cuci tangan', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'tidur', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perempuan sebaiknya cebok...',
                        'options' => [
                            ['label' => 'A', 'text' => 'dari belakang ke depan', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'dari depan ke belakang', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'arah mana saja', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Celana dalam sebaiknya diganti...',
                        'options' => [
                            ['label' => 'A', 'text' => '2 kali sehari atau saat basah/kotor', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'seminggu sekali', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'hanya saat diingatkan', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 6 =================
            [
                'order' => 6,
                'slug' => 'gizi-kesehatan-tubuh-dan-pubertas',
                'title' => 'Gizi, Kesehatan Tubuh, dan Pubertas',
                'description' => 'Remaja memahami bahwa makan bergizi, aktivitas fisik, tidur, dan kesehatan mental mendukung pertumbuhan serta kesehatan reproduksi.',
                'section' => 'mulai-belajar',
                'key_message' => 'Pertumbuhan cepat yang terjadi selama masa pubertas perlu didukung oleh pola hidup sehat.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Gizi dan Kesehatan Tubuh Saat Pubertas',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis gizi & kesehatan tubuh — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Zat besi', 'definition' => 'Mineral untuk membantu pembentukan sel darah merah.'],
                    ['term' => 'Anemia', 'definition' => 'Kondisi ketika tubuh kekurangan sel darah merah atau hemoglobin.'],
                    ['term' => 'Citra tubuh', 'definition' => 'Cara seseorang memandang dan merasakan tubuhnya.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Mengapa remaja membutuhkan makanan bergizi?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Karena tubuh sedang tumbuh', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Agar tidak perlu tidur', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Agar tidak minum air', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Tablet tambah darah digunakan sesuai?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Anjuran tenaga kesehatan atau sekolah', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Saran akun tidak dikenal', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Dosis bebas', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang benar tentang bentuk tubuh?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Semua harus sama', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Setiap tubuh dapat berbeda', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Nilai diri ditentukan berat badan', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 7 =================
            [
                'order' => 7,
                'slug' => 'relasi-sehat-dan-pertemanan-aman',
                'title' => 'Relasi Sehat dan Pertemanan Aman',
                'description' => 'Remaja mampu membedakan relasi sehat dan tidak sehat serta memilih lingkungan yang aman.',
                'section' => 'jaga-diri',
                'key_message' => 'Relasi sehat dengan teman, keluarga, maupun guru seharusnya membuat seseorang merasa aman, dihargai, dan dapat menjadi diri sendiri.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Relasi Sehat dan Pertemanan Aman',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis relasi sehat — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Relasi sehat', 'definition' => 'Hubungan yang aman, setara, dan saling menghormati.'],
                    ['term' => 'Kontrol', 'definition' => 'Tindakan mengatur orang lain secara berlebihan.'],
                    ['term' => 'Privasi', 'definition' => 'Hak menjaga informasi dan ruang pribadi.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Ciri relasi sehat adalah?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Saling menghormati', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Memaksa', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Mengancam', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Meminta foto pribadi sebagai bukti cinta adalah?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Tindakan sehat', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tanda tekanan atau manipulasi', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Kewajiban', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika merasa dikontrol, kamu dapat?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Mencari bantuan', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Diam selamanya', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Mengirim kata sandi', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 8 =================
            [
                'order' => 8,
                'slug' => 'batasan-tubuh-dan-persetujuan',
                'title' => 'Batasan Tubuh dan Persetujuan',
                'description' => 'Remaja memahami batasan tubuh pribadi dan makna persetujuan secara sederhana.',
                'section' => 'jaga-diri',
                'key_message' => 'Orang lain harus meminta izin sebelum melakukan interaksi fisik dengan tubuhmu.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Batasan Tubuh dan Persetujuan',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis batasan tubuh & persetujuan — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Batasan tubuh', 'definition' => 'Aturan pribadi untuk melindungi tubuh.'],
                    ['term' => 'Persetujuan', 'definition' => 'Izin atau keputusan yang jelas.'],
                    ['term' => 'Paksaan', 'definition' => 'Tekanan atau ancaman agar seseorang menuruti.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Apakah diam berarti setuju?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Ya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tidak', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Selalu', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau ingin memeluk teman, sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'langsung peluk', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'minta izin dulu', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'diam saja', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dilakukan saat teman berkata tidak?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Berhenti dan menghormati', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Memaksa', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Mengejek', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 9 =================
            [
                'order' => 9,
                'slug' => 'sentuhan-aman-membingungkan-dan-tidak-aman',
                'title' => 'Sentuhan Aman, Membingungkan, dan Tidak Aman',
                'description' => 'Remaja mampu mengenali berbagai jenis sentuhan dan menentukan tindakan perlindungan.',
                'section' => 'jaga-diri',
                'key_message' => 'Langkah penyelamatan jika ada ajakan atau sentuhan yang membuat takut atau tidak nyaman.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Jenis-Jenis Sentuhan',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis jenis sentuhan — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Bagian pribadi', 'definition' => 'Bagian tubuh yang biasanya tertutup pakaian dalam.'],
                    ['term' => 'Sentuhan aman', 'definition' => 'Sentuhan yang sesuai, disetujui, dan tidak membahayakan.'],
                    ['term' => 'Sentuhan membingungkan', 'definition' => 'Sentuhan yang membuat perasaan tidak nyaman, takut, atau ragu.'],
                    ['term' => 'Sentuhan tidak aman', 'definition' => 'Sentuhan yang melanggar batas atau bersifat seksual.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Jika kamu mendapat sentuhan tidak aman, apa yang harus dilakukan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Disimpan selamanya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Diceritakan kepada orang tepercaya', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Dibagikan di media sosial', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika tubuh membeku saat takut, apakah korban bersalah?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Ya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tidak', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Kadang-kadang', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Urutan tindakan perlindungan adalah?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Tolak–Pergi–Cerita', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Diam–Tunggu–Lupa', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Kirim–Hapus–Diam', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 10 =================
            [
                'order' => 10,
                'slug' => 'pencegahan-kekerasan-seksual',
                'title' => 'Pencegahan Kekerasan Seksual',
                'description' => 'Remaja mampu mengenali bentuk kekerasan seksual, melindungi diri, dan mencari bantuan dengan aman.',
                'section' => 'jaga-diri',
                'key_message' => 'Utamakan keselamatan. Jangan menyalahkan diri sendiri. Cari orang aman.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Pencegahan Kekerasan Seksual',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis pencegahan kekerasan seksual — aset menunggu dari client',
                    ],
                ],
                // Judul PDF bilang "5 istilah", tapi tabel berisi 3 baris — 3 baris inilah yang dimasukkan.
                'glossary' => [
                    ['term' => 'Kekerasan seksual', 'definition' => 'Tindakan atau sentuhan yang berhubungan dengan tubuh atau bagian pribadi tanpa izin.'],
                    ['term' => 'Manipulasi', 'definition' => 'Cara memengaruhi atau menipu seseorang untuk kepentingan pelaku.'],
                    ['term' => 'Bukti digital', 'definition' => 'Pesan, foto, video, atau rekaman yang dapat membantu pelaporan.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Siapa yang bertanggung jawab atas kekerasan seksual?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Korban', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Pelaku', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Teman korban', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dilakukan pada bukti ancaman digital?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Disebarkan', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Disimpan dengan aman', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Dihapus selalu', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagaimana mendukung teman korban?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Menyalahkan', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Mendengarkan dan membantu mencari layanan', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Menyebarkan cerita', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 11 =================
            [
                'order' => 11,
                'slug' => 'infeksi-menular-seksual',
                'title' => 'Infeksi Menular Seksual (IMS)',
                'description' => 'Remaja mengenali informasi dasar IMS, cara pencegahan, dan kapan perlu mencari tenaga kesehatan.',
                'section' => 'jaga-diri',
                'key_message' => 'IMS adalah infeksi yang terutama dapat menular melalui aktivitas seksual dan juga melalui darah.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Infeksi Menular Seksual',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis IMS — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'IMS', 'definition' => 'Infeksi yang terutama menular melalui aktivitas seksual.'],
                    ['term' => 'HIV', 'definition' => 'Virus yang menyerang sistem kekebalan tubuh.'],
                    ['term' => 'Vaksin', 'definition' => 'Bahan untuk membantu tubuh membentuk perlindungan.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Apakah IMS selalu menimbulkan gejala?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Selalu', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tidak selalu', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Hanya pada laki-laki', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'HIV menular melalui?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Berjabat tangan', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Aktivitas tertentu yang melibatkan cairan tubuh tertentu', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Berbagi makanan', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika ada luka genital, apa yang dilakukan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Mengobati sendiri', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Memeriksakan diri', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Menyembunyikan selamanya', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 12 =================
            [
                'order' => 12,
                'slug' => 'mitos-dan-fakta-kesehatan-reproduksi',
                'title' => 'Mitos dan Fakta Kesehatan Reproduksi',
                'description' => 'Remaja mampu membedakan mitos dan fakta kesehatan reproduksi.',
                'section' => 'mulai-belajar',
                'key_message' => 'Tidak semua informasi tentang tubuh dan kesehatan reproduksi itu benar.',
                'contents' => [
                    [
                        'type' => 'flipcard',
                        'title' => 'Flipcard Interaktif: Mitos vs Fakta',
                        'content' => 'Klik kartu untuk melihat penjelasan faktual. Satu kartu memuat satu mitos.',
                        'content_data' => [
                            ['mitos' => 'Menstruasi adalah darah kotor.', 'fakta' => 'Menstruasi adalah proses normal pelepasan lapisan rahim.'],
                            ['mitos' => 'Mimpi basah berarti remaja nakal.', 'fakta' => 'Mimpi basah adalah perubahan normal yang terjadi saat pubertas.'],
                            ['mitos' => 'Tidak boleh mandi saat menstruasi.', 'fakta' => 'Mandi dan menjaga kebersihan tubuh tetap penting saat menstruasi.'],
                            ['mitos' => 'HIV menular melalui pelukan.', 'fakta' => 'HIV tidak menular melalui pelukan.'],
                            ['mitos' => 'Orang yang dikenal pasti aman.', 'fakta' => 'Pelanggaran batas dapat dilakukan oleh orang yang dikenal maupun tidak dikenal.'],
                            ['mitos' => 'Korban kekerasan salah karena tidak melawan.', 'fakta' => 'Korban tidak pernah bersalah; tubuh dapat membeku (freeze response) saat merasa sangat takut.'],
                            ['mitos' => 'Semua informasi viral pasti benar.', 'fakta' => 'Informasi viral harus diperiksa sumber, tanggal, penulis, dan bukti sebelum dipercaya atau dibagikan.'],
                        ],
                    ],
                ],
                'glossary' => [
                    ['term' => 'Mitos', 'definition' => 'Informasi yang dipercaya tetapi belum tentu benar.'],
                    ['term' => 'Fakta', 'definition' => 'Informasi yang didukung bukti.'],
                    ['term' => 'Sumber tepercaya', 'definition' => 'Pihak yang memiliki keahlian dan tanggung jawab atas informasi.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Informasi viral pasti benar?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Ya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tidak', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Selalu jika banyak komentar', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika menemukan informasi kesehatan reproduksi dari internet, sebaiknya...',
                        'options' => [
                            ['label' => 'A', 'text' => 'langsung percaya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'cek sumber dan bertanya jika ragu', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'sebarkan ke semua teman', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Sumber yang lebih tepercaya adalah?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Akun anonim', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Kementerian Kesehatan atau tenaga kesehatan', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Pesan berantai', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 13 =================
            [
                'order' => 13,
                'slug' => 'hak-kesehatan-remaja',
                'title' => 'Hak Kesehatan Remaja',
                'description' => 'Remaja memahami hak memperoleh informasi, perlindungan, komunikasi aksesibel, dan layanan kesehatan yang bermartabat.',
                'section' => 'jaga-diri',
                'key_message' => 'Remaja memiliki hak kesehatan beserta tanggung jawabnya.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Hak Kesehatan Remaja',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis hak kesehatan remaja — aset menunggu dari client',
                    ],
                ],
                'glossary' => [
                    ['term' => 'Hak', 'definition' => 'Hal yang seharusnya diterima atau dilindungi.'],
                    ['term' => 'Diskriminasi', 'definition' => 'Perlakuan tidak adil karena identitas atau kondisi seseorang.'],
                    ['term' => 'Aksesibel', 'definition' => 'Dapat digunakan dan dipahami oleh berbagai pengguna.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Remaja Tuli berhak mendapat informasi dalam bentuk?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Hanya suara', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Bentuk yang dapat dipahami', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Tidak perlu informasi', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bolehkah meminta penjelasan tertulis?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Boleh', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Tidak boleh', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Hanya orang tua', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa tanggung jawab pengguna?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Menyebarkan data orang lain', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Menghormati privasi', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Memaksa orang lain', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 14 =================
            [
                'order' => 14,
                'slug' => 'cara-mencari-bantuan',
                'title' => 'Cara Mencari Bantuan',
                'description' => 'Remaja mampu menentukan kepada siapa, kapan, dan bagaimana meminta bantuan.',
                'section' => 'jaga-diri',
                'key_message' => 'Jangan takut untuk meminta bantuan kepada orang dewasa terpercaya.',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Cara Mencari Bantuan',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis cara mencari bantuan — aset menunggu dari client',
                    ],
                ],
                // Judul PDF bilang "5 istilah", tapi tabel berisi 3 baris — 3 baris inilah yang dimasukkan.
                'glossary' => [
                    ['term' => 'Orang dewasa tepercaya', 'definition' => 'Orang dewasa yang aman, mendengarkan, dan membantu.'],
                    ['term' => 'Rujukan', 'definition' => 'Menghubungkan seseorang ke layanan yang sesuai.'],
                    ['term' => 'Darurat', 'definition' => 'Keadaan yang membutuhkan pertolongan segera.'],
                ],
                'quizzes' => [
                    [
                        'question' => 'Jika orang pertama tidak membantu, apa yang dilakukan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Berhenti mencari bantuan', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Hubungi orang tepercaya lain', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Menyalahkan diri', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Cara komunikasi saat meminta bantuan dapat menggunakan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Tulisan, isyarat, atau gambar', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Hanya suara', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Tidak boleh menggunakan bukti', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jika dalam bahaya segera?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Tetap sendiri', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Pergi ke tempat aman dan cari pertolongan', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Menghapus semua pesan', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

            // ================= MATERI 15 =================
            [
                'order' => 15,
                'slug' => 'evaluasi-dan-refleksi-diri',
                'title' => 'Evaluasi dan Refleksi Diri',
                'description' => 'Remaja mampu menilai pemahaman, menyusun rencana perilaku sehat, dan meningkatkan keyakinan diri.',
                'section' => 'lainnya',
                'key_message' => 'Apa yang sudah saya pelajari sejauh ini?',
                'contents' => [
                    [
                        'type' => 'infographic',
                        'title' => 'Infografis Aksesibel: Evaluasi dan Refleksi Diri',
                        'content' => 'Infografis aksesibel (media utama).',
                        'media_url' => null,
                        'alt_text' => 'Infografis evaluasi & refleksi diri — aset menunggu dari client',
                    ],
                    [
                        'type' => 'checklist_interaktif',
                        'title' => 'Rencana Tindakan 7 Hari & Catatan Pribadi',
                        'content' => 'Fitur interaktif kolom catatan pribadi yang aman, dan rencana tindakan 7 hari.',
                        'content_data' => [
                            ['hari' => 1, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 2, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 3, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 4, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 5, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 6, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                            ['hari' => 7, 'kebiasaan_sehat' => '', 'sudah_dilakukan' => false, 'catatan' => ''],
                        ],
                    ],
                ],
                'glossary' => [], // Tidak ada glosarium untuk materi ini di PDF sumber.
                'quizzes' => [
                    [
                        'question' => 'Apa yang dilakukan jika masih bingung?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Membuka ulang materi atau bertanya', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Menebak selalu', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Berhenti belajar', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Mengapa membuat rencana tindakan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Agar pengetahuan menjadi kebiasaan', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'Agar lupa materi', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Agar membandingkan diri', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apakah meminta bantuan merupakan kelemahan?',
                        'options' => [
                            ['label' => 'A', 'text' => 'Ya', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Tidak', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Selalu', 'is_correct' => false],
                        ],
                    ],
                ],
            ],

        ];
    }
}
