<!-- Kode ini diletakkan di resources/views/manga/read.blade.php -->
@extends('layout')

@section('content')

<!-- CSS Kustom untuk Efek Seamless, Lazy Loading, dan Floating Menu -->
<style>
    /* Desain pembungkus gambar */
    .page-wrapper {
        position: relative;
        width: 100%;
        min-height: 80vh;
        background-color: #121212; 
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .page-wrapper::before {
        content: "Memuat gambar...";
        position: absolute;
        color: #444;
        font-weight: bold;
        font-size: 1rem;
        z-index: 1;
    }

    .manga-image {
        width: 100%;
        height: auto;
        display: block;
        z-index: 2; 
        position: relative;
    }

    .manga-reader-container .reader-img {
        margin-bottom: -1px; 
    }
    
    .hover-primary:hover {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }

    /* === FITUR BARU: Floating Action Button (FAB) === */
    .fab-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
    }
    
    .fab-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        transition: transform 0.2s;
        cursor: pointer;
    }

    .fab-btn:hover {
        transform: scale(1.1);
    }
    
    /* Overlay untuk Kecerahan */
    #brightnessOverlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0);
        pointer-events: none;
        z-index: 1040;
        transition: background-color 0.2s;
    }
</style>

<!-- Overlay Kecerahan Layar -->
<div id="brightnessOverlay"></div>

<!-- 1. HEADER INFO CHAPTER -->
<div class="row mt-3 mb-4 justify-content-center">
    <div class="col-md-10 d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom border-secondary pb-3">
        <div class="text-center text-md-start mb-3 mb-md-0">
            <h3 class="fw-bold text-white mb-1">{{ $chapter->manga->title }}</h3>
            <h6 class="text-secondary mb-0">
                Chapter {{ $chapter->chapter_number }} 
                @if($chapter->title) <span class="text-muted"> - {{ $chapter->title }}</span> @endif
            </h6>
        </div>
        <div>
            <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-outline-secondary btn-sm px-4 rounded-pill fw-bold shadow-sm">
                &larr; Detail Komik
            </a>
        </div>
    </div>
</div>

<!-- 2. AREA BACA KOMIK (SEAMLESS & LAZY LOAD) -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-7 p-0 shadow-lg manga-reader-container" style="background-color: #000000; border-radius: 8px; overflow: hidden;" id="readerArea">
        @forelse($chapter->pages->sortBy('page_number') as $page)
            <div class="page-wrapper">
                <img src="{{ asset($page->image_path) }}" class="img-fluid w-100 d-block m-0 p-0 reader-img manga-image" alt="Halaman {{ $page->page_number }}" loading="lazy">
            </div>
        @empty
            <div class="p-5 text-center text-muted" style="background-color: #1a1a1a; min-height: 50vh; display: flex; align-items: center; justify-content: center;">
                <h5 class="mb-0">Belum ada gambar yang diunggah untuk chapter ini.</h5>
            </div>
        @endforelse
    </div>
</div>

<!-- 3. TOMBOL NAVIGASI NEXT / PREV -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-7 d-flex justify-content-between align-items-center bg-dark p-3 rounded shadow-sm border border-secondary border-opacity-50">
        
        @if($prevChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $prevChapter->chapter_number]) }}" class="btn btn-dark px-4 rounded-pill border-secondary fw-bold hover-primary" title="Chapter Sebelumnya (Panah Kiri)">
                &larr; Prev
            </a>
        @else
            <button class="btn btn-dark px-4 rounded-pill border-secondary text-muted" disabled>&larr; Prev</button>
        @endif

        <span class="text-muted small fw-bold">CH {{ $chapter->chapter_number }}</span>

        @if($nextChapter)
            <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $nextChapter->chapter_number]) }}" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm" title="Chapter Selanjutnya (Panah Kanan)">
                Next &rarr;
            </a>
        @else
            <button class="btn btn-secondary px-4 rounded-pill fw-bold" disabled>Next &rarr;</button>
        @endif

    </div>
</div>

