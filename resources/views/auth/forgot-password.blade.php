@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('content')
<div class="auth-card">

    <div class="text-center mb-4">
        <h3 class="fw-bold mb-2">Lupa Password?</h3>

        <p class="text-muted mb-0">
            Masukkan email dan password baru untuk akun ITCARE Anda.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('password.update.direct') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Email
            </label>

            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-envelope"></i>
                </span>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control form-control-custom"
                       placeholder="Masukkan email akun"
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Password Baru
            </label>

            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-lock"></i>
                </span>

                <input type="password"
                       name="password"
                       class="form-control form-control-custom"
                       placeholder="Masukkan password baru"
                       required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">
                Konfirmasi Password
            </label>

            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-shield-lock"></i>
                </span>

                <input type="password"
                       name="password_confirmation"
                       class="form-control form-control-custom"
                       placeholder="Ulangi password baru"
                       required>
            </div>
        </div>

        <button type="submit"
                class="btn btn-primary-custom w-100">
            <i class="bi bi-check-circle"></i>
            Update Password
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}"
           class="text-decoration-none fw-semibold">

            <i class="bi bi-arrow-left"></i>
            Kembali ke Login
        </a>
    </div>

</div>
@endsection