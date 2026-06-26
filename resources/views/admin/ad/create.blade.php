<!-- Kode ini diletakkan di resources/views/admin/ad/create.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-white mb-0">Tambah Iklan Baru</h3>
                <a href="{{ route('admin.ad.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold">Batal & Kembali</a>
            </div>

            <div class="card bg-dark border-secondary shadow-lg rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.ad.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Nama Sponsor / Judul Iklan</label>
                            <input type="text" name="title" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Link URL Tujuan</label>
                            <input type="url" name="link_url" class="form-control bg-dark text-white border-secondary" placeholder="https://..." required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-light fw-bold">Posisi Tayang</label>
                                <select name="position" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="header">Header (Atas)</option>
                                    <option value="footer">Footer (Bawah)</option>
                                    <option value="sidebar">Sidebar (Samping)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light fw-bold">Status Iklan</label>
                                <select name="is_active" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="1">Aktif Tayang</option>
                                    <option value="0">Disembunyikan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold d-block">Upload Banner Iklan</label>
                            <input type="file" name="image_path" class="form-control bg-dark text-white border-secondary" accept="image/*" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow">Simpan Iklan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection