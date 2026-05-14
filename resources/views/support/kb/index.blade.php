@extends('layouts.app')
@section('title', 'Knowledge Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Knowledge Base</h4>
        <p class="text-muted mb-0">Kelola artikel panduan IT untuk pengguna.</p>
    </div>
    <a href="{{ route('support.kb.create') }}" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Buat Artikel</a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="text-muted">
                <tr>
                    <th class="border-0 pb-3">THUMBNAIL</th>
                    <th class="border-0 pb-3">JUDUL ARTIKEL</th>
                    <th class="border-0 pb-3">KATEGORI</th>
                    <th class="border-0 pb-3">STATUS</th>
                    <th class="border-0 pb-3 text-end">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $kb)
                <tr>
                    <td>
                        @if($kb->thumbnail)
                            <img src="{{ Storage::url($kb->thumbnail) }}" class="rounded object-fit-cover" width="80" height="60">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 60px;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $kb->title }}</div>
                        <div class="text-muted small">Oleh: {{ $kb->creator->name }} &bull; {{ $kb->created_at->format('d M Y') }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $kb->category }}</span></td>
                    <td>
                        @if($kb->status == 'published')
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Published</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Draft</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('support.kb.edit', $kb) }}" class="btn btn-light btn-sm rounded-pill px-3"><i class="bi bi-pencil"></i> Edit</a>
                        <form action="{{ route('support.kb.destroy', $kb) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Hapus artikel ini?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        <h6 class="fw-bold text-dark">Belum ada artikel panduan</h6>
                        <p class="small mb-0">Anda dapat membuat artikel baru untuk membantu pengguna.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $articles->links('pagination::bootstrap-5') }}
</div>
@endsection
