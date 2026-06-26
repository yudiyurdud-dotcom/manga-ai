<?php

// Kode ini diletakkan di app/Http/Controllers/AdminUserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = User::latest();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate(15);
        return view('admin.user.index', compact('users', 'search'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi tingkat dewa: Mencegah admin secara tidak sengaja menurunkan jabatannya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa mengubah jabatanmu sendiri.');
        }

        $request->validate(['role' => 'required|in:user,admin']);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Hak akses pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi tingkat dewa: Mencegah admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus secara permanen.');
    }
}