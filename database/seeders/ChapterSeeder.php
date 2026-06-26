<?php

// Kode ini diletakkan di database/seeders/ChapterSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Page;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        // Mencari komik pertama (Ninja Rebirth) dari database
        $manga = Manga::first();

        if ($manga) {
            // Membuat Chapter 1
            $chapter = Chapter::create([
                'manga_id' => $manga->id,
                'chapter_number' => '01',
                'title' => 'Awal Mula Reinkarnasi',
            ]);

            // Membuat 3 halaman gambar dummy berurutan untuk chapter tersebut
            for ($i = 1; $i <= 3; $i++) {
                Page::create([
                    'chapter_id' => $chapter->id,
                    'page_number' => $i,
                    // Kita gunakan placeholder gambar panjang agar terasa seperti komik webtoon
                    'image_path' => 'https://via.placeholder.com/800x1200?text=Halaman+' . $i,
                ]);
            }
        }
    }
}