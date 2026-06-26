<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function index()
    {
        // Menarik data pengaturan pertama. Jika tabel masih kosong, otomatis buat data default.
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            ['site_name' => 'Manga-AI', 'maintenance_mode' => false]
        );

        return view('admin.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
        ]);

        $setting = Setting::first();
        
        // Memperbarui data. Toggle switch mengirimkan nilai 'on' jika dicentang, kita ubah jadi boolean.
        $setting->update([
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'maintenance_mode' => $request->has('maintenance_mode') ? true : false,
        ]);

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}