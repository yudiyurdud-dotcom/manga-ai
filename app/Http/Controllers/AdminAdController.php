<?php

// Kode ini diletakkan di app/Http/Controllers/AdminAdController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Storage;

class AdminAdController extends Controller
{
    public function index()
    {
        $ads = Advertisement::latest()->paginate(10);
        return view('admin.ad.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ad.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_path' => 'required|image|max:2048',
            'link_url' => 'required|url',
            'position' => 'required|in:header,footer,sidebar',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('image_path');
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('ads', 'public');
        }

        Advertisement::create($data);
        return redirect()->route('admin.ad.index')->with('success', 'Iklan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('admin.ad.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:2048',
            'link_url' => 'required|url',
            'position' => 'required|in:header,footer,sidebar',
            'is_active' => 'required|boolean',
        ]);

        $ad = Advertisement::findOrFail($id);
        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            if ($ad->image_path && Storage::disk('public')->exists($ad->image_path)) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $data['image_path'] = $request->file('image_path')->store('ads', 'public');
        }

        $ad->update($data);
        return redirect()->route('admin.ad.index')->with('success', 'Data Iklan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);
        if ($ad->image_path && Storage::disk('public')->exists($ad->image_path)) {
            Storage::disk('public')->delete($ad->image_path);
        }
        $ad->delete();
        
        return back()->with('success', 'Iklan berhasil dihapus secara permanen.');
    }
}