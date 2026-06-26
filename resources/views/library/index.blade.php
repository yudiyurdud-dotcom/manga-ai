<!-- Kode ini diletakkan di resources/views/library/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <!-- Header & Toggle View -->
    <div class="d-flex justify-content-between align-items-end border-bottom border-secondary pb-3 mb-4">
        <div>
            <h2 class="fw-bold text-white mb-0">📚 Library Saya</h2>
            <p class="text-muted mb-0 small">Kelola koleksi komik dan riwayat bacamu.</p>
        </div>
        
        <!-- Toggle View Mode (Seperti File Explorer) -->
        <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-primary" id="btn-grid" onclick="setViewMode('grid')" title="Large Icons (Grid)">
                🔲 Grid
            </button>
            <button type="button" class="btn btn-outline-secondary" id="btn-list" onclick="setViewMode('list')" title="Details (List)">
                📄 List
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <!-- Nav Tabs untuk Bookmark & History -->
    <ul class="nav nav-tabs border-secondary mb-4" id="libraryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-white bg-dark border-secondary border-bottom-0" id="bookmark-tab" data-bs-toggle="tab" data-bs-target="#bookmark-pane" type="button" role="tab">
                🔖 Bookmark
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-secondary border-secondary" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" type="button" role="tab">
                🕒 History Baca
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="libraryTabsContent">
        
        <!-- TAB 1: BOOKMARK -->
        <div class="tab-pane fade show active" id="bookmark-pane" role="tabpanel">
            
            <!-- TAMPILAN GRID (Large Icons) -->
            <div class="row g-3 view-grid">
                @forelse($bookmarks as $bm)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card bg-dark border-secondary h-100 position-relative manga-card shadow-sm">
                        <!-- Tombol Hapus Bookmark -->
                        <form action="{{ route('bookmark.toggle', $bm->manga->id) }}" method="POST" class="position-absolute top-0 end-0 m-1 z-3">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="width:28px; height:28px;" title="Hapus Bookmark" onclick="return confirm('Hapus komik ini dari bookmark?')">✖</button>
                        </form>
                        
                        <a href="{{ route('manga.show', $bm->manga->slug) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/' . $bm->manga->cover_image) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Cover">
                            <div class="card-body p-2 text-center">
                                <h6 class="text-white mb-0 text-truncate small fw-bold">{{ $bm->manga->title }}</h6>
                            </div>
                        </a>
                    </div>
                </div>
                @empty
                    <p class="text-muted w-100 text-center py-5">Belum ada komik di bookmark.</p>
                @endforelse
            </div>

            <!-- TAMPILAN LIST (Details) -->
            <div class="list-group view-list" style="display: none;">
                @forelse($bookmarks as $bm)
                <div class="list-group-item bg-dark border-secondary text-white d-flex justify-content-between align-items-center mb-2 rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $bm->manga->cover_image) }}" style="width: 50px; height: 70px; object-fit: cover;" class="rounded me-3">
                        <div>
                            <a href="{{ route('manga.show', $bm->manga->slug) }}" class="text-white text-decoration-none fw-bold h5 mb-1 d-block">{{ $bm->manga->title }}</a>
                            <small class="text-muted">Disimpan: {{ $bm->created_at->format('d M Y - H:i') }}</small>
                        </div>
                    </div>
                    <form action="{{ route('bookmark.toggle', $bm->manga->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold rounded-pill px-3" onclick="return confirm('Hapus komik ini dari bookmark?')">Hapus</button>
                    </form>
                </div>
                @empty
                    <p class="text-muted w-100 text-center py-5">Belum ada komik di bookmark.</p>
                @endforelse
            </div>
            
            <!-- Pagination Bookmark -->
            <div class="mt-4">{{ $bookmarks->links() }}</div>
        </div>


        <!-- TAB 2: HISTORY BACA -->
        <div class="tab-pane fade" id="history-pane" role="tabpanel">
            
            <!-- TAMPILAN GRID (Large Icons) -->
            <div class="row g-3 view-grid">
                @forelse($histories as $history)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card bg-dark border-secondary h-100 position-relative manga-card shadow-sm">
                        <a href="{{ route('chapter.read', ['slug' => $history->manga->slug, 'chapter_number' => $history->chapter->chapter_number]) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/' . $history->manga->cover_image) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Cover">
                            <!-- Overlay Info Chapter -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-1" style="background: rgba(0,0,0,0.8);">
                                <small class="text-warning fw-bold d-block text-center">Ch. {{ $history->chapter->chapter_number }}</small>
                            </div>
                        </a>
                    </div>
                    <div class="p-1 text-center">
                        <h6 class="text-white mb-0 text-truncate small">{{ $history->manga->title }}</h6>
                        <small class="text-muted" style="font-size: 0.65rem;">{{ $history->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                    <p class="text-muted w-100 text-center py-5">Belum ada riwayat membaca komik.</p>
                @endforelse
            </div>

            <!-- TAMPILAN LIST (Details) -->
            <div class="list-group view-list" style="display: none;">
                @forelse($histories as $history)
                <div class="list-group-item bg-dark border-secondary text-white d-flex justify-content-between align-items-center mb-2 rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $history->manga->cover_image) }}" style="width: 50px; height: 70px; object-fit: cover;" class="rounded me-3">
                        <div>
                            <a href="{{ route('chapter.read', ['slug' => $history->manga->slug, 'chapter_number' => $history->chapter->chapter_number]) }}" class="text-white text-decoration-none fw-bold h5 mb-1 d-block">
                                {{ $history->manga->title }}
                            </a>
                            <span class="badge bg-primary text-white me-2">Chapter {{ $history->chapter->chapter_number }}</span>
                            <small class="text-muted">Dibaca: {{ $history->updated_at->format('d M Y - H:i') }}</small>
                        </div>
                    </div>
                    <a href="{{ route('chapter.read', ['slug' => $history->manga->slug, 'chapter_number' => $history->chapter->chapter_number]) }}" class="btn btn-outline-info btn-sm fw-bold rounded-pill px-3">
                        Lanjut Baca
                    </a>
                </div>
                @empty
                    <p class="text-muted w-100 text-center py-5">Belum ada riwayat membaca komik.</p>
                @endforelse
            </div>

            <!-- Pagination History -->
            <div class="mt-4">{{ $histories->links() }}</div>
        </div>

    </div>
</div>

<script>
    // Menyimpan memori pilihan tampilan (Grid/List) ke Browser (Local Storage)
    function setViewMode(mode) {
        localStorage.setItem('library_view_mode', mode);
        
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');
        const viewGrids = document.querySelectorAll('.view-grid');
        const viewLists = document.querySelectorAll('.view-list');

        if (mode === 'list') {
            btnGrid.classList.replace('btn-primary', 'btn-outline-secondary');
            btnList.classList.replace('btn-outline-secondary', 'btn-primary');
            viewGrids.forEach(el => el.style.setProperty('display', 'none', 'important'));
            viewLists.forEach(el => el.style.setProperty('display', 'block', 'important'));
        } else {
            btnList.classList.replace('btn-primary', 'btn-outline-secondary');
            btnGrid.classList.replace('btn-outline-secondary', 'btn-primary');
            viewLists.forEach(el => el.style.setProperty('display', 'none', 'important'));
            viewGrids.forEach(el => el.style.setProperty('display', 'flex', 'important'));
        }
    }

    // Memuat tampilan saat halaman dibuka
    document.addEventListener("DOMContentLoaded", function() {
        const savedMode = localStorage.getItem('library_view_mode') || 'grid';
        setViewMode(savedMode);

        // Memperbaiki tab agar kembali ke tab semula setelah pindah halaman (Pagination)
        const activeTab = localStorage.getItem('active_library_tab');
        if (activeTab) {
            let tabElement = new bootstrap.Tab(document.querySelector(activeTab));
            tabElement.show();
        }
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('active_library_tab', '#' + e.target.id);
            });
        });
    });
</script>
@endsection