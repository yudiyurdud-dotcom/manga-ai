<!-- Kode ini diletakkan di resources/views/admin/chapter/create.blade.php -->
@extends('layout')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">
                Tambah Chapter Baru - {{ $manga->title }}
            </div>
            <div class="card-body p-4">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.chapter.store', $manga->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nomor Chapter</label>
                            <input type="text" name="chapter_number" class="form-control" placeholder="Contoh: 01, 02, atau 1.5" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Judul Chapter (Opsional)</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Pertemuan Pertama">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold text-danger">Unggah Halaman Komik (Bisa Pilih Banyak Sekaligus)</label>
                        <!-- Atribut 'multiple' dan name 'pages[]' sangat penting di sini -->
                        <input type="file" name="pages[]" class="form-control" accept="image/*" multiple required>
                        <small class="text-muted mt-1 d-block">
                            * Blok semua gambar halaman dari folder komputermu. Sistem akan mengurutkannya berdasarkan nama file. Pastikan nama file gambarnya sudah urut (contoh: 01.jpg, 02.jpg, 03.jpg).
                        </small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.manga.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">Unggah Chapter</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection