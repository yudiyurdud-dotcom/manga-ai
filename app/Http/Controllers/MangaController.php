<?php

// Kode ini diletakkan di app/Http/Controllers/MangaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter; // Pastikan Model Chapter di-import

class MangaController extends Controller
{
    // Method Index yang sudah kita buat sebelumnya
    public function index()
    {
        // Mengambil komik beserta relasi genrenya, diurutkan dari yang terbaru
        $mangas = Manga::with('genres')->latest()->paginate(12);
        
        // UBAH BARIS INI: Arahkan ke 'manga.index'
        return view('manga.index', compact('mangas')); 
    }

    // Menampilkan Halaman Detail Komik
    public function show($slug)
    {
        // Mencari komik berdasarkan slug (bukan ID)
        $manga = Manga::with(['genres', 'chapters' => function($query) {
            $query->orderBy('created_at', 'desc'); 
        }])->where('slug', $slug)->firstOrFail();

        return view('manga.show', compact('manga'));
    }

    // Menampilkan Halaman Baca Chapter
    public function read($slug, $chapter_number)
    {
        $manga = Manga::where('slug', $slug)->firstOrFail();
        
        $chapter = Chapter::with(['pages', 'manga', 'comments.user'])
            ->where('manga_id', $manga->id)
            ->where('chapter_number', $chapter_number)
            ->firstOrFail();

        // LOGIKA RIWAYAT BACA (HISTORY)
        if (auth()->check()) {
            $userId = auth()->id();
            
            // 1. Catat chapter yang sedang dibaca ini ke database
            \App\Models\ReadingHistory::updateOrCreate(
                ['user_id' => $userId, 'manga_id' => $manga->id, 'chapter_id' => $chapter->id],
                ['updated_at' => now()]
            );

            // 2. Ambil 3 ID History terbaru untuk komik ini saja
            $keepHistoryIds = \App\Models\ReadingHistory::where('user_id', $userId)
                ->where('manga_id', $manga->id)
                ->latest('updated_at')
                ->take(3)
                ->pluck('id');

            // 3. Hapus history lama yang tidak masuk dalam 3 ID terbaru tadi
            \App\Models\ReadingHistory::where('user_id', $userId)
                ->where('manga_id', $manga->id)
                ->whereNotIn('id', $keepHistoryIds)
                ->delete();
        }
            
        // Mencari chapter sebelumnya (berdasarkan nomor chapter yang lebih kecil)
        $prevChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '<', $chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();

        // Mencari chapter selanjutnya (berdasarkan nomor chapter yang lebih besar)
        $nextChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '>', $chapter_number)
            ->orderBy('chapter_number', 'asc')
            ->first();
        
        
        // --- TAMBAHKAN BLOK INI ---
        if (\Illuminate\Support\Facades\Auth::check()) {
            \App\Models\ReadingHistory::updateOrCreate(
                ['user_id' => \Illuminate\Support\Facades\Auth::id(), 'manga_id' => $manga->id],
                ['chapter_id' => $chapter->id, 'updated_at' => now()]
            );
        }
        // --------------------------
        
        // Jangan lupa kirim $prevChapter dan $nextChapter ke tampilan (view)
        return view('manga.read', compact('chapter', 'prevChapter', 'nextChapter'));
    }
}