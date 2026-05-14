@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-2">Selamat Datang 👋</h3>
        <p class="text-muted">Masuk ke akun ITCARE Anda</p>
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

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Email Address</label>
            <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label fw-medium text-secondary" style="font-size: 0.9rem;">Password</label>
            <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary-custom mb-3">Masuk</button>
        
        <p class="text-center text-muted" style="font-size: 0.9rem;">
            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-blue);">Daftar di sini</a>
        </p>
    </form>
</div>
@endsection
