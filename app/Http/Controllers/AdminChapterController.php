<?php

// Kode ini diletakkan di app/Http/Controllers/AdminChapterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class AdminChapterController extends Controller
{

public function index(Request $request)
    {
        // 1. Memori jumlah data yang ditampilkan
        $perPage = $request->input('per_page', session('admin_chapter_per_page', 10));
        session(['admin_chapter_per_page' => $perPage]);

        // 2. Tangkap kata kunci pencarian
        $search = $request->input('search');

        // 3. Bangun Query (Tarik data chapter beserta relasi judul komiknya)
        $query = Chapter::with('manga')->latest();

        // 4. Saring berdasarkan judul chapter atau judul komik
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('manga', function($q) use ($search) {
                      $q->where('title', 'like', '%' . $search . '%');
                  });
        }

        // 5. Eksekusi dengan pagination
        $chapters = $query->paginate($perPage);

        return view('admin.chapter.index', compact('chapters', 'search', 'perPage'));
    }

    // Proteksi Admin
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Akses Ditolak.');
            }
            return $next($request);
        });
    }

    // Menampilkan form tambah chapter untuk komik tertentu
    public function create($manga_id)
    {
        $manga = Manga::findOrFail($manga_id);
        return view('admin.chapter.create', compact('manga'));
    }

    // Menyimpan chapter dan banyak halaman gambar sekaligus
    public function store(Request $request, $manga_id)
    {
        $request->validate([
            'chapter_number' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            // Validasi file 'pages' harus berupa array gambar
            'pages' => 'required|array',
            'pages.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072' // Maks 3MB per gambar
        ]);

        $manga = Manga::findOrFail($manga_id);

        // 1. Simpan data Chapter
        $chapter = Chapter::create([
            'manga_id' => $manga->id,
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
        ]);

        // 2. Proses Multi-Upload Gambar
        if ($request->hasFile('pages')) {
            $pageNumber = 1;
            foreach ($request->file('pages') as $file) {
                // Simpan gambar ke storage/app/public/pages
                $imagePath = $file->store('pages', 'public');

                // Simpan data halaman ke database
                Page::create([
                    'chapter_id' => $chapter->id,
                    'page_number' => $pageNumber,
                    'image_path' => asset('storage/' . $imagePath), // Langsung buat jadi URL utuh
                ]);

                $pageNumber++;
            }
        }

        return redirect()->back()->with('success', 'Chapter berhasil diunggah! Silakan tambah chapter selanjutnya.');
    }

    // Menampilkan halaman edit chapter beserta gambar-gambarnya
    public function edit($id)
    {
        // Menarik data chapter beserta halamannya, diurutkan dari nomor terkecil
        $chapter = \App\Models\Chapter::with(['pages' => function($query) {
            $query->orderBy('page_number', 'asc');
        }])->findOrFail($id);

        return view('admin.chapter.edit', compact('chapter'));
    }

    // Memproses pembaruan chapter dan penambahan gambar baru
    public function update(Request $request, $id)
    {
        $request->validate([
            'chapter_number' => 'required|numeric',
            'title' => 'nullable|string|max:255',
            'pages.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048' // Validasi untuk gambar-gambar baru
        ]);

        $chapter = \App\Models\Chapter::findOrFail($id);
        
        // Update informasi dasar
        $chapter->update([
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
        ]);

        // Jika admin mengunggah gambar-gambar baru
        if ($request->hasFile('pages')) {
            // Cari nomor urut halaman terakhir untuk menyambung urutan
            $lastPage = $chapter->pages()->orderBy('page_number', 'desc')->first();
            $nextPageNumber = $lastPage ? $lastPage->page_number + 1 : 1;

            foreach ($request->file('pages') as $file) {
                $path = $file->store('manga_pages', 'public');
                
                \App\Models\Page::create([
                    'chapter_id' => $chapter->id,
                    'page_number' => $nextPageNumber,
                    'image_path' => '/storage/' . $path // Format path agar langsung terbaca di frontend
                ]);
                
                $nextPageNumber++;
            }
        }

        return redirect()->route('admin.chapter.edit', $chapter->id)->with('success', 'Chapter berhasil diperbarui dan gambar ditambahkan!');
    }

    // Menghapus spesifik satu gambar dari chapter
    public function destroyPage($id)
    {
        $page = \App\Models\Page::findOrFail($id);
        
        // Hapus file fisik dari folder storage Laragon
        $imagePath = str_replace('/storage/', '', $page->image_path);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
        }
        
        $page->delete();

        return back()->with('success', 'Gambar berhasil dihapus dari chapter.');
    }

    // Menghapus keseluruhan chapter beserta semua gambarnya
    public function destroy($id)
    {
        $chapter = \App\Models\Chapter::findOrFail($id);
        
        // Hapus semua file gambar fisik yang menempel di chapter ini
        foreach($chapter->pages as $page) {
            $imagePath = str_replace('/storage/', '', $page->image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
        }
        
        $chapter->delete(); 
        
        return redirect()->route('admin.chapter.index')->with('success', 'Chapter beserta seluruh gambarnya berhasil dihapus!');
    }
}