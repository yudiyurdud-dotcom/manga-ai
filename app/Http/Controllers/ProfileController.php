<?php

// Kode ini diletakkan di app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // Mengubah Nama Tampilan
    // Mengubah Nama Tampilan & Privasi
    public function updateInfo(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $user = Auth::user();
        $user->name = $request->name;
        // Jika checkbox dicentang, nilainya 1. Jika tidak, nilainya 0 (false)
        $user->is_private = $request->has('is_private') ? true : false; 
        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui!');
    }

    // Mengubah Password dan Paksa Logout
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Auth::logout(); // Paksa pengguna untuk login ulang
        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
    }

    // Mengunggah Foto Profil Menggunakan AJAX
    public function uploadAvatar(Request $request)
    {
        // Validasi: Harus berupa gambar, max 5MB (5120 KB)
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', 
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Hapus foto lama dari penyimpanan lokal jika sudah punya
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder storage/app/public/avatars
            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $imagePath;
            $user->save();

            // Mengembalikan respons JSON untuk ditangkap oleh AJAX
            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/' . $imagePath)
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    // Menampilkan Profil Publik Pengguna
    public function showPublic($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Cek apakah user aktif dalam 5 menit terakhir
        $isOnline = $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->diffInMinutes(now()) < 5;

        $histories = [];
        $bookmarks = [];
        
        // Jika akun TIDAK diprivat, tarik data riwayat dan bookmark
        if (!$user->is_private) {
            $histories = \App\Models\ReadingHistory::with(['manga', 'chapter'])->where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
            $bookmarks = \App\Models\Bookmark::with('manga')->where('user_id', $user->id)->latest()->get();
        }

        return view('profile.public', compact('user', 'isOnline', 'histories', 'bookmarks'));
    }
}