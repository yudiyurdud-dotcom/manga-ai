<!-- Kode ini diletakkan di resources/views/admin/user/index.blade.php -->
@extends('layout')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-white mb-0">Panel Admin - Kelola Pengguna</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-danger text-white border-0">{{ session('error') }}</div>
    @endif

    <!-- Search Bar -->
    <div class="card bg-dark border-secondary mb-4 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.user.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari nama atau email..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary fw-bold px-3" type="submit">Cari</button>
                        @if($search)
                            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-danger fw-bold px-3">X</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="table-responsive rounded shadow-sm border border-secondary" style="background-color: #1e1e1e;">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="table-active">
                <tr>
                    <th scope="col" style="width: 25%;">Pengguna</th>
                    <th scope="col" style="width: 25%;">Email</th>
                    <th scope="col" style="width: 15%;">Bergabung</th>
                    <th scope="col" class="text-center" style="width: 20%;">Role / Jabatan</th>
                    <th scope="col" class="text-center" style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="rounded-circle me-2 border border-secondary" style="width: 35px; height: 35px; object-fit: cover;">
                            <span class="fw-bold text-light">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-secondary">{{ $user->email }}</td>
                    <td class="text-secondary small">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <form action="{{ route('admin.user.updateRole', $user->id) }}" method="POST" class="d-flex justify-content-center m-0">
                            @csrf
                            @method('PUT')
                            <!-- Fitur "onchange" akan langsung menyimpan data ketika pilihan diklik -->
                            <select name="role" class="form-select form-select-sm bg-dark border-secondary me-2 {{ $user->role == 'admin' ? 'text-warning fw-bold' : 'text-white' }}" style="width: auto; cursor:pointer;" onchange="this.form.submit()" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User Biasa</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin Utama</option>
                            </select>
                        </form>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus akun {{ $user->name }} secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-bold" {{ $user->id === auth()->id() ? 'disabled' : '' }}>Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pengguna yang mendaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Pagination -->
    <div class="mt-4 pagination-dark">
        {{ $users->withQueryString()->links() }}
    </div>

</div>
@endsection