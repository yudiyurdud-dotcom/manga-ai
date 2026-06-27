<!-- Kode ini diletakkan di resources/views/manga/index.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5">
    
    <!-- Judul Halaman -->
    <div class="col-md-12 mb-4">
        <h4 class="text-white fw-bold border-bottom border-secondary pb-2">Latest Update</h4>
    </div>

    <!-- Grid Katalog Komik ala MangaDex -->
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
        @forelse($mangas as $manga)
            @php
                // Logika Bendera Dinamis berdasarkan kolom 'type'
                // Default: Jepang (Manga)
                $flag = 'JP'; 
                
                if($manga->type == 'Manhwa') {
                    $flag = 'KR'; // Korea
                } elseif($manga->type == 'Manhua') {
                    $flag = 'CN'; // China
                } elseif($manga->type == 'Comic' || $manga->type == 'OEL') {
                    $flag = 'US'; // Amerika/Barat
                }
            @endphp

            <div class="col">
                <!-- Seluruh kartu dijadikan tautan agar mudah diklik -->
                <a href="{{ route('manga.show', $manga->slug) }}" class="text-decoration-none">
                    <div class="card bg-transparent border-0 h-100 manga-card position-relative overflow-hidden">
                        
                        <!-- Wadah Cover dengan Rasio Tetap (Aspect Ratio 2:3) -->
                        <div class="position-relative rounded shadow-sm" style="overflow: hidden; aspect-ratio: 2/3;">
                            
                            <!-- Ikon Negara (Kode) di Pojok Kanan Atas -->
                            <div class="position-absolute top-0 end-0 p-1 m-1 rounded fw-bold text-white shadow-sm" style="background-color: rgba(0,0,0,0.6); z-index: 10; font-size: 0.75rem; letter-spacing: 1px; backdrop-filter: blur(2px);">
                                {{ $flag }}
                            </div>

                            <!-- Gambar Cover -->
                            <img src="{{ asset('storage/' . $manga->cover_image) }}" class="w-100 h-100 cover-img" alt="Cover" style="object-fit: cover; transition: transform 0.3s ease;">
                            
                            <!-- Efek Gelap saat di-hover -->
                            <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0; transition: opacity 0.3s ease;"></div>
                        </div>

                        <!-- Detail Judul -->
                        <div class="pt-2">
                            <h6 class="text-white fw-bold mb-0 title-clamp">
                                {{ $manga->title }}
                            </h6>
                            <!-- Tambahan: Menampilkan Tipe Komik di bawah judul -->
                            <small class="text-muted">{{ $manga->type ?? 'Manga' }}</small>
                        </div>

                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5 w-100">
                <h5>Belum ada komik terbaru.</h5>
            </div>
        @endforelse
    </div>

    <!-- Tombol Navigasi Pagination -->
    <div class="col-md-12 d-flex justify-content-center mt-5 pagination-dark">
        {{ $mangas->links() }}
    </div>

</div>

<!-- CSS Kustom untuk Efek MangaDex -->
<style>
    /* Menghaluskan efek zoom saat kartu disentuh kursor */
    .manga-card:hover .cover-img {
        transform: scale(1.05);
    }
    .manga-card:hover .overlay {
        opacity: 0.4 !important;
    }
    .manga-card:hover .title-clamp {
        color: #0d6efd !important; /* Judul menyala biru saat disentuh */
    }

    /* Kunci utama agar teks terpotong sempurna tanpa bocor */
    .title-clamp {
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
        text-overflow: ellipsis;
        font-size: 0.9rem; 
        line-height: 1.4; /* Atur jarak antar baris */
        max-height: 2.8em; /* Kunci tingginya persis untuk 2 baris (1.4 * 2) */
    }
    
    /* Tema Gelap untuk Pagination */
    .pagination-dark .pagination {
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