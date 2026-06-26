<!-- Kode ini diletakkan di resources/views/profile/index.blade.php -->
@extends('layout')

@section('content')
<div class="row mt-4 mb-5 justify-content-center">
    <div class="col-md-10">

        <!-- 1. BANNER PROFIL ATAS -->
        <div class="card border-0 mb-4 shadow-sm" style="background-color: #16181f; border-radius: 12px;">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center">
                
                <!-- Kiri: Foto Profil -->
                <div class="position-relative me-md-4 mb-3 mb-md-0">
                    <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/120?text=Foto' }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #252836;" alt="Foto Profil">
                </div>
                
                <!-- Tengah: Info User -->
                <div class="flex-grow-1 text-center text-md-start">
                    <h3 class="fw-bold mb-1 text-white d-flex align-items-center justify-content-center justify-content-md-start">
                        {{ $user->name }} 
                        @if($user->role === 'admin')
                            <span class="badge bg-warning text-dark ms-2 fs-6 rounded-pill">⭐ Admin</span>
                        @endif
                    </h3>
                    <div class="text-muted small mb-2">
                        <span class="me-3"><i class="bi bi-envelope"></i> {{ $user->email }}</span>
                        <span><i class="bi bi-calendar"></i> Bergabung {{ $user->created_at ? $user->created_at->format('F Y') : 'Baru saja' }}</span>
                    </div>
                    <div id="uploadStatus" class="small fw-bold"></div>
                </div>

                <!-- Kanan: Tombol Edit/Upload Foto -->
                <div class="mt-3 mt-md-0">
                    <label for="avatarInput" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="cursor: pointer;">
                        Edit Profile
                    </label>
                    <input type="file" id="avatarInput" class="d-none" accept="image/*">
                </div>
            </div>
        </div>

        <!-- 2. KONTEN PENGATURAN BAWAH DENGAN TAB AKTIF -->
        <div class="card border-0 shadow-sm" style="background-color: #16181f; border-radius: 12px;">
            
            <!-- Header Tab Navigasi -->
            <div class="card-header border-bottom border-secondary p-0" style="background-color: #1e212b; border-radius: 12px 12px 0 0;">
                <ul class="nav nav-pills nav-fill" id="profilePills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-0 fw-bold w-100" id="setting-tab" data-bs-toggle="pill" data-bs-target="#setting" type="button" role="tab" aria-selected="true" style="border-radius: 12px 0 0 0 !important;">Setting</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-0 fw-bold w-100 text-muted" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab" aria-selected="false" style="border-radius: 0 12px 0 0 !important;" onclick="this.classList.remove('text-muted'); document.getElementById('setting-tab').classList.add('text-muted');">Security</button>
                    </li>
                </ul>
            </div>

            <!-- Isi Konten Tab -->
            <div class="card-body p-4 p-md-5">
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="tab-content" id="profilePillsContent">
                    
                    <!-- TAB 1: SETTING (UBAH NAMA) -->
                    <div class="tab-pane fade show active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                        <h5 class="fw-bold text-white mb-1">Pengaturan Akun Dasar</h5>
                        <p class="text-muted small mb-4">Ubah nama tampilan yang akan dilihat oleh pengguna lain.</p>
                        
                        <form action="{{ route('profile.update.info') }}" method="POST">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <label class="form-label text-muted small fw-bold">Nama Tampilan</label>
                                    <input type="text" name="name" class="form-control text-white border-secondary" style="background-color: #252836;" value="{{ $user->name }}" required>
                                </div>
                                <!-- Tambahkan di bawah input Nama Tampilan pada resources/views/profile/index.blade.php -->
<div class="mb-4 mt-4">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="privacySwitch" name="is_private" value="1" {{ $user->is_private ? 'checked' : '' }}>
        <label class="form-check-label text-white fw-bold ms-2" for="privacySwitch">Akun Privat</label>
    </div>
    <small class="text-muted">Jika diaktifkan, pengguna lain tidak bisa melihat daftar Library dan Riwayat Anda.</small>
</div>
                                <div class="col-md-4 mt-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan Nama</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: SECURITY (UBAH PASSWORD) -->
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <h5 class="fw-bold text-white mb-1">Keamanan Akun</h5>
                        <p class="text-muted small mb-4">Pastikan akunmu menggunakan password yang panjang dan kuat.</p>

                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted small fw-bold">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control text-white border-secondary" style="background-color: #252836;" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold">Password Baru</label>
                                    <input type="password" name="password" class="form-control text-white border-secondary" style="background-color: #252836;" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted small fw-bold">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control text-white border-secondary" style="background-color: #252836;" required>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-danger px-4 rounded-pill">Ubah Password</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT UPLOAD AJAX -->
<script>
    // Script agar teks warna tab ikut berubah saat diklik
    document.getElementById('setting-tab').addEventListener('click', function() {
        this.classList.remove('text-muted');
        document.getElementById('security-tab').classList.add('text-muted');
    });

    // Script Upload Foto
    document.getElementById('avatarInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        if (file.size > 5242880) {
            alert('Ukuran gambar terlalu besar! Maksimal 5MB.');
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');

        const statusLabel = document.getElementById('uploadStatus');
        statusLabel.innerHTML = '<span class="text-warning">⏳ Sedang mengunggah...</span>';

        fetch('{{ route('profile.upload.avatar') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avatarPreview').src = data.avatar_url;
                statusLabel.innerHTML = '<span class="text-success mt-1 d-inline-block">✅ Foto berhasil diubah!</span>';
                setTimeout(() => statusLabel.innerHTML = '', 3000);
            } else {
                statusLabel.innerHTML = '<span class="text-danger mt-1 d-inline-block">❌ Gagal mengunggah foto.</span>';
            }
        })
        .catch(error => {
            statusLabel.innerHTML = '<span class="text-danger mt-1 d-inline-block">❌ Terjadi kesalahan sistem.</span>';
        });
    });
</script>
@endsection