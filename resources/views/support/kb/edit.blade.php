@extends('layouts.app')
@section('title', 'Edit Artikel KB')

@section('content')
<div class="mb-4">
    <a href="{{ route('support.kb.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h4 class="fw-bold mb-1">Edit Artikel</h4>
</div>

<form action="{{ route('support.kb.update', $kb) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card-custom mb-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Artikel</label>
                    <input type="text" name="title" value="{{ old('title', $kb->title) }}" class="form-control form-control-custom @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Isi Artikel</label>
                    <textarea name="content" class="form-control form-control-custom @error('content') is-invalid @enderror" rows="12" required>{{ old('content', $kb->content) }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom mb-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="category" class="form-select form-control-custom @error('category') is-invalid @enderror" required>
                        <option value="Network" {{ old('category', $kb->category) == 'Network' ? 'selected' : '' }}>Network</option>
                        <option value="Software" {{ old('category', $kb->category) == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Hardware" {{ old('category', $kb->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                        <option value="Account & Access" {{ old('category', $kb->category) == 'Account & Access' ? 'selected' : '' }}>Account & Access</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select form-control-custom @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $kb->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $kb->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Thumbnail</label>
                    @if($kb->thumbnail)
                        <div class="mb-2">
                            <img src="{{ Storage::url($kb->thumbnail) }}" class="img-fluid rounded" style="max-height: 100px;">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                    @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary-custom w-100">Update Artikel</button>
            </div>
        </div>
    </div>
</form>
@endsection
