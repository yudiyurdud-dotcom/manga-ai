<?php

// Kode ini diletakkan di database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Memanggil file MangaSeeder yang baru kita buat
        $this->call([
            MangaSeeder::class,
        ]);
    }
}