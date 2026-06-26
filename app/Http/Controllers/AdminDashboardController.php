<?php

// Kode ini diletakkan di app/Http/Controllers/AdminDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\User;
use App\Models\Comment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Menghitung total data keseluruhan
        $totalManga = Manga::count();
        $totalChapter = Chapter::count();
        $totalUser = User::count();
        $totalComment = Comment::count();

        // Mengambil data terbaru untuk ditampilkan di tabel ringkasan
        $recentUsers = User::latest()->take(5)->get();
        $recentComments = Comment::with(['user', 'manga'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalManga', 
            'totalChapter', 
            'totalUser', 
            'totalComment',
            'recentUsers',
            'recentComments'
        ));
    }
}