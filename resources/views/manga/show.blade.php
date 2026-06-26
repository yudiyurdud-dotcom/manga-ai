<!-- Kode ini diletakkan di resources/views/manga/show.blade.php -->
@extends('layout')

@section('content')
<!-- 1. HERO SECTION (Ala MangaDex & Webtoon) -->
<div class="position-relative overflow-hidden mb-5 shadow-sm" style="border-radius: 12px; background-color: #121212;">
    
    <!-- Efek Background Blur -->
    <div class="position-absolute w-100 h-100" style="background-image: url('{{ $manga->cover_image ? asset('storage/' . $manga->cover_image) : 'https://via.placeholder.com/400x550' }}'); background-size: cover; background-position: center; filter: blur(25px); opacity: 0.25; z-index: 1;"></div>
    
    <!-- Overlay Gradasi Gelap -->
    <div class="position-relative p-4 p-md-5" style="z-index: 2; background: linear-gradient(to right, rgba(18,18,18,1) 0%, rgba(18,18,18,0.6) 100%);">
        <div class="row align-items-center">
            
            <!-- Kolom Kiri: Cover & Action -->
            <div class="col-md-3 col-lg-3 mb-4 mb-md-0 text-center">
                <img src="{{ $manga->cover_image ? asset('storage/' . $manga->cover_image) : 'https://via.placeholder.com/400x550' }}" class="img-fluid rounded shadow w-100 mb-3 border border-secondary border-opacity-50" style="aspect-ratio: 2/3; object-fit: cover;" alt="Cover {{ $manga->title }}">
                
                @auth
                    @php
                        // Cek apakah komik ini sudah ada di daftar bookmark milik user yang sedang login
                        $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
                                                            ->where('manga_id', $manga->id)
                                                            ->exists();
                    @endphp
                    
                    <form action="{{ route('bookmark.toggle', $manga->id) }}" method="POST">
                        @csrf
                        @if($isBookmarked)
                            <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill shadow-sm py-2" title="Klik lagi untuk menghapus dari Bookmark">
                                ✅ Tersimpan di Bookmark
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold rounded-pill shadow-sm py-2 bg-dark">
                                🔖 Simpan ke Bookmark
                            </button>
                        @endif
                    </form>
                @endauth
            </div>

            <!-- Kolom Kanan: Detail Informasi -->
            <div class="col-md-9 col-lg-9 text-white">
                <!-- Judul Utama -->
                <h1 class="fw-bold mb-1">{{ $manga->title }}</h1>
                
                <!-- Alternative Titles -->
                @if($manga->alternative_titles)
                    <h6 class="text-secondary mb-3 fst-italic">{{ $manga->alternative_titles }}</h6>
                @else
                    <div class="mb-3"></div> <!-- Spacing jika tidak ada judul alternatif -->
                @endif
                
                <!-- Info Author & Artist -->
                <div class="d-flex flex-wrap align-items-center mb-4" style="font-size: 0.95rem;">
                    <div class="me-4 mb-2 mb-md-0">
                        <span class="text-secondary fw-bold">✍️ Author:</span> 
                        <span class="text-light ms-1">{{ $manga->author ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-secondary fw-bold">🎨 Artist:</span> 
                        <span class="text-light ms-1">{{ $manga->artist ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- Kapsul Status, Demografi, Tema & Genre -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @php
                        $statusColor = 'primary'; // Default completed
                        if($manga->status == 'ongoing') $statusColor = 'success';
                        if($manga->status == 'hiatus') $statusColor = 'warning text-dark';
                    @endphp
                    <span class="badge bg-{{ $statusColor }} rounded-pill px-3 py-2 text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                        {{ $manga->status }}
                    </span>

                    @if($manga->type)
                        <span class="badge bg-info text-dark rounded-pill px-3 py-2 fw-bold" title="Tipe Komik">
                            📖 {{ $manga->type }}
                        </span>
                    @endif

                    @if($manga->demographic)
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-bold">
                            👥 {{ $manga->demographic }}
                        </span>
                    @endif

                    @if($manga->theme)
                        @foreach(explode(',', $manga->theme) as $theme)
                            <span class="badge bg-secondary rounded-pill px-3 py-2 border border-light border-opacity-25">{{ trim($theme) }}</span>
                        @endforeach
                    @endif

                    @foreach($manga->genres as $genre)
                        <span class="badge bg-dark rounded-pill px-3 py-2 border border-secondary">{{ $genre->name }}</span>
                    @endforeach
                </div>

                <!-- Sinopsis -->
                <h5 class="fw-bold mb-2 text-white border-bottom border-secondary pb-2 d-inline-block">Sinopsis</h5>
                <p class="text-light mt-2" style="line-height: 1.8; text-align: justify; font-size: 0.95rem;">
                    {{ $manga->synopsis ?? 'Sinopsis belum tersedia untuk komik ini.' }}
                </p>
            </div>

        </div>
    </div>
</div>

<!-- 2. DAFTAR CHAPTER -->
<div class="row mb-5 justify-content-center">
    <div class="col-md-10">
        <h4 class="fw-bold mb-4 text-white border-bottom border-secondary pb-2">Daftar Chapter</h4>
        
        @if($manga->chapters->count() > 0)
            <div class="list-group rounded shadow-sm" style="background-color: #1e1e1e;">
                @foreach($manga->chapters as $chapter)
                    <a href="{{ route('chapter.read', ['slug' => $manga->slug, 'chapter_number' => $chapter->chapter_number]) }}" class="list-group-item list-group-item-action bg-transparent text-white border-secondary d-flex justify-content-between align-items-center p-3 chapter-hover transition-all">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold fs-5 me-3 text-primary">CH {{ $chapter->chapter_number }}</span>
                            @if($chapter->title)
                                <span class="text-light">{{ $chapter->title }}</span>
                            @endif
                        </div>
                        <span class="text-muted small">{{ $chapter->created_at->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="alert text-center text-muted" style="background-color: #1e1e1e; border: 1px solid #333;">
                Belum ada chapter yang tersedia untuk komik ini.
            </div>
        @endif
    </div>
</div>

<!-- 3. BAGIAN KOMENTAR DETAIL KOMIK -->
<div class="row mb-5 justify-content-center">
    <div class="col-md-10">
        @php 
            $mangaComments = App\Models\Comment::where('manga_id', $manga->id)->whereNull('chapter_id')->latest()->get(); 
        @endphp
        
        <h4 class="fw-bold text-white border-bottom border-secondary pb-2">Komentar Komik ({{ $mangaComments->count() }})</h4>
        
        @if(session('success'))
            <div class="alert alert-success mt-3 bg-success text-white border-0">{{ session('success') }}</div>
        @endif

        @auth
        <form action="{{ route('manga.comment.store', $manga->id) }}" method="POST" class="mb-4 mt-4 p-3 rounded" style="background-color: #1e1e1e;">
            @csrf
            <div class="mb-3">
                <textarea name="comment_text" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Bagaimana pendapatmu tentang komik ini secara keseluruhan?" required></textarea>
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
            @forelse($mangaComments as $comment)
            <!-- ID Deep Linking -->
            <div class="list-group-item px-4 py-3 bg-transparent text-white border-secondary mb-3 rounded" style="background-color: #16181f !important;" id="comment-{{ $comment->id }}">
                <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        
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

<!-- CSS Tambahan untuk Efek Interaktif Chapter -->
<style>
    .chapter-hover:hover {
        background-color: #2a2a2a !important;
        transform: translateX(5px);
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection