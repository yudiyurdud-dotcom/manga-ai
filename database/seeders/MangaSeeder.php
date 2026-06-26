<?php

// Kode ini diletakkan di database/seeders/MangaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manga;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin dan User Biasa
        User::create([
            'name' => 'Admin Miyamura',
            'email' => 'admin@manga.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pembaca Setia',
            'email' => 'user@manga.test',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 2. Membuat Data Genre
        $action = Genre::create(['name' => 'Action', 'slug' => 'action']);
        $romance = Genre::create(['name' => 'Romance', 'slug' => 'romance']);
        $fantasy = Genre::create(['name' => 'Fantasy', 'slug' => 'fantasy']);

        // 3. Membuat Data Komik & Sinopsis untuk diuji AI
        $manga1 = Manga::create([
            'title' => 'Ninja Rebirth',
            'slug' => 'ninja-rebirth',
            'author' => 'Kishimoto',
            'synopsis' => 'Seorang pembunuh bayaran legendaris dikhianati oleh organisasinya. Ia bereinkarnasi ke dunia sihir dan bertekad menggunakan ilmu bela diri rahasianya untuk melindungi teman-teman barunya dari ancaman monster iblis.',
            'status' => 'ongoing',
        ]);
        $manga1->genres()->attach([$action->id, $fantasy->id]);

        $manga2 = Manga::create([
            'title' => 'My School Love Story',
            'slug' => 'my-school-love-story',
            'author' => 'Miyamura',
            'synopsis' => 'Kisah manis tentang seorang gadis pemalu yang tidak sengaja menjatuhkan buku hariannya. Buku itu ditemukan oleh siswa paling populer di sekolah, yang ternyata memiliki rahasia dan hobi yang sama dengannya.',
            'status' => 'completed',
        ]);
        $manga2->genres()->attach([$romance->id]);
    }
}