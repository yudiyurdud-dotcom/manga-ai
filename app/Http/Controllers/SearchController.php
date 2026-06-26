<?php

// Kode ini diletakkan di app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Manga;

class SearchController extends Controller
{
    // ==========================================
    // 1. PENCARIAN FILTER LANJUTAN (Konvensional)
    // ==========================================
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Manga::with('genres');

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('alternative_titles', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('author')) {
            $query->where('author', 'like', "%{$request->author}%");
        }

        if ($request->filled('artist')) {
            $query->where('artist', 'like', "%{$request->artist}%");
        }

        if ($request->filled('demographic')) {
            $query->where('demographic', $request->demographic);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('genres')) {
            $selectedGenres = $request->genres;
            foreach ($selectedGenres as $genre) {
                $query->whereHas('genres', function($q) use ($genre) {
                    $q->where('name', $genre); 
                });
            }
        }

        $mangas = $query->latest()->paginate(12)->withQueryString();

        // MENGAMBIL GENRE DARI DATABASE
        $availableGenres = \App\Models\Genre::orderBy('name', 'asc')->pluck('name');
        
        // DIKEMBALIKAN KE ARRAY KARENA TIDAK ADA TABELNYA DI DATABASE
        $availableDemographics = ['Shounen', 'Shoujo', 'Seinen', 'Josei'];
        $availableStatuses = ['Ongoing', 'Completed', 'Hiatus', 'Dropped'];

        return view('search.index', compact(
            'mangas', 
            'keyword', 
            'availableGenres', 
            'availableDemographics', 
            'availableStatuses'
        ));
    }

    // ==========================================
    // 2. PENCARIAN CERDAS BERBASIS AI (Semantic Search)
    // ==========================================
    public function aiSearch(Request $request)
    {
        $keyword = $request->input('keyword');
        
        if (!$keyword) {
            $mangas = Manga::with('genres')->latest()->paginate(12);
            return view('search.ai', compact('mangas', 'keyword'));
        }

        $allMangas = Manga::all();
        
        if ($allMangas->isEmpty()) {
            $mangas = Manga::where('id', 0)->paginate(12);
            return view('search.ai', compact('mangas', 'keyword'))
                ->with('error', 'Belum ada data komik di database untuk dicari.');
        }

        $synopsisList = [];
        $mangaIdMap = [];

        foreach ($allMangas as $index => $manga) {
            $synopsisList[] = $manga->synopsis;
            $mangaIdMap[$index] = $manga->id; 
        }

        try {
            $response = Http::post('http://127.0.0.1:5000/api/search', [
                'query' => $keyword,
                'synopsis_list' => $synopsisList
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'success') {
                    $mangaIds = [];
                    
                    foreach ($responseData['data'] as $aiResult) {
                        if ($aiResult['skor_kemiripan'] > 0.1) {
                            $mangaIds[] = $mangaIdMap[$aiResult['index_komik']];
                        }
                    }
                    
                    if (!empty($mangaIds)) {
                        $idString = implode(',', $mangaIds);
                        
                        $mangas = Manga::with('genres')
                            ->whereIn('id', $mangaIds)
                            ->orderByRaw("FIELD(id, $idString)") 
                            ->paginate(12);
                            
                        return view('search.ai', compact('mangas', 'keyword'));
                    }
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke layanan AI. Pastikan script Python (app.py) sedang berjalan.');
        }

        $mangas = Manga::where('id', 0)->paginate(12); 
        return view('search.ai', compact('mangas', 'keyword'));
    }
}