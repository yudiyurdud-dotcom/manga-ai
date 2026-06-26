<!-- Kode ini diletakkan di resources/views/search/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <!-- Bagian Header & Tombol AI -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-end border-bottom border-secondary pb-3">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold text-white mb-1">🔍 Pencarian Lanjutan</h2>
                <p class="text-muted mb-0">Gunakan filter di bawah untuk menemukan komik spesifik.</p>
            </div>
            <div>
                <a href="{{ route('search.ai') }}" class="btn btn-outline-info rounded-pill fw-bold shadow-sm">
                    ✨ Coba Pencarian Cerdas AI
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- KOLOM KIRI: FORM FILTER -->
        <div class="col-lg-3 mb-4">
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h5 class="mb-0 text-white fw-bold">⚙️ Filter Komik</h5>
                </div>
                <div class="card-body">
                    <!-- Form menggunakan method GET agar hasil bisa di-share link-nya -->
                    <form action="{{ route('search') }}" method="GET">
                        
                        <!-- Pencarian Judul (Telah diperbaiki menjadi keyword) -->
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold small">Judul / Judul Alternatif</label>
                            <input type="text" name="keyword" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul..." value="{{ request('keyword') }}">
                        </div>

                        <!-- Accordion Filter Lanjutan -->
                        <div class="accordion accordion-dark" id="filterAccordion">
                            
                            <!-- Filter Detail Manga -->
                            <div class="accordion-item border-secondary bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-dark text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetails">
                                        Detail Kreator & Status
                                    </button>
                                </h2>
                                <div id="collapseDetails" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
                                    <div class="accordion-body pt-2 pb-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light small">Penulis (Author)</label>
                                            <input type="text" name="author" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ request('author') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-light small">Ilustrator (Artist)</label>
                                            <input type="text" name="artist" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ request('artist') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-light small">Demografi</label>
                                            <select name="demographic" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="">Semua Demografi</option>
                                                @foreach($availableDemographics ?? [] as $demo)
                                                    <option value="{{ $demo }}" {{ request('demographic') == $demo ? 'selected' : '' }}>{{ $demo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label text-light small">Status Rilis</label>
                                            <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary">
                                                <option value="">Semua Status</option>
                                                @foreach($availableStatuses ?? [] as $stat)
                                                    <option value="{{ $stat }}" {{ request('status') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Genre (Checkboxes) -->
                            <div class="accordion-item border-secondary bg-transparent">
                                <h2 class="accordion-header">
                                    <!-- Terbuka otomatis jika ada genre yang sedang difilter -->
                                    <button class="accordion-button {{ request('genres') ? '' : 'collapsed' }} bg-dark text-white shadow-none border-top border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGenres">
                                        Pilih Genre
                                    </button>
                                </h2>
                                <div id="collapseGenres" class="accordion-collapse collapse {{ request('genres') ? 'show' : '' }}" data-bs-parent="#filterAccordion">
                                    <div class="accordion-body pt-3">
                                        <div class="row g-2">
                                            @foreach($availableGenres ?? [] as $genre)
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <!-- Penamaan array genres[] agar bisa multi-select -->
                                                    <input class="form-check-input border-secondary" type="checkbox" name="genres[]" value="{{ $genre }}" id="genre_{{ $loop->index }}" 
                                                    {{ in_array($genre, request('genres', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-light small" for="genre_{{ $loop->index }}">
                                                        {{ $genre }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Tombol Submit & Reset -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill">Terapkan Filter</button>
                            <a href="{{ route('search') }}" class="btn btn-outline-danger btn-sm rounded-pill">Reset Pencarian</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: HASIL PENCARIAN -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0">Hasil Pencarian <span class="badge bg-secondary ms-2">{{ $mangas->total() }} Komik</span></h5>
            </div>

            <div class="row g-3">
                @forelse($mangas as $manga)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card bg-dark border-secondary h-100 manga-card shadow-sm position-relative">
                        <!-- Badge Status -->
                        @if($manga->status)
                            <span class="badge {{ $manga->status == 'Ongoing' ? 'bg-success' : ($manga->status == 'Completed' ? 'bg-primary' : 'bg-warning') }} position-absolute top-0 start-0 m-2 z-1">
                                {{ $manga->status }}
                            </span>
                        @endif

                        <a href="{{ route('manga.show', $manga->slug) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/' . $manga->cover_image) }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Cover {{ $manga->title }}">
                            <div class="card-body p-2 text-center">
                                <h6 class="text-white mb-1 text-truncate fw-bold" title="{{ $manga->title }}">{{ $manga->title }}</h6>
                                
                                <!-- Tampilkan List Genre dari relasi Many-to-Many -->
                                @if($manga->genres->isNotEmpty())
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">
                                        {{ $manga->genres->pluck('name')->implode(', ') }}
                                    </small>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h1 class="display-1 text-muted mb-3">📭</h1>
                    <h5 class="text-white">Tidak ada komik yang sesuai kriteria.</h5>
                    <p class="text-muted">Coba kurangi filter atau gunakan kata kunci lain.</p>
                </div>
                @endforelse
            </div>

            <!-- Navigasi Halaman (Pagination) -->
            <div class="mt-4">
                {{ $mangas->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom CSS agar Accordion terlihat menyatu dengan Dark Mode */
    .accordion-dark .accordion-button {
        color: #e0e0e0;
        background-color: #1a1a1a;
    }
    .accordion-dark .accordion-button:not(.collapsed) {
        color: #fff;
        background-color: #2b2b2b;
        box-shadow: none;
    }
    .accordion-dark .accordion-button::after {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>
@endsection