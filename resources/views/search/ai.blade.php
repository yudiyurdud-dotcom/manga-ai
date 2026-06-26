<!-- Kode ini diletakkan di resources/views/search/ai.blade.php -->
@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <div class="d-inline-block bg-info bg-opacity-10 text-info fw-bold px-3 py-1 rounded-pill mb-3 border border-info">
                ✨ Semantic Search by Artificial Intelligence
            </div>
            <h1 class="text-white fw-bold mb-3">Lupa Judul Komik?</h1>
            <p class="text-muted fs-5">Ceritakan saja alur cerita, karakter, atau momen yang kamu ingat. AI kami akan memproses maknanya dan mencarikan komiknya untukmu!</p>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card bg-dark border-secondary shadow-lg" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <form action="{{ route('search.ai') }}" method="GET">
                        <div class="mb-3">
                            <textarea name="keyword" class="form-control bg-dark text-white border-secondary fs-5 p-3" rows="4" placeholder="Contoh: Ada anak laki-laki berambut kuning yang ingin menjadi ketua desa dan dia punya monster rubah ekor sembilan..." required>{{ request('keyword') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('search') }}" class="text-secondary text-decoration-none small">
                                &larr; Gunakan Filter Biasa
                            </a>
                            <button type="submit" class="btn btn-info fw-bold px-5 rounded-pill shadow-sm text-dark">
                                🧠 Eksekusi AI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if(request('keyword'))
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h5 class="text-white mb-4 border-bottom border-secondary pb-2">Hasil Analisis AI untuk: <span class="text-info">"{{ request('keyword') }}"</span></h5>
            
            <div class="row g-3">
                @forelse($mangas as $manga)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card bg-dark border-secondary h-100 manga-card shadow-sm position-relative border-info" style="border-width: 2px;">
                        <span class="badge bg-info text-dark position-absolute top-0 end-0 m-2 z-1 fw-bold">✨ AI Match</span>
                        <a href="{{ route('manga.show', $manga->slug) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/' . $manga->cover_image) }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Cover">
                            <div class="card-body p-2 text-center">
                                <h6 class="text-white mb-1 text-truncate fw-bold">{{ $manga->title }}</h6>
                            </div>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h1 class="display-1 text-muted mb-3">🤖</h1>
                    <h5 class="text-white">AI tidak menemukan kecocokan sinopsis.</h5>
                    <p class="text-muted">Coba deskripsikan jalan ceritanya dengan kata-kata yang berbeda.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $mangas->withQueryString()->links() }}</div>
        </div>
    </div>
    @endif
</div>
@endsection