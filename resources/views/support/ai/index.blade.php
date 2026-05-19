@extends('layouts.app')
@section('title', 'AI Assistant')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
        <i class="bi bi-stars" style="color: var(--accent-purple);"></i>
        AI Assistant
    </h3>
    <p class="text-muted">
        AI membantu menganalisa, memberikan rekomendasi, dan mengoptimalkan penanganan.
    </p>
</div>

<div class="row g-4">
    <div class="col-md-8">

        <div class="card-custom mb-4" style="background: linear-gradient(to bottom right, #f8fafc, #f3e8ff);">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">AI Insight</h6>
                <span class="badge rounded-pill text-bg-light">Data real-time</span>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="bg-white p-3 rounded shadow-sm border h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold small">
                            <i class="bi bi-repeat"></i> Recurring Issues
                        </div>
                        <h3 class="fw-bold mb-1">
                            {{ $recurringIssues?->total ?? 0 }}
                        </h3>
                        <p class="small text-muted mb-0" style="font-size:0.7rem;">
                            Kategori paling sering:
                            <b>{{ $recurringIssues?->category ?? 'Belum ada data' }}</b>
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="bg-white p-3 rounded shadow-sm border h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold small">
                            <i class="bi bi-journal-x"></i> Knowledge Gap
                        </div>
                        <h3 class="fw-bold mb-1">{{ $knowledgeGap }}</h3>
                        <p class="small text-muted mb-0" style="font-size:0.7rem;">
                            Ticket dengan kategori yang belum punya artikel KB.
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="bg-white p-3 rounded shadow-sm border h-100 border-warning border-opacity-50">
                        <div class="d-flex align-items-center gap-2 mb-2 text-warning fw-bold small">
                            <i class="bi bi-exclamation-triangle"></i> SLA Risk
                        </div>
                        <h3 class="fw-bold mb-1 text-warning">{{ $slaRisk }}</h3>
                        <p class="small text-muted mb-0" style="font-size:0.7rem;">
                            Ticket open/progress lebih dari 4 jam.
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="bg-white p-3 rounded shadow-sm border h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-success fw-bold small">
                            <i class="bi bi-robot"></i> AI Resolution
                        </div>
                        <h3 class="fw-bold mb-1 text-success">{{ $aiResolutionRate }}%</h3>
                        <p class="small text-muted mb-0" style="font-size:0.7rem;">
                            Estimasi ticket terbantu rekomendasi AI.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('ai_summary'))
        <div class="card-custom mb-4" style="background: linear-gradient(to bottom right, #ffffff, #f3e8ff);">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-stars fs-5" style="color: var(--accent-purple);"></i>
                <h6 class="fw-bold mb-0">Hasil Analisis AI</h6>
            </div>

            <div style="white-space: pre-line; line-height: 1.7;">
                {{ session('ai_summary') }}
            </div>
        </div>
        @endif

        <div class="card-custom mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">AI Rekomendasi</h6>
                <span class="small text-muted">Berdasarkan data pengaduan terbaru</span>
            </div>

            <div class="list-group list-group-flush border-top pt-2">
                @forelse($latestTickets as $ticket)
                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between border-0 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded bg-light text-primary d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-ticket-detailed"></i>
                        </div>

                        <div>
                            <div class="fw-bold">{{ $ticket->title }}</div>
                            <div class="small text-muted">
                                {{ $ticket->category }} • {{ $ticket->priority }} • {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-1">
                            Analisis
                        </span>
                        <div class="small text-muted" style="font-size:0.7rem;">
                            ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    Belum ada ticket untuk dianalisis.
                </div>
                @endforelse
            </div>
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3">Aktivitas AI Terbaru</h6>

            <div class="chat-timeline">
                @forelse($latestTickets as $ticket)
                <div class="mb-4 position-relative">
                    <div class="chat-avatar rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 32px; height: 32px; background-color: var(--accent-purple);">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <div class="fw-bold mb-1">
                        Siap menganalisis ticket ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="text-muted small">
                        {{ $ticket->title }} • {{ $ticket->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-muted small">
                    Belum ada aktivitas AI.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-custom mb-4" style="background: var(--bg-color);">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-lightning-charge text-warning"></i>
                Quick Actions
            </h6>

            <div class="d-grid gap-2">

                <form action="{{ route('support.ai.summarize') }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="btn btn-white border rounded p-3 text-start small bg-white text-dark w-100">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-stars text-primary me-1"></i>
                            Rangkum 5 Pengaduan Terbaru
                        </div>
                        <div class="text-muted small">
                            AI menganalisis pola masalah dari pengaduan terbaru.
                        </div>
                    </button>
                </form>

                <a href="{{ route('support.tickets.index') }}"
                    class="btn btn-white border rounded p-3 text-start small bg-white text-dark w-100 text-decoration-none">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-ticket-detailed text-danger me-1"></i>
                        Lihat Semua Pengaduan 
                    </div>
                    <div class="text-muted small">
                        Kelola pengaduan masuk, assign support, dan update status.
                    </div>
                </a>

                <a href="{{ route('support.kb.create') }}"
                    class="btn btn-white border rounded p-3 text-start small bg-white text-dark w-100 text-decoration-none">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-journal-plus text-success me-1"></i>
                        Tambah Artikel KB
                    </div>
                    <div class="text-muted small">
                        Buat artikel solusi baru untuk knowledge base.
                    </div>
                </a>

                <a href="{{ route('support.tickets.export') }}"
                    class="btn btn-white border rounded p-3 text-start small bg-white text-dark w-100 text-decoration-none">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-download text-warning me-1"></i>
                        Export Laporan Pengaduan
                    </div>
                    <div class="text-muted small">
                        Unduh data pengaduan dalam format CSV.
                    </div>
                </a>

            </div>

            <div class="card-custom">
                <h6 class="fw-bold mb-3">Kapasitas AI</h6>

                <ul class="list-unstyled small text-muted d-flex flex-column gap-3 mb-0">
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up fs-5 text-primary" style="width: 20px;"></i>
                        Analisis pola pengaduan terbaru
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-card-text fs-5 text-primary" style="width: 20px;"></i>
                        Rangkum masalah otomatis
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightbulb fs-5 text-primary" style="width: 20px;"></i>
                        Rekomendasi tindakan IT Support
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-book fs-5 text-primary" style="width: 20px;"></i>
                        Persiapan integrasi Knowledge Base
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endsection