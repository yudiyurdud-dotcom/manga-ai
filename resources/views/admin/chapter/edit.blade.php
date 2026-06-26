<!-- Kode ini diletakkan di resources/views/admin/chapter/edit.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1">Edit Chapter</h3>
            <h6 class="text-primary">{{ $chapter->manga->title }} - Chapter {{ $chapter->chapter_number }}</h6>
        </div>
        <a href="{{ route('admin.chapter.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold">Batal & Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        
        <!-- BAGIAN KIRI: Form Edit Info & Tambah Gambar -->
        <div class="col-lg-4 mb-4">
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-header border-secondary bg-transparent">
                    <h5 class="fw-bold text-white mb-0 mt-2">Informasi Chapter</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chapter.update', $chapter->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Nomor Chapter <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="chapter_number" class="form-control bg-dark text-white border-secondary" value="{{ old('chapter_number', $chapter->chapter_number) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Judul Chapter (Opsional)</label>
                            <input type="text" name="title" class="form-control bg-dark text-white border-secondary" value="{{ old('title', $chapter->title) }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Tambah Gambar Baru</label>
                            <input type="file" name="pages[]" class="form-control bg-dark text-white border-secondary" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">Bisa pilih banyak gambar sekaligus. Gambar baru akan diletakkan di urutan paling akhir.</small>
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold w-100 shadow-sm">Simpan Perubahan & Unggah</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: Galeri Gambar Chapter (Manajemen Halaman) -->
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0 mt-2">Kelola Halaman ({{ $chapter->pages->count() }} Gambar)</h5>
                </div>
                <div class="card-body">
                    
                    @if($chapter->pages->count() > 0)
                        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
                            @foreach($chapter->pages as $page)
                                <div class="col">
                                    <div class="card bg-transparent border-secondary h-100 position-relative overflow-hidden">
                                        <!-- Indikator Nomor Halaman -->
                                        <div class="position-absolute top-0 start-0 p-1 m-1 bg-dark text-white rounded fw-bold shadow-sm" style="font-size: 0.75rem; z-index: 10;">
                                            Hal {{ $page->page_number }}
                                        </div>
                                        
                                        <!-- Gambar -->
                                        <img src="{{ asset($page->image_path) }}" class="card-img-top w-100" style="aspect-ratio: 2/3; object-fit: cover;" alt="Hal {{ $page->page_number }}">
                                        
                                        <!-- Tombol Hapus Gambar -->
                                        <div class="card-footer bg-transparent border-top-0 p-2 text-center">
                                            <form action="{{ route('admin.page.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <h5>Belum ada gambar di chapter ini.</h5>
                            <p>Gunakan form di sebelah kiri untuk mengunggah gambar.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection