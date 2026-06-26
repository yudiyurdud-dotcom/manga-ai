@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 mt-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold mb-4">Registrasi Akun</h3>
                
                <form method="POST" action="/register">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mb-3">Daftar</button>
                    <p class="text-center mb-0">Sudah punya akun? <a href="/login">Masuk di sini</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection