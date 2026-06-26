@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 mt-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold mb-4">Login</h3>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mb-3">Masuk</button>
                    <p class="text-center mb-0">Belum punya akun? <a href="/register">Daftar di sini</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection