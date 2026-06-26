<!-- Kode ini diletakkan di resources/views/admin/chapter/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-white mb-0">Panel Admin - Kelola Chapter</h2>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <!-- Tombol diarahkan ke kelola komik karena penambahan chapter butuh ID komik -->
            <a href="{{ route('admin.manga.index') }}" class="btn btn-outline-primary fw-bold shadow-sm rounded-pill px-4">
                Pilih Komik untuk Tambah Chapter
            </a>
        </div>
    </div>

    <!-- Area Filter & Search Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.chapter.index') }}" method="GET" class="row g-2 align-items-center" id="filterForm">
                
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

                <div class="col-md-5 ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul komik atau judul chapter..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary fw-bold px-3" type="submit">Cari</button>
                        @if($search)
                            <a href="{{ route('admin.chapter.index') }}" class="btn btn-outline-danger fw-bold px-3">X</a>
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
                    <th scope="col" style="width: 30%;">Judul Komik</th>
                    <th scope="col" class="text-center">Chapter</th>
                    <th scope="col">Judul Chapter</th>
                    <th scope="col" class="text-center">Total Halaman</th>
                    <th scope="col" class="text-center">Diunggah</th>
                    <th scope="col" class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chapters as $chapter)
                <tr>
                    <td>
                        <span class="fw-bold d-block text-truncate text-primary" style="max-width: 250px;">
                            {{ $chapter->manga->title ?? 'Komik Tidak Ditemukan' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary fw-bold" style="font-size: 0.85rem;">CH {{ $chapter->chapter_number }}</span>
                    </td>
                    <td>
                        <span class="text-light text-truncate d-block" style="max-width: 200px;">
                            {{ $chapter->title ?? '-' }}
                        </span>
                    </td>
                    <td class="text-center text-muted small">
                        <!-- Menghitung relasi pages jika ada -->
                        {{ $chapter->pages ? $chapter->pages->count() : 0 }} Hal
                    </td>
                    <td class="text-center text-muted small">
                        {{ $chapter->created_at->format('d M Y') }}
                    </td>
                    <!-- Kode yang diperbarui -->
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Tombol Edit diaktifkan -->
                            <a href="{{ route('admin.chapter.edit', $chapter->id) }}" class="btn btn-outline-light btn-sm shadow-sm">Edit</a>
                            
                            <!-- Tombol Hapus diaktifkan -->
                            <form action="{{ route('admin.chapter.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Peringatan: Ini akan menghapus chapter beserta SELURUH gambarnya secara permanen. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        Tidak ada data chapter yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
        <div class="text-muted small mb-3 mb-md-0 fw-bold">
            Menampilkan {{ $chapters->firstItem() ?? 0 }} sampai {{ $chapters->lastItem() ?? 0 }} dari total {{ $chapters->total() }} chapter
        </div>
        <div class="pagination-dark">
            {{ $chapters->withQueryString()->links() }}
        </div>
    </div>

</div>

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