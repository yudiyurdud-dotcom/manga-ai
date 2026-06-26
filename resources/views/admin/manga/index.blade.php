<!-- Kode ini diletakkan di resources/views/admin/manga/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <!-- Bagian Header (Judul & Tombol Tambah) -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-white mb-0">Panel Admin - Kelola Komik</h2>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.manga.create') }}" class="btn btn-primary fw-bold shadow-sm rounded-pill px-4">
                + Tambah Komik Baru
            </a>
        </div>
    </div>

    <!-- Area Filter & Search Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
            <!-- ID filterForm ini penting agar JavaScript bisa melakukan auto-submit -->
            <form action="{{ route('admin.manga.index') }}" method="GET" class="row g-2 align-items-center" id="filterForm">
                
                <!-- Opsi Tampilkan X Data -->
                <div class="col-auto d-flex align-items-center">
                    <label for="per_page" class="text-light me-2 small fw-bold">Tampilkan:</label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm bg-dark text-white border-secondary" style="width: auto; cursor: pointer;" onchange="document.getElementById('filterForm').submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-light ms-2 small">entri</span>
                </div>

                <!-- Kolom Pencarian -->
                <div class="col-md-5 ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul atau author komik..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary fw-bold px-3" type="submit">Cari</button>
                        @if($search)
                            <!-- Tombol Reset akan muncul jika sedang mencari sesuatu -->
                            <a href="{{ route('admin.manga.index') }}" class="btn btn-outline-danger fw-bold px-3">X</a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Tabel Data Utama -->
    <div class="table-responsive rounded shadow-sm border border-secondary" style="background-color: #1e1e1e;">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="table-active">
                <tr>
                    <th scope="col" class="text-center" style="width: 80px;">Cover</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Author</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center" style="width: 250px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mangas as $manga)
                <tr>
                    <td class="text-center p-2">
                        <img src="{{ asset('storage/' . $manga->cover_image) }}" alt="Cover" class="rounded shadow-sm" style="width: 50px; height: 70px; object-fit: cover;">
                    </td>
                    <td>
                        <span class="fw-bold d-block text-truncate" style="max-width: 300px; font-size: 0.95rem;">{{ $manga->title }}</span>
                        <!-- Menampilkan jumlah chapter yang dimiliki komik ini -->
                        <span class="badge bg-secondary mt-1" style="font-size: 0.7rem;">{{ $manga->chapters->count() }} Chapter</span>
                    </td>
                    <td><span class="text-light small">{{ $manga->author }}</span></td>
                    <td class="text-center">
                        <span class="badge bg-{{ $manga->status == 'ongoing' ? 'success' : 'primary' }} px-3 py-2" style="border-radius: 6px;">
                            {{ ucfirst($manga->status) }}
                        </span>
                    </td>
                    <!-- Kode yang diperbarui -->
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.chapter.create', $manga->id) }}" class="btn btn-success btn-sm fw-bold shadow-sm">+ Chapter</a>
                            
                            <!-- Tombol Edit sudah nyala -->
                            <a href="{{ route('admin.manga.edit', $manga->id) }}" class="btn btn-outline-light btn-sm shadow-sm">Edit</a>
                            
                            <!-- Tombol Hapus sudah nyala -->
                            <form action="{{ route('admin.manga.destroy', $manga->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komik ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <h4 class="mb-2">🤷‍♂️</h4>
                        Tidak ada data komik yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Pagination di Bawah Tabel -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
        <div class="text-muted small mb-3 mb-md-0 fw-bold">
            Menampilkan {{ $mangas->firstItem() ?? 0 }} sampai {{ $mangas->lastItem() ?? 0 }} dari total {{ $mangas->total() }} komik
        </div>
        <div class="pagination-dark">
            <!-- withQueryString() menjaga agar pencarian dan pilihan baris tidak kereset saat pindah halaman -->
            {{ $mangas->withQueryString()->links() }}
        </div>
    </div>

</div>

<!-- CSS Tambahan untuk Pagination Tema Gelap -->
<style>
    .pagination-dark .pagination {
        margin-bottom: 0;
        --bs-pagination-bg: #1e1e1e;
        --bs-pagination-border-color: #333;
        --bs-pagination-color: #e0e0e0;
        --bs-pagination-hover-bg: #2a2a2a;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-hover-border-color: #0d6efd;
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
    }
</style>
@endsection