<!-- 4. AREA KOMENTAR CHAPTER -->
<!-- ... (KODE KOMENTAR ASLI TETAP SAMA SEPERTI SEBELUMNYA) ... -->
<div class="row justify-content-center mb-5">
    <div class="col-md-10 col-lg-8">
        <h4 class="fw-bold border-bottom border-secondary pb-2 text-white">Komentar Chapter ({{ $chapter->comments->count() }})</h4>
        
        @if(session('success'))
            <div class="alert alert-success mt-3 bg-success text-white border-0">{{ session('success') }}</div>
        @endif

        @auth
        <form action="{{ route('comment.store', $chapter->id) }}" method="POST" class="mb-4 mt-4 p-3 rounded" style="background-color: #1e1e1e;">
            @csrf
            <div class="mb-3">
                <textarea name="comment_text" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Tulis komentarmu untuk chapter ini..." required></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">Kirim Komentar</button>
            </div>
        </form>
        @else
        <div class="alert mt-3 text-white border-secondary" style="background-color: #1e1e1e;">
            Silakan <a href="/login" class="fw-bold text-primary text-decoration-none">Login</a> terlebih dahulu untuk memberikan komentar.
        </div>
        @endauth

        <div class="list-group mt-4">
            @forelse($chapter->comments->sortByDesc('created_at') as $comment)
            <!-- ID Deep Linking -->
            <div class="list-group-item px-4 py-3 bg-transparent text-white border-secondary mb-3 rounded shadow-sm" style="background-color: #16181f !important;" id="comment-{{ $comment->id }}">
                <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        
                        <!-- Link Profil Publik -->
                        <a href="{{ route('profile.public', $comment->user->id) }}" class="mb-0 fw-bold text-decoration-none {{ $comment->user->role == 'admin' ? 'text-warning' : 'text-white' }}">
                            {{ $comment->user->name }}
                            @if($comment->user->role == 'admin') <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Admin</span> @endif
                        </a>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">{{ $comment->created_at->diffForHumans() }}</small>
                        <a href="{{ url()->current() }}#comment-{{ $comment->id }}" class="text-secondary small text-decoration-none me-2" title="Bagikan Komentar ini">🔗 Link</a>
                        <a href="{{ route('comment.thread', $comment->id) }}" class="text-decoration-none text-info small fw-bold">Balasan &rarr;</a>
                    </div>
                </div>
                <p class="mb-1 small text-light" style="text-align: justify; line-height: 1.6;">{{ $comment->comment_text }}</p>
            </div>
            @empty
            <p class="text-muted small text-center mt-4">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            @endforelse
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- FITUR BARU: FLOATING BUTTON & OFFCANVAS SETTINGS -->
<!-- ========================================== -->

<!-- Tombol Gigi Mengambang di Pojok Kanan Bawah -->
<div class="fab-container">
    <button class="btn btn-primary fab-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#settingsOffcanvas" aria-controls="settingsOffcanvas" title="Pengaturan Membaca (Kecerahan, Auto-Scroll, Daftar Chapter)">
        ⚙️
    </button>
</div>

<!-- Offcanvas (Panel Geser dari Bawah) -->
<div class="offcanvas offcanvas-bottom text-bg-dark rounded-top-4" tabindex="-1" id="settingsOffcanvas" style="height: auto; max-height: 85vh;">
    <div class="offcanvas-header border-bottom border-secondary px-4 py-3">
        <h5 class="offcanvas-title fw-bold"><span class="text-primary">⚙️ Pengaturan</span> Membaca</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <!-- 1. Slider Kecerahan Layar -->
                <div class="mb-4">
                    <label for="brightnessSlider" class="form-label text-light fw-bold mb-2" title="Atur gelap terangnya area baca">
                        💡 Kecerahan Layar: <span id="brightnessValue" class="text-primary">100%</span>
                    </label>
                    <input type="range" class="form-range" id="brightnessSlider" min="20" max="100" value="100" oninput="updateBrightness(this.value)">
                </div>

                <!-- 2. Tombol Auto Scroll -->
                <div class="mb-4">
                    <label class="form-label text-light fw-bold mb-2" title="Layar akan menggulir sendiri secara otomatis ke bawah">
                        ⏬ Scroll Otomatis (Kecepatan)
                    </label>
                    <div class="btn-group w-100 shadow-sm" role="group">
                        <button type="button" class="btn btn-outline-danger active fw-bold" id="btnScrollOff" onclick="setAutoScroll(0, this)">Off</button>
                        <button type="button" class="btn btn-outline-light fw-bold" onclick="setAutoScroll(1, this)" title="Kecepatan Lambat">1x</button>
                        <button type="button" class="btn btn-outline-light fw-bold" onclick="setAutoScroll(2, this)" title="Kecepatan Normal">2x</button>
                        <button type="button" class="btn btn-outline-light fw-bold" onclick="setAutoScroll(4, this)" title="Kecepatan Cepat">3x</button>
                    </div>
                </div>

                <!-- 3. Daftar Chapter (Accordion/Collapse) -->
                <div class="mt-4 border-top border-secondary pt-4">
                    <button class="btn btn-outline-primary w-100 fw-bold rounded-pill shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#chapterListCollapse" aria-expanded="false" title="Lihat dan cari chapter lain dari komik ini">
                        ☰ Buka Daftar Chapter
                    </button>
                    
                    <div class="collapse mt-3" id="chapterListCollapse">
                        <div class="card card-body bg-dark border-secondary p-3">
                            <!-- Kolom Pencarian Chapter -->
                            <input type="text" id="searchChapterInput" class="form-control bg-dark text-white border-secondary mb-3" placeholder="Cari nomor atau judul chapter..." onkeyup="filterChapters()">
                            
                            <!-- Area List yang bisa di-scroll -->
                            <div class="list-group" style="max-height: 200px; overflow-y: auto;" id="chapterListArea">
                                <!-- Mengambil semua chapter dari manga ini -->
                                @foreach($chapter->manga->chapters->sortByDesc('chapter_number') as $listChap)
                                    <a href="{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $listChap->chapter_number]) }}" 
                                       class="list-group-item list-group-item-action bg-transparent text-light border-secondary chapter-item {{ $listChap->id === $chapter->id ? 'active bg-primary border-primary' : '' }}">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">Chapter {{ $listChap->chapter_number }}</span>
                                            @if($listChap->title) <small class="text-truncate ms-2" style="max-width: 150px;">{{ $listChap->title }}</small> @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT UNTUK FITUR BACA -->
<script>
    // --- 1. FITUR KECERAHAN LAYAR ---
    function updateBrightness(value) {
        document.getElementById('brightnessValue').innerText = value + '%';
        // Menghitung opacity dari warna hitam (100% = opacity 0, 20% = opacity 0.8)
        let opacity = (100 - value) / 100;
        document.getElementById('brightnessOverlay').style.backgroundColor = `rgba(0, 0, 0, ${opacity})`;
    }

    // --- 2. FITUR AUTO SCROLL ---
    let scrollInterval;
    function setAutoScroll(speed, btnElement) {
        // Hapus status aktif dari semua tombol scroll
        const buttons = btnElement.parentElement.querySelectorAll('.btn');
        buttons.forEach(btn => {
            btn.classList.remove('active', 'btn-outline-danger');
            btn.classList.add('btn-outline-light');
        });

        // Setel tombol yang diklik menjadi aktif
        btnElement.classList.add('active');
        if (speed === 0) {
            btnElement.classList.add('btn-outline-danger');
            btnElement.classList.remove('btn-outline-light');
        } else {
            btnElement.classList.add('btn-outline-primary');
            btnElement.classList.remove('btn-outline-light');
        }

        // Hentikan scroll yang sedang berjalan
        clearInterval(scrollInterval);

        // Jika tidak 0, jalankan interval scroll
        if (speed > 0) {
            scrollInterval = setInterval(() => {
                // Scroll layar ke bawah sejumlah pixel (speed) setiap 30 milidetik
                window.scrollBy(0, speed);
            }, 30);
        }
    }

    // Jika user menggerakkan layar secara manual (scroll up), jangan matikan auto-scroll,
    // biarkan saja agar terasa seperti video yang diputar namun bisa di-seek.

    // --- 3. FITUR PENCARIAN CHAPTER ---
    function filterChapters() {
        let input = document.getElementById("searchChapterInput").value.toLowerCase();
        let items = document.querySelectorAll('.chapter-item');
        
        items.forEach(item => {
            let text = item.innerText.toLowerCase();
            if (text.includes(input)) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }

    // --- 4. FITUR KEYBOARD NAVIGATION (PANAH KIRI/KANAN) ---
    document.addEventListener('keydown', function(event) {
        // Jangan eksekusi jika user sedang mengetik di kolom komentar atau kolom pencarian
        if (event.target.tagName.toLowerCase() === 'textarea' || event.target.tagName.toLowerCase() === 'input') {
            return;
        }

        @if($prevChapter)
            if (event.key === "ArrowLeft") {
                window.location.href = "{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $prevChapter->chapter_number]) }}";
            }
        @endif

        @if($nextChapter)
            if (event.key === "ArrowRight") {
                window.location.href = "{{ route('chapter.read', ['slug' => $chapter->manga->slug, 'chapter_number' => $nextChapter->chapter_number]) }}";
            }
        @endif
    });
</script>

@endsection