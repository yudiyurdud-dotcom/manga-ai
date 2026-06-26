<!-- Kode ini diletakkan di resources/views/admin/comment/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-white mb-0">Panel Admin - Kelola Komentar</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3 bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <!-- Area Filter & Search Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.comment.index') }}" method="GET" class="row g-2 align-items-center" id="filterForm">
                
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
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari isi komentar atau nama pengguna..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary fw-bold px-3" type="submit">Cari</button>
                        @if($search)
                            <a href="{{ route('admin.comment.index') }}" class="btn btn-outline-danger fw-bold px-3">X</a>
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
                    <th scope="col" style="width: 20%;">Pengguna</th>
                    <th scope="col" style="width: 35%;">Isi Komentar</th>
                    <th scope="col" style="width: 20%;">Lokasi</th>
                    <th scope="col" class="text-center" style="width: 15%;">Waktu</th>
                    <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" class="rounded-circle me-2 border border-secondary" style="width: 35px; height: 35px; object-fit: cover;">
                            <span class="fw-bold text-light text-truncate" style="max-width: 150px;">{{ $comment->user->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-light d-block text-truncate" style="max-width: 350px; font-size: 0.95rem;">
                            {{ $comment->comment_text }}
                        </span>
                    </td>
                    <td>
                        @if($comment->chapter_id)
                            <!-- Komentar berada di Halaman Chapter -->
                            <span class="badge bg-primary d-block text-truncate text-start" style="max-width: 200px;">
                                CH {{ $comment->chapter->chapter_number ?? '?' }} - {{ $comment->manga->title ?? 'Komik' }}
                            </span>
                        @elseif($comment->manga_id)
                            <!-- Komentar berada di Halaman Utama Komik -->
                            <span class="badge bg-secondary d-block text-truncate text-start" style="max-width: 200px;">
                                Info - {{ $comment->manga->title ?? 'Komik' }}
                            </span>
                        @else
                            <span class="badge bg-dark border border-secondary text-muted">Balasan Komentar</span>
                        @endif
                    </td>
                    <td class="text-center text-muted small">
                        {{ $comment->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        Tidak ada data komentar yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
        <div class="text-muted small mb-3 mb-md-0 fw-bold">
            Menampilkan {{ $comments->firstItem() ?? 0 }} sampai {{ $comments->lastItem() ?? 0 }} dari total {{ $comments->total() }} komentar
        </div>
        <div class="pagination-dark">
            {{ $comments->withQueryString()->links() }}
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