@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-2">Buat Akun 🚀</h3>
        <p class="text-muted">Bergabung dengan ITCARE</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control form-control-custom" placeholder="Budi Santoso" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Email Address</label>
            <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Password</label>
            <input type="password" name="password" class="form-control form-control-custom" placeholder="Min. 8 karakter" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary-custom mb-3">Daftar Akun</button>
        
        <p class="text-center text-muted" style="font-size: 0.9rem;">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-blue);">Masuk di sini</a>
        </p>
    </form>
</div>
@endsection
