<!-- Kode ini diletakkan di resources/views/admin/ad/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-white mb-0">Panel Admin - Kelola Iklan</h2>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.ad.create') }}" class="btn btn-primary fw-bold shadow-sm rounded-pill px-4">
                + Tambah Iklan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3 bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <div class="table-responsive rounded shadow-sm border border-secondary" style="background-color: #1e1e1e;">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="table-active">
                <tr>
                    <th scope="col" style="width: 150px;">Banner</th>
                    <th scope="col">Nama Iklan</th>
                    <th scope="col">Posisi</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ads as $ad)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $ad->image_path) }}" class="rounded border border-secondary" style="width: 120px; height: 60px; object-fit: cover;">
                    </td>
                    <td>
                        <span class="fw-bold d-block text-white">{{ $ad->title }}</span>
                        <a href="{{ $ad->link_url }}" target="_blank" class="small text-info text-truncate d-inline-block" style="max-width: 250px;">{{ $ad->link_url }}</a>
                    </td>
                    <td>
                        <span class="badge bg-secondary text-uppercase">{{ $ad->position }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $ad->is_active ? 'success' : 'danger' }}">
                            {{ $ad->is_active ? 'Aktif Tayang' : 'Disembunyikan' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.ad.edit', $ad->id) }}" class="btn btn-outline-light btn-sm shadow-sm">Edit</a>
                            <form action="{{ route('admin.ad.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus iklan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-bold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">Belum ada iklan yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination-dark">
        {{ $ads->links() }}
    </div>
</div>
@endsection