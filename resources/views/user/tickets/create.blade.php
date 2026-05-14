@extends('layouts.app')
@section('title', 'Ajukan Keluhan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Ajukan Keluhan IT</h4>
    <p class="text-muted">Jelaskan kendala Anda agar dapat ditangani oleh tim Support.</p>
</div>

<form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card-custom mb-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Judul singkat</label>
            <input type="text"
                   name="title"
                   class="form-control form-control-custom @error('title') is-invalid @enderror"
                   placeholder="Contoh: Laptop tidak bisa connect WIFI"
                   value="{{ old('title') }}"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Gambar (Opsional)</label>

            <label for="imageInput"
                   class="border rounded bg-light d-flex align-items-center justify-content-center position-relative overflow-hidden @error('image') border-danger @enderror"
                   style="height: 220px; border-style: dashed !important; cursor: pointer;">

                <input type="file"
                       name="image"
                       id="imageInput"
                       class="d-none"
                       accept="image/*">

                <img id="imagePreview"
                     src=""
                     alt="Preview Gambar"
                     class="w-100 h-100 d-none"
                     style="object-fit: cover;">

                <div id="uploadPlaceholder" class="text-center text-muted">
                    <i class="bi bi-camera fs-1"></i>
                    <div class="small">Klik untuk upload foto error (Maks 2MB)</div>
                    <div class="small text-muted mt-1">Format: JPG, JPEG, PNG</div>
                </div>
            </label>

            @error('image')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="card-custom mb-4">
        <div class="mb-4">
            <label class="form-label fw-bold">Deskripsi masalah</label>
            <textarea name="description"
                      rows="4"
                      class="form-control form-control-custom @error('description') is-invalid @enderror"
                      placeholder="Jelaskan apa yang terjadi, kapan, dan langkah yang sudah dicoba.."
                      required>{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Kategori</label>
            <div class="row g-3">
                @php
                    $categories = [
                        ['name' => 'Network', 'desc' => 'WIFI, VPN, internet', 'icon' => 'bi-wifi'],
                        ['name' => 'Software', 'desc' => 'Aplikasi, OS, update', 'icon' => 'bi-window-stack'],
                        ['name' => 'Hardware', 'desc' => 'Laptop, printer, monitor', 'icon' => 'bi-pc-display'],
                        ['name' => 'Account & Access', 'desc' => 'Email, password, folder', 'icon' => 'bi-key']
                    ];
                @endphp

                @foreach($categories as $cat)
                    <div class="col-md-6">
                        <label class="w-100">
                            <input type="radio"
                                   name="category"
                                   value="{{ $cat['name'] }}"
                                   class="btn-check"
                                   {{ old('category') == $cat['name'] ? 'checked' : '' }}
                                   required>

                            <div class="btn btn-outline-secondary w-100 text-start p-3 rounded @error('category') border-danger @enderror"
                                 style="border-radius: 12px !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi {{ $cat['icon'] }} fs-5"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold text-dark">{{ $cat['name'] }}</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">
                                            {{ $cat['desc'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            @error('category')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Prioritas</label>
            <div class="d-flex gap-2">
                <input type="radio"
                       class="btn-check"
                       name="priority"
                       id="p_low"
                       value="Low"
                       {{ old('priority') == 'Low' ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-secondary flex-grow-1 @error('priority') border-danger @enderror"
                       for="p_low"
                       style="border-radius: 10px;">
                    Low
                </label>

                <input type="radio"
                       class="btn-check"
                       name="priority"
                       id="p_medium"
                       value="Medium"
                       {{ old('priority') == 'Medium' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary flex-grow-1 @error('priority') border-danger @enderror"
                       for="p_medium"
                       style="border-radius: 10px;">
                    Medium
                </label>

                <input type="radio"
                       class="btn-check"
                       name="priority"
                       id="p_high"
                       value="High"
                       {{ old('priority') == 'High' ? 'checked' : '' }}>
                <label class="btn btn-outline-danger flex-grow-1 @error('priority') border-danger @enderror"
                       for="p_high"
                       style="border-radius: 10px;">
                    High
                </label>
            </div>

            @error('priority')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-6">
            <i class="bi bi-send me-2"></i> Submit Pengajuan
        </button>
    </div>
</form>

<script>
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('d-none');
            uploadPlaceholder.classList.add('d-none');
        } else {
            imagePreview.src = '';
            imagePreview.classList.add('d-none');
            uploadPlaceholder.classList.remove('d-none');
        }
    });
</script>
@endsection