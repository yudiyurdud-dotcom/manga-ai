<?php

// Kode ini diletakkan di app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // 1. Menyimpan Komentar Utama
    public function store(Request $request, $chapter_id)
    {
        // Validasi komentar (maksimal 1000 karakter)
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        // Simpan ke database dan tampung datanya ke variabel $newComment
        $newComment = Comment::create([
            'user_id' => Auth::id(),
            'chapter_id' => $chapter_id,
            'comment_text' => $request->comment_text,
        ]);

        // Redirect kembali ke halaman sebelumnya ditambah ID komentar agar layar auto-scroll ke bawah
        return redirect(url()->previous() . '#comment-' . $newComment->id)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }

    // 2. Menampilkan Halaman Khusus Utas Balasan Komentar
    public function showThread($id)
    {
        // Tarik komentar utama beserta relasi balasannya
        $comment = Comment::with(['user', 'manga', 'replies.user'])->findOrFail($id);
        return view('manga.comment_thread', compact('comment'));
    }

    // 3. Menyimpan Balasan dari Komentar Utama
    public function reply(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        $parentComment = Comment::findOrFail($id);

        $newComment = Comment::create([
            'user_id' => Auth::id(),
            'manga_id' => $parentComment->manga_id,
            'chapter_id' => $parentComment->chapter_id,
            'parent_id' => $parentComment->id, // Ini yang menjadikannya sebuah balasan
            'comment_text' => $request->comment, // Disesuaikan dengan nama kolom databasemu
        ]);

        // Redirect kembali ke halaman sebelumnya ditambah ID komentar agar layar auto-scroll
        return redirect(url()->previous() . '#comment-' . $newComment->id)
            ->with('success', 'Balasan berhasil dikirim!');
    }

    // Menyimpan Komentar di Halaman Detail Komik (Manga)
    public function storeMangaComment(Request $request, $manga_id)
    {
        $request->validate(['comment_text' => 'required|string|max:1000']);

        $newComment = Comment::create([
            'user_id' => Auth::id(),
            'manga_id' => $manga_id,
            'chapter_id' => null, // Dikosongkan karena ini bukan komentar chapter
            'comment_text' => $request->comment_text,
        ]);

        return redirect(url()->previous() . '#comment-' . $newComment->id)->with('success', 'Komentar berhasil ditambahkan!');
    }
}