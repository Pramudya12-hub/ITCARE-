@extends('layouts.app')
@section('title', 'Panduan IT')

@section('content')
{{-- Halaman daftar artikel knowledge base dengan fitur pencarian dan filter kategori --}}
<div class="text-center mb-5 mt-3">
    <h2 class="fw-bold mb-3">Bagaimana kami bisa membantu?</h2>
    <div class="mx-auto" style="max-width: 600px;">
        <form action="{{ route('user.kb.index') }}" method="GET" class="search-bar w-100 mb-3" style="box-shadow: var(--shadow-soft); border-radius: 50px;">
            <i class="bi bi-search fs-5" style="left: 20px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari masalah, error, atau panduan instalasi..." style="padding: 16px 20px 16px 50px; font-size: 1rem;">
        </form>
    </div>
    
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <a href="{{ route('user.kb.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-light text-muted border' }} rounded-pill px-4">Semua</a>
        <a href="{{ route('user.kb.index', ['category' => 'Network', 'search' => request('search')]) }}" class="btn {{ request('category') == 'Network' ? 'btn-primary' : 'btn-light text-muted border' }} rounded-pill px-4">Network</a>
        <a href="{{ route('user.kb.index', ['category' => 'Software', 'search' => request('search')]) }}" class="btn {{ request('category') == 'Software' ? 'btn-primary' : 'btn-light text-muted border' }} rounded-pill px-4">Software</a>
        <a href="{{ route('user.kb.index', ['category' => 'Hardware', 'search' => request('search')]) }}" class="btn {{ request('category') == 'Hardware' ? 'btn-primary' : 'btn-light text-muted border' }} rounded-pill px-4">Hardware</a>
        <a href="{{ route('user.kb.index', ['category' => 'Account & Access', 'search' => request('search')]) }}" class="btn {{ request('category') == 'Account & Access' ? 'btn-primary' : 'btn-light text-muted border' }} rounded-pill px-4">Account</a>
    </div>
</div>

{{-- Daftar artikel yang tampil sesuai filter/pencarian --}}
<div class="row g-4">
    @forelse($articles as $kb)
    <div class="col-md-4">
        <a href="{{ route('user.kb.show', $kb) }}" class="text-decoration-none">
            <div class="card-custom h-100 hover-shadow transition-all" style="padding: 0; overflow: hidden;">
                @if($kb->thumbnail)
                    <img src="{{ asset('storage/' . $kb->thumbnail) }}" class="w-100 object-fit-cover" style="height: 160px;">
                @else
                    <div class="w-100 bg-light d-flex align-items-center justify-content-center text-muted" style="height: 160px;">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                @endif
                <div class="p-4">
                    <div class="badge bg-light text-dark mb-2 rounded-pill border"><i class="bi bi-tag"></i> {{ $kb->category }}</div>
                    <h5 class="fw-bold text-dark mb-2">{{ $kb->title }}</h5>
                    <p class="text-muted small mb-0">{{ Str::limit(strip_tags($kb->content), 100) }}</p>
                </div>
            </div>
        </a>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-5">
        <i class="bi bi-search fs-1 mb-3 d-block"></i>
        Tidak ada artikel ditemukan.
    </div>
    @endforelse
</div>

{{-- Navigasi halaman (pagination) --}}
<div class="mt-5 d-flex justify-content-center">
    {{ $articles->links('pagination::bootstrap-5') }}
</div>
@endsection
