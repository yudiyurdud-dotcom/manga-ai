<?php

// Kode ini diletakkan di app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Proteksi: Hanya Admin yang boleh mengakses controller ini
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Akses Ditolak: Anda bukan Admin.');
            }
            return $next($request);
        });
    }

    // Menampilkan daftar komik di panel admin
    public function index(Request $request)
    {
        // 1. Ambil nilai 'per_page' dari URL. Jika tidak ada, cek di Session. Jika masih kosong, default 10.
        $perPage = $request->input('per_page', session('admin_per_page', 10));
        
        // 2. Simpan pilihan per_page ini ke Session agar tidak hilang saat admin menekan F5 (Refresh)
        session(['admin_per_page' => $perPage]);

        // 3. Tangkap kata kunci pencarian
        $search = $request->input('search');

        // 4. Bangun Query
        $query = \App\Models\Manga::query()->latest();

        // 5. Jika admin mengetik sesuatu di kotak pencarian, saring datanya
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
        }

        // 6. Eksekusi query dengan pagination yang dinamis
        $mangas = $query->paginate($perPage);

        return view('admin.manga.index', compact('mangas', 'search', 'perPage'));
    }

    // Menampilkan formulir tambah komik
    public function create()
    {
        // Ambil semua nama genre untuk dijadikan auto-complete
        $genres = \App\Models\Genre::pluck('name'); 
        return view('admin.manga.create', compact('genres'));
    }

    // Menyimpan data komik baru dan mengunggah gambar
    public function store(Request $request)
{
    $request->validate([
    'title' => 'required|string|max:255',
    'type' => 'required|in:Manga,Manhwa,Manhua,Comic,OEL', // Tambahkan baris ini
    'status' => 'required|in:ongoing,completed,hiatus',
    'cover_image' => 'nullable|image|max:2048'
]);

    $data = $request->except(['cover_image', 'genres']);
    
    // PERBAIKAN: Menghapus . '-' . time() agar URL bersih hanya berisi judul komik
    $data['slug'] = \Illuminate\Support\Str::slug($request->title);

    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    }

    $manga = \App\Models\Manga::create($data);

    // LOGIKA TAGIFY: Menyimpan dan membuat genre baru secara dinamis
    $this->syncGenres($manga, $request->genres);

    return redirect()->route('admin.manga.index')->with('success', 'Komik berhasil ditambahkan!');
}

    // Menampilkan halaman form edit komik
    public function edit($id)
    {
        $manga = \App\Models\Manga::with('genres')->findOrFail($id);
        $allGenres = \App\Models\Genre::pluck('name');
        
        // Format genre komik ini menjadi string dipisah koma untuk Tagify
        $currentGenres = $manga->genres->pluck('name')->implode(', ');

        return view('admin.manga.edit', compact('manga', 'allGenres', 'currentGenres'));
    }

    // Memproses pembaruan data komik ke database
    public function update(Request $request, $id)
    {
        $request->validate([
    'title' => 'required|string|max:255',
    'type' => 'required|in:Manga,Manhwa,Manhua,Comic,OEL', // Tambahkan baris ini
    'status' => 'required|in:ongoing,completed,hiatus',
    'cover_image' => 'nullable|image|max:2048'
]);

        $manga = \App\Models\Manga::findOrFail($id);
        $data = $request->except(['cover_image', 'genres']);

        if ($request->hasFile('cover_image')) {
            if ($manga->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($manga->cover_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($manga->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $manga->update($data);

        // LOGIKA TAGIFY: Menyimpan dan membuat genre baru secara dinamis
        $this->syncGenres($manga, $request->genres);

        return redirect()->route('admin.manga.index')->with('success', 'Data komik berhasil diperbarui!');
    }

    // Fungsi Bantuan Pribadi untuk Mengelola Genre dari Tagify
    private function syncGenres($manga, $genresInput)
{
    $genreIds = [];

    if ($genresInput) {
        // Tagify biasanya mengirim data dalam bentuk string JSON
        $genres = json_decode($genresInput, true);
        
        // Loop setiap genre yang diinputkan admin
        foreach ($genres as $g) {
            $genreName = $g['value']; 

            // PERBAIKANNYA ADA DI SINI
            // Kita gunakan firstOrCreate dengan 2 parameter array
            $genre = \App\Models\Genre::firstOrCreate(
                ['name' => $genreName], // Parameter 1: Yang dicari di database
                ['slug' => Str::slug($genreName)] // Parameter 2: Ekstra data jika genre belum ada dan harus dibuat baru
            );

            $genreIds[] = $genre->id;
        }
    }

    // Sinkronisasi data ke pivot table (manga_genres)
    $manga->genres()->sync($genreIds);
}

    // Menghapus komik secara permanen beserta file cover-nya
    public function destroy($id)
    {
        $manga = \App\Models\Manga::findOrFail($id);
        
        if ($manga->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($manga->cover_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($manga->cover_image);
        }
        
        $manga->delete();
        
        return redirect()->route('admin.manga.index')->with('success', 'Komik berhasil dihapus secara permanen!');
    }
}