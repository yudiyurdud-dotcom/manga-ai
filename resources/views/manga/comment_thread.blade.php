<!-- Kode ini diletakkan di resources/views/manga/comment_thread.blade.php -->
@extends('layout')

@section('content')
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-4">&larr; Kembali ke Komik</a>

        <!-- Komentar Utama -->
        <div class="card border-primary mb-4" style="background-color: #16181f;">
            <div class="card-header bg-primary text-white fw-bold border-0 d-flex justify-content-between align-items-center">
                <span>Komentar Utama oleh {{ $comment->user->name }}</span>
                <span class="badge bg-dark">{{ $comment->manga->title }}</span>
            </div>
            <div class="card-body p-4 text-white">
                <p class="mb-0 fs-5">{{ $comment->comment }}</p>
                <small class="text-muted d-block mt-3">{{ $comment->created_at->format('d M Y - H:i') }}</small>
            </div>
        </div>

        <hr class="border-secondary mb-4">

        <!-- Form Balasan -->
        <div class="card border-0 mb-5" style="background-color: #1e212b;">
            <div class="card-body">
                <form action="{{ route('comment.reply', $comment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="comment" rows="3" class="form-control bg-dark text-white border-secondary" placeholder="Tulis balasanmu di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success px-4">Kirim Balasan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Balasan -->
        <h5 class="text-white fw-bold mb-3"><i class="bi bi-chat-dots"></i> {{ $comment->replies->count() }} Balasan</h5>
        <div class="list-group">
            @forelse($comment->replies as $reply)
                <!-- Tambahkan id="comment-{{ $comment->id }}" pada elemen pembungkus -->
<div class="list-group-item px-0 py-3" id="comment-{{ $comment->id }}">
    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
        
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random&size=32" class="rounded-circle me-2">
            <!-- Username nanti akan kita buat menjadi link ke Profil Publik -->
            <a href="#" class="mb-0 fw-bold text-white text-decoration-none">{{ $comment->user->name }}</a>
        </div>
        
        <div>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            
            <!-- Tautan Share/Deep Link Komentar -->
            <a href="{{ url()->current() }}#comment-{{ $comment->id }}" class="text-secondary small ms-2 text-decoration-none" title="Bagikan Komentar ini">🔗 Link</a>
            
            <a href="{{ route('comment.thread', $comment->id) }}" class="text-decoration-none text-info small ms-2 fw-bold">Lihat Balasan &rarr;</a>
        </div>
        
    </div>
    <p class="mb-1 ms-5 small" style="text-align: justify;">{{ $comment->comment_text }}</p>
</div>
            @empty
                <div class="text-muted text-center py-4">Belum ada balasan. Jadilah yang pertama membalas!</div>
            @endforelse
        </div>

    </div>
</div>
@endsection