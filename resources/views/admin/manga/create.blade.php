@extends('layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-white mb-0">Tambah Komik Baru</h3>
                <a href="{{ route('admin.manga.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold">Batal & Kembali</a>
            </div>

            <div class="card bg-dark border-secondary shadow-lg rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.manga.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Judul Komik Utama <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control bg-dark text-white border-secondary" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Alternative Titles</label>
                            <input type="text" name="alternative_titles" class="form-control bg-dark text-white border-secondary" value="{{ old('alternative_titles') }}" placeholder="Pisahkan dengan koma jika lebih dari satu">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-light fw-bold">Author (Penulis)</label>
                                <input type="text" name="author" class="form-control bg-dark text-white border-secondary" value="{{ old('author') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light fw-bold">Artist (Penggambar)</label>
                                <input type="text" name="artist" class="form-control bg-dark text-white border-secondary" value="{{ old('artist') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-light fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="hiatus" {{ old('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-light fw-bold">Theme</label>
                                <input type="text" name="theme" class="form-control bg-dark text-white border-secondary" value="{{ old('theme') }}" placeholder="Contoh: Reincarnation, Magic">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light fw-bold">Demographic</label>
                                <select name="demographic" class="form-select bg-dark text-white border-secondary">
                                    <option value="">-- Pilih --</option>
                                    <option value="Shounen" {{ old('demographic') == 'Shounen' ? 'selected' : '' }}>Shounen</option>
                                    <option value="Seinen" {{ old('demographic') == 'Seinen' ? 'selected' : '' }}>Seinen</option>
                                    <option value="Shoujo" {{ old('demographic') == 'Shoujo' ? 'selected' : '' }}>Shoujo</option>
                                    <option value="Josei" {{ old('demographic') == 'Josei' ? 'selected' : '' }}>Josei</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold d-block">Genre Komik</label>
                            <input name="genres" id="genresInput" class="form-control bg-dark border-secondary text-white" value="{{ old('genres') }}" placeholder="Ketik genre lalu tekan Enter atau koma...">
                            <small class="text-muted">Ketik nama genre. Jika belum ada di database, akan otomatis ditambahkan.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Sinopsis</label>
                            <textarea name="synopsis" class="form-control bg-dark text-white border-secondary" rows="5">{{ old('synopsis') }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-light fw-bold d-block">Upload Cover Image</label>
                            <input type="file" name="cover_image" class="form-control bg-dark text-white border-secondary" accept="image/*">
                        </div>
                        
                        <hr class="border-secondary mb-4">

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow">Simpan Komik Baru</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .tagify {
        --tags-border-color: #6c757d;
        --tags-hover-border-color: #0d6efd;
        --tags-focus-border-color: #0d6efd;
        --tag-bg: #2a2a2a;
        --tag-hover: #333;
        --tag-text-color: #fff;
        --tag-text-color--edit: #fff;
        --tag-remove-bg: rgba(255,0,0,0.3);
        --tag-remove-btn-color: #fff;
        --tag-remove-btn-bg--hover: #dc3545;
        border-radius: 0.375rem;
    }
    .tagify__dropdown {
        background-color: #1e1e1e;
        border: 1px solid #6c757d;
    }
    .tagify__dropdown__item {
        color: #fff;
    }
    .tagify__dropdown__item--active {
        background-color: #0d6efd;
        color: #fff;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.querySelector('#genresInput');
        // Daftar rekomendasi genre ditarik dari variabel $genres yang dikirim oleh AdminController@create
        var whitelist = {!! json_encode($genres) !!}; 
        
        new Tagify(input, {
            whitelist: whitelist,
            maxTags: 15,
            dropdown: {
                maxItems: 20,           // Batas maksimal saran yang muncul
                classname: "tags-look", // Class kustom untuk dropdown
                enabled: 0,             // Munculkan saran saat diklik walau belum mengetik
                closeOnSelect: false    // Jangan tutup dropdown setelah memilih
            }
        });
    });
</script>
@endsection