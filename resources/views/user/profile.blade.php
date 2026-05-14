@extends('layouts.app')
@section('title', 'Profile Saya')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Profile Saya</h4>
    <p class="text-muted">Kelola informasi akun dan foto profile Anda.</p>
</div>

<div class="card-custom">
    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="text-center mb-4">
            <img id="photoPreview"
                 src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://placehold.co/120x120?text=User' }}"
                 class="rounded-circle border shadow-sm"
                 style="width:120px; height:120px; object-fit:cover;">

            <div class="mt-3">
                <input type="file"
                       name="profile_photo"
                       id="profilePhotoInput"
                       class="form-control @error('profile_photo') is-invalid @enderror"
                       accept="image/*">

                @error('profile_photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Nama</label>
            <input type="text"
                   name="name"
                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                   value="{{ old('name', Auth::user()->name) }}"
                   required>

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email"
                   name="email"
                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                   value="{{ old('email', Auth::user()->email) }}"
                   required>

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label fw-bold">Password Baru</label>
            <input type="password"
                   name="password"
                   class="form-control form-control-custom @error('password') is-invalid @enderror"
                   placeholder="Kosongkan jika tidak ingin mengubah password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control form-control-custom"
                   placeholder="Ulangi password baru">
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 py-3">
            <i class="bi bi-save me-2"></i> Simpan Perubahan
        </button>
    </form>
</div>

<script>
    const profileInput = document.getElementById('profilePhotoInput');
    const profilePreview = document.getElementById('photoPreview');

    profileInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            profilePreview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection