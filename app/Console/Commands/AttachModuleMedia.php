<?php

namespace App\Console\Commands;

use App\Models\ModuleContent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * php artisan modules:attach-media
 *
 * Menyisir folder public/images/modules/{slug}/... dan otomatis mengisi
 * media_url (untuk video_isyarat & infographic) atau content_data (untuk
 * komik, berupa array halaman berurutan) di tabel module_content —
 * berdasarkan slug modul, tanpa perlu edit ModuleSeedAll.php manual
 * satu-satu.
 *
 * KONVENSI PENAMAAN FOLDER/FILE yang dicari command ini:
 *
 *   public/images/modules/{slug}/infografis.(png|jpg|jpeg|webp)
 *   public/images/modules/{slug}/video.(mp4|webm)
 *   public/images/modules/{slug}/komik/01.png, 02.png, 03.png, ...
 *
 * Contoh nyata:
 *   public/images/modules/menstruasi/infografis.png
 *   public/images/modules/mimpi-basah/infografis.png
 *   public/images/modules/mengenal-tubuh-kita/video.mp4
 *   public/images/modules/pubertas-dan-perubahan-pada-tubuh/komik/01.png
 *
 * Slug harus PERSIS sama dengan kolom `slug` di tabel modules (lihat
 * ModuleSeedAll.php) — command ini mencocokkan berdasarkan slug tersebut.
 *
 * Cara pakai:
 *   php artisan modules:attach-media --dry-run   -> lihat dulu apa yang akan diubah
 *   php artisan modules:attach-media             -> benar-benar simpan ke database
 */
class AttachModuleMedia extends Command
{
    protected $signature = 'modules:attach-media {--dry-run : Tampilkan hasil pencocokan tanpa menyimpan ke database}';

    protected $description = 'Menghubungkan file media di public/images/modules/{slug}/... ke kolom media_url/content_data pada module_content';

    private const IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp'];
    private const VIDEO_EXTENSIONS = ['mp4', 'webm'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $baseDir = public_path('images/modules');

        if (!File::isDirectory($baseDir)) {
            $this->error("Folder belum ada: {$baseDir}");
            $this->line('Buat folder itu dulu, taruh file media di dalamnya sesuai konvensi penamaan di komentar file ini, baru jalankan command ini lagi.');
            return self::FAILURE;
        }

        $updated = 0;
        $skipped = 0;

        // ---------- Video & Infografis: 1 file per module_content ----------
        ModuleContent::whereIn('type', ['infographic', 'video_isyarat'])
            ->with('module')
            ->get()
            ->each(function (ModuleContent $content) use ($baseDir, $dryRun, &$updated, &$skipped) {
                if (!$content->module) {
                    $skipped++;
                    return;
                }

                $slug = $content->module->slug;
                $dir = "{$baseDir}/{$slug}";

                if (!File::isDirectory($dir)) {
                    $this->line("  · {$slug} [{$content->type}] -> folder tidak ditemukan, dilewati");
                    $skipped++;
                    return;
                }

                $prefix = $content->type === 'video_isyarat' ? 'video' : 'infografis';
                $extensions = $content->type === 'video_isyarat' ? self::VIDEO_EXTENSIONS : self::IMAGE_EXTENSIONS;

                $match = collect(File::files($dir))
                    ->first(function ($file) use ($prefix, $extensions) {
                        return str_starts_with(strtolower($file->getFilenameWithoutExtension()), $prefix)
                            && in_array(strtolower($file->getExtension()), $extensions, true);
                    });

                if (!$match) {
                    $this->line("  · {$slug} [{$content->type}] -> file '{$prefix}.*' tidak ditemukan di {$dir}, dilewati");
                    $skipped++;
                    return;
                }

                $relativePath = "images/modules/{$slug}/{$match->getFilename()}";
                $this->info("  ✓ {$slug} [{$content->type}] -> {$relativePath}");

                if (!$dryRun) {
                    $content->update(['media_url' => $relativePath]);
                }
                $updated++;
            });

        // ---------- Komik: banyak halaman per module_content ----------
        ModuleContent::where('type', 'komik')
            ->with('module')
            ->get()
            ->each(function (ModuleContent $content) use ($baseDir, $dryRun, &$updated, &$skipped) {
                if (!$content->module) {
                    $skipped++;
                    return;
                }

                $slug = $content->module->slug;
                $dir = "{$baseDir}/{$slug}/komik";

                if (!File::isDirectory($dir)) {
                    $this->line("  · {$slug} [komik] -> folder 'komik/' tidak ditemukan, dilewati");
                    $skipped++;
                    return;
                }

                $pages = collect(File::files($dir))
                    ->filter(fn($file) => in_array(strtolower($file->getExtension()), self::IMAGE_EXTENSIONS, true))
                    ->sortBy(fn($file) => $file->getFilename())
                    ->values()
                    ->map(fn($file) => [
                        'image' => "images/modules/{$slug}/komik/{$file->getFilename()}",
                        'caption' => null,
                    ])
                    ->toArray();

                if (empty($pages)) {
                    $this->line("  · {$slug} [komik] -> tidak ada file gambar di {$dir}, dilewati");
                    $skipped++;
                    return;
                }

                $this->info('  ✓ ' . $slug . ' [komik] -> ' . count($pages) . ' halaman ditemukan');

                if (!$dryRun) {
                    $content->update(['content_data' => $pages]);
                }
                $updated++;
            });

        $this->newLine();
        $this->info("Selesai. {$updated} konten diperbarui, {$skipped} dilewati (file/folder tidak ditemukan).");

        if ($dryRun) {
            $this->comment('Ini masih --dry-run, BELUM ada perubahan tersimpan ke database.');
            $this->comment('Jalankan tanpa --dry-run untuk benar-benar menyimpan: php artisan modules:attach-media');
        }

        return self::SUCCESS;
    }
}
