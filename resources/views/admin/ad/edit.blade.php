@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-white mb-0">Edit Iklan</h3>
                <a href="{{ route('admin.ad.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold">Batal & Kembali</a>
            </div>

            <div class="card bg-dark border-secondary shadow-lg rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.ad.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Nama Sponsor / Judul Iklan</label>
                            <input type="text" name="title" class="form-control bg-dark text-white border-secondary" value="{{ old('title', $ad->title) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Link URL Tujuan</label>
                            <input type="url" name="link_url" class="form-control bg-dark text-white border-secondary" value="{{ old('link_url', $ad->link_url) }}" placeholder="https://..." required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-light fw-bold">Posisi Tayang</label>
                                <select name="position" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="header" {{ old('position', $ad->position) == 'header' ? 'selected' : '' }}>Header (Atas)</option>
                                    <option value="footer" {{ old('position', $ad->position) == 'footer' ? 'selected' : '' }}>Footer (Bawah)</option>
                                    <option value="sidebar" {{ old('position', $ad->position) == 'sidebar' ? 'selected' : '' }}>Sidebar (Samping)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light fw-bold">Status Iklan</label>
                                <select name="is_active" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="1" {{ old('is_active', $ad->is_active) == '1' ? 'selected' : '' }}>Aktif Tayang</option>
                                    <option value="0" {{ old('is_active', $ad->is_active) == '0' ? 'selected' : '' }}>Disembunyikan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold d-block">Ganti Banner Iklan (Opsional)</label>
                            
                            <div class="d-flex align-items-center mt-2 mb-3 p-2 border border-secondary rounded bg-dark">
                                <img src="{{ asset('storage/' . $ad->image_path) }}" class="rounded shadow-sm me-3 border border-secondary" style="width: 120px; height: 60px; object-fit: cover;" alt="Banner Saat Ini">
                                <small class="text-muted">Banner saat ini. Biarkan kolom file di bawah kosong jika tidak ingin mengubah gambar.</small>
                            </div>
                            
                            <input type="file" name="image_path" class="form-control bg-dark text-white border-secondary" accept="image/*">
                        </div>
                        
                        <div class="text-end mt-4 border-top border-secondary pt-4">
                            <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow">Simpan Perubahan</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection