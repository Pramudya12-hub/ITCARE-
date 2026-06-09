@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
{{-- Halaman ini menampilkan dashboard utama user setelah login --}}
<div class="hero-banner mb-4">
    <h2 class="fw-bold mb-2">HALO, {{ strtoupper(explode(' ', auth()->user()->name)[0]) }} 👋</h2>
    <p class="mb-0 text-light opacity-75">Ada kendala IT? Ajukan tiket atau cari solusi instan dari knowledge base.</p>
    <div class="mt-4 d-flex gap-3">
        <a href="{{ route('user.tickets.create') }}" class="btn btn-primary" style="border-radius: 50px; padding: 8px 24px;">Ajukan Keluhan</a>
        <a href="{{ route('user.kb.index') }}" class="btn btn-light" style="border-radius: 50px; padding: 8px 24px; color: var(--primary-navy);"><i class="bi bi-book"></i> Lihat Panduan IT</a>
    </div>
</div>

{{-- Kartu statistik: menampilkan jumlah tiket berdasarkan status --}}
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card-custom text-center">
            <div class="d-flex align-items-center justify-content-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fef08a; color: #854d0e;">
                    <i class="bi bi-exclamation-circle fs-4"></i>
                </div>
                <div class="text-start">
                    <h3 class="fw-bold mb-0">{{ $stats['open'] }}</h3>
                    <div class="text-muted small">Tiket Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center">
            <div class="d-flex align-items-center justify-content-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #e0e7ff; color: #3730a3;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div class="text-start">
                    <h3 class="fw-bold mb-0">{{ $stats['in_progress'] }}</h3>
                    <div class="text-muted small">Sedang Ditangani</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center">
            <div class="d-flex align-items-center justify-content-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #dcfce7; color: #166534;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div class="text-start">
                    <h3 class="fw-bold mb-0">{{ $stats['closed'] }}</h3>
                    <div class="text-muted small">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Keluhan Terbaru Saya</h5>
    <a href="{{ route('user.tickets.index') }}" class="text-decoration-none fw-medium" style="color: var(--text-main);">Lihat semua &rarr;</a>
</div>

{{-- Daftar 3 tiket terbaru milik user --}}
<div class="mb-5">
    @forelse($recentTickets as $ticket)
        {{-- Tentukan ikon berdasarkan kategori tiket --}}
        @php
            $catIcons = [
                'Network' => 'bi-wifi',
                'Software' => 'bi-window-stack',
                'Hardware' => 'bi-pc-display',
                'Account & Access' => 'bi-key'
            ];
            $icon = $catIcons[$ticket->category] ?? 'bi-ticket';
        @endphp
        <a href="{{ route('user.tickets.show', $ticket) }}" class="text-decoration-none">
            <div class="card-custom d-flex align-items-center gap-3 mb-3 hover-shadow" style="padding: 1.25rem; transition: 0.2s;">
                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi {{ $icon }} fs-2 text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted small">ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="badge badge-status 
                            {{ $ticket->status == 'Open' ? 'status-open' : ($ticket->status == 'In Progress' ? 'status-progress' : 'status-closed') }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                    <h6 class="fw-bold mb-1 text-dark">{{ $ticket->title }}</h6>
                    <p class="text-muted small mb-0 text-truncate" style="max-width: 600px;">{{ $ticket->description }}</p>
                </div>
                <div class="text-muted small">
                    {{ $ticket->created_at->format('H.i') }}
                </div>
            </div>
        </a>
    @empty
        <div class="text-center py-5 text-muted card-custom">
            <i class="bi bi-emoji-smile fs-1 d-block mb-3 text-secondary opacity-50"></i>
            <h6 class="fw-bold text-dark">Belum ada keluhan</h6>
            <p class="small mb-0">Anda belum pernah mengajukan tiket apapun.</p>
        </div>
    @endforelse
</div>

{{-- Bagian panduan populer: menampilkan 3 artikel knowledge base terbaru --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Panduan Populer</h5>
    <a href="{{ route('user.kb.index') }}" class="text-decoration-none fw-medium" style="color: var(--text-main);">Lihat semua &rarr;</a>
</div>

<div class="row g-4">
    @forelse($popularKb as $kb)
        <div class="col-md-12">
            <a href="{{ route('user.kb.show', $kb) }}" class="text-decoration-none">
                <div class="card-custom d-flex gap-3 align-items-center" style="padding: 1.25rem;">
                    <div>
                        <div class="text-muted small fw-bold mb-1"><i class="bi bi-tag text-info"></i> {{ strtoupper($kb->category) }}</div>
                        <h6 class="fw-bold text-dark mb-1">{{ $kb->title }}</h6>
                        <p class="text-muted small mb-0">{{ Str::limit(strip_tags($kb->content), 80) }}</p>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted card-custom">
                <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">Belum ada artikel panduan</h6>
                <p class="small mb-0">Tim IT belum mempublikasikan panduan apapun.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
