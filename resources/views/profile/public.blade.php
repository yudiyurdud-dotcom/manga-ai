<!-- Kode ini diletakkan di resources/views/profile/public.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5 justify-content-center">
    <div class="col-md-10">

        <!-- Banner Profil -->
        <div class="card border-0 mb-4 shadow-sm" style="background-color: #16181f; border-radius: 12px;">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center">
                <div class="position-relative me-md-4 mb-3 mb-md-0">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #252836;" alt="Foto Profil">
                    
                    <!-- Indikator Online/Offline -->
                    @if($isOnline)
                        <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-3 border-dark rounded-circle" title="Sedang Online"></span>
                    @else
                        <span class="position-absolute bottom-0 end-0 p-2 bg-secondary border border-3 border-dark rounded-circle" title="Offline"></span>
                    @endif
                </div>
                
                <div class="flex-grow-1 text-center text-md-start">
                    <h3 class="fw-bold mb-1 text-white">
                        {{ $user->name }} 
                        @if($user->role === 'admin') <span class="badge bg-warning text-dark ms-2 fs-6 rounded-pill">⭐ Admin</span> @endif
                    </h3>
                    <div class="text-muted small mb-2">
                        <span><i class="bi bi-calendar"></i> Bergabung {{ $user->created_at->format('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Library / Privasi -->
        @if($user->is_private)
            <div class="card border-0 shadow-sm text-center py-5" style="background-color: #16181f; border-radius: 12px;">
                <h1 class="text-muted mb-3">🔒</h1>
                <h4 class="text-white fw-bold">Akun Privat</h4>
                <p class="text-secondary">Pengguna ini menyembunyikan riwayat baca dan bookmark-nya.</p>
            </div>
        @else
            <!-- Tampilkan Riwayat & Bookmark seperti di Library Pribadi -->
            <h5 class="text-white fw-bold mb-3 mt-5 border-bottom border-secondary pb-2">Bookmark {{ $user->name }}</h5>
            <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
                @forelse($bookmarks as $bookmark)
                    <div class="col">
                        <div class="card bg-dark border-secondary h-100 manga-card">
                            <img src="{{ asset('storage/' . $bookmark->manga->cover_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-2 text-center">
                                <h6 class="text-white small fw-bold text-truncate mb-2">{{ $bookmark->manga->title }}</h6>
                                <a href="{{ route('manga.show', $bookmark->manga->slug) }}" class="btn btn-outline-primary btn-sm w-100">Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">Tidak ada bookmark publik.</div>
                @endforelse
            </div>
        @endif

    </div>
</div>
@endsection