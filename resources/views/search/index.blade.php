@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Hasil Pencarian</h4>
    <p class="text-muted mb-0">
        Menampilkan hasil untuk: <strong>{{ $query }}</strong>
    </p>
</div>

<div class="card-custom mb-4">
    <h5 class="fw-bold mb-3">Pengajuan / Ticket</h5>

    @forelse($tickets as $ticket)
        <a href="{{ auth()->user()->role === 'it_support' 
            ? route('support.tickets.show', $ticket) 
            : route('user.tickets.show', $ticket) }}"
           class="text-decoration-none text-dark">

            <div class="border-bottom py-3">
                <div class="fw-bold">
                    ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }} - {{ $ticket->title }}
                </div>
                <div class="small text-muted">
                    {{ $ticket->category }} • {{ $ticket->priority }} • {{ $ticket->status }}
                </div>
            </div>
        </a>
    @empty
        <p class="text-muted mb-0">Tidak ada ticket yang ditemukan.</p>
    @endforelse
</div>

<div class="card-custom">
    <h5 class="fw-bold mb-3">Knowledge Base</h5>

    @forelse($articles as $article)
        <a href="{{ auth()->user()->role === 'it_support' 
            ? route('support.kb.edit', $article) 
            : route('user.kb.show', $article) }}"
           class="text-decoration-none text-dark">

            <div class="border-bottom py-3">
                <div class="fw-bold">{{ $article->title }}</div>
                <div class="small text-muted">
                    {{ $article->category }}
                </div>
            </div>
        </a>
    @empty
        <p class="text-muted mb-0">Tidak ada artikel yang ditemukan.</p>
    @endforelse
</div>
@endsection