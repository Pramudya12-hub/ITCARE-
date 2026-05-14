@extends('layouts.app')
@section('title', $article->title)

@section('content')
<div class="mx-auto" style="max-width: 800px;">
    <a href="{{ route('user.kb.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali ke Pusat Bantuan</a>

    <div class="card-custom">
        <div class="badge bg-light text-dark mb-3 rounded-pill border"><i class="bi bi-tag"></i> {{ $article->category }}</div>
        
        <h2 class="fw-bold mb-3">{{ $article->title }}</h2>
        
        <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-4 border-bottom">
            <div><i class="bi bi-person"></i> {{ $article->creator->name }}</div>
            <div><i class="bi bi-calendar3"></i> {{ $article->created_at->format('d M Y') }}</div>
        </div>

        @if($article->thumbnail)
            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-100 rounded mb-4 object-fit-cover" style="max-height: 400px;">
        @endif

        <div class="article-content" style="line-height: 1.8;">
            {!! nl2br(e($article->content)) !!}
        </div>
        
        <div class="mt-5 pt-4 border-top text-center">
            <p class="fw-bold mb-3">Apakah panduan ini membantu?</p>
            <button class="btn btn-outline-success rounded-pill px-4 me-2"><i class="bi bi-hand-thumbs-up"></i> Ya</button>
            <button class="btn btn-outline-danger rounded-pill px-4"><i class="bi bi-hand-thumbs-down"></i> Tidak</button>
        </div>
    </div>
</div>
@endsection
