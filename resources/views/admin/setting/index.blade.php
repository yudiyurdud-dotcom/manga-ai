<!-- Kode ini diletakkan di resources/views/admin/setting/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-white mb-0">Pengaturan Website</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
            @endif

            <div class="card bg-dark border-secondary shadow-lg rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.setting.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Website -->
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Nama Website / Brand</label>
                            <input type="text" name="site_name" class="form-control bg-dark text-white border-secondary" value="{{ old('site_name', $setting->site_name) }}" required>
                            <small class="text-muted">Nama ini akan muncul di tab browser dan navigasi utama.</small>
                        </div>
                        
                        <!-- Deskripsi Website -->
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Deskripsi Website (SEO)</label>
                            <textarea name="site_description" class="form-control bg-dark text-white border-secondary" rows="4" placeholder="Website baca komik terbaik...">{{ old('site_description', $setting->site_description) }}</textarea>
                            <small class="text-muted">Jelaskan secara singkat tentang website ini agar mudah ditemukan di pencarian Google.</small>
                        </div>

                        <hr class="border-secondary my-4">

                        <!-- Mode Perbaikan (Maintenance) -->
                        <div class="mb-4">
                            <h5 class="text-white fw-bold mb-3">Sistem & Keamanan</h5>
                            <div class="form-check form-switch d-flex align-items-center mb-2">
                                <input class="form-check-input me-3 mt-0" type="checkbox" role="switch" id="maintenance_mode" name="maintenance_mode" style="transform: scale(1.3);" {{ $setting->maintenance_mode ? 'checked' : '' }}>
                                <label class="form-check-label text-light fw-bold" for="maintenance_mode">Aktifkan Maintenance Mode</label>
                            </div>
                            <small class="text-warning">Jika aktif, hanya admin yang bisa mengakses website. Pengunjung biasa akan melihat halaman "Sedang Perbaikan".</small>
                        </div>
                        
                        <div class="text-end mt-4 border-top border-secondary pt-4">
                            <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow">Simpan Pengaturan</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection