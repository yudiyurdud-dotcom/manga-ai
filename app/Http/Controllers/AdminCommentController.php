<?php

// Kode ini diletakkan di app/Http/Controllers/AdminCommentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class AdminCommentController extends Controller
{
    // Menampilkan daftar seluruh komentar
    public function index(Request $request)
    {
        // 1. Memori jumlah data yang ditampilkan (Session)
        $perPage = $request->input('per_page', session('admin_comment_per_page', 10));
        session(['admin_comment_per_page' => $perPage]);

        // 2. Tangkap kata kunci pencarian
        $search = $request->input('search');

        // 3. Bangun Query (Tarik data komentar beserta relasi user, manga, dan chapter)
        $query = Comment::with(['user', 'manga', 'chapter'])->latest();

        // 4. Saring berdasarkan isi komentar atau nama pengguna
        if ($search) {
            $query->where('comment_text', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        // 5. Eksekusi dengan pagination
        $comments = $query->paginate($perPage);

        return view('admin.comment.index', compact('comments', 'search', 'perPage'));
    }

    // Menghapus komentar
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Hapus komentar (jika ada balasan/thread, Laravel akan menghapusnya otomatis jika ada relasi cascade di database)
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}