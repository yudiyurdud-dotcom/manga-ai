<!-- Kode ini diletakkan di resources/views/admin/dashboard/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="row align-items-center mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-white mb-0">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}. Berikut adalah ringkasan sistem hari ini.</p>
        </div>
    </div>

    <!-- Baris Kartu Statistik -->
    <div class="row g-4 mb-5">
        <!-- Kartu Total Komik -->
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0d6efd !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-2">TOTAL KOMIK</h6>
                    <h2 class="text-white fw-bold mb-0">{{ number_format($totalManga) }}</h2>
                </div>
            </div>
        </div>
        <!-- Kartu Total Chapter -->
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-2">TOTAL CHAPTER</h6>
                    <h2 class="text-white fw-bold mb-0">{{ number_format($totalChapter) }}</h2>
                </div>
            </div>
        </div>
        <!-- Kartu Total Pengguna -->
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ffc107 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-2">TOTAL PENGGUNA</h6>
                    <h2 class="text-white fw-bold mb-0">{{ number_format($totalUser) }}</h2>
                </div>
            </div>
        </div>
        <!-- Kartu Total Komentar -->
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-2">TOTAL KOMENTAR</h6>
                    <h2 class="text-white fw-bold mb-0">{{ number_format($totalComment) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Tabel Ringkasan -->
    <div class="row g-4">
        <!-- Pengguna Baru Bergabung -->
        <div class="col-lg-6">
            <div class="card bg-dark border-secondary shadow-sm" style="border-radius: 12px;">
                <div class="card-header border-secondary bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white fw-bold">Pengguna Baru</h5>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-light rounded-pill">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($recentUsers as $user)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="rounded-circle me-3" style="width: 35px; height: 35px; object-fit: cover;">
                                            <div>
                                                <span class="d-block text-white fw-bold">{{ $user->name }}</span>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Komentar Terbaru -->
        <div class="col-lg-6">
            <div class="card bg-dark border-secondary shadow-sm" style="border-radius: 12px;">
                <div class="card-header border-secondary bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white fw-bold">Komentar Terbaru</h5>
                    <a href="{{ route('admin.comment.index') }}" class="btn btn-sm btn-outline-light rounded-pill">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($recentComments as $comment)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3 mt-1 text-primary">💬</div>
                                            <div>
                                                <span class="d-block text-white text-truncate" style="max-width: 300px;">"{{ $comment->comment_text }}"</span>
                                                <small class="text-muted">Oleh <strong>{{ $comment->user->name }}</strong> di {{ $comment->manga->title ?? 'Komik' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td class="text-center py-4 text-muted">Belum ada komentar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection