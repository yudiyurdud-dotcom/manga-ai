<?php

// Kode ini diletakkan di app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Tambahkan baris ini di atas
use Illuminate\Support\Facades\View; // Tambahkan ini
use App\Models\Setting; // Tambahkan ini
use App\Models\Advertisement;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        // 1. Membagikan Pengaturan Website
        if (Schema::hasTable('settings')) {
            $webSetting = Setting::firstOrCreate(
                ['id' => 1],
                ['site_name' => 'Manga-AI', 'maintenance_mode' => false]
            );
            View::share('webSetting', $webSetting);
        }

        // 2. Membagikan Iklan (Banner Rotation System)
        // Tambahkan pengecekan hasColumn agar Artisan tidak error
        if (Schema::hasTable('advertisements') && Schema::hasColumn('advertisements', 'position')) {
            // Mengambil 1 iklan acak yang berstatus aktif untuk diletakkan di atas
            $adHeader = Advertisement::where('is_active', 1)
                            ->where('position', 'header')
                            ->inRandomOrder()
                            ->first();
            
            // Mengambil 1 iklan acak yang berstatus aktif untuk diletakkan di bawah
            $adFooter = Advertisement::where('is_active', 1)
                            ->where('position', 'footer')
                            ->inRandomOrder()
                            ->first();

            View::share('adHeader', $adHeader);
            View::share('adFooter', $adFooter);
        }
    }
}