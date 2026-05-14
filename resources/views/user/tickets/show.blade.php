@extends('layouts.app')
@section('title', 'Detail Tiket')

@section('content')
@php
    $statusLabel = $ticket->status == 'Open'
        ? 'Baru Masuk'
        : ($ticket->status == 'In Progress' ? 'Sedang Ditangani' : 'Selesai Ditangani');

    $formatDuration = function ($start, $end) {
        if (!$start || !$end) return null;

        $minutes = abs(\Carbon\Carbon::parse($start)->diffInMinutes(\Carbon\Carbon::parse($end)));

        if ($minutes < 1) return 'Kurang dari 1 menit';

        if ($minutes < 60) {
            return $minutes . ' menit';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours < 24) {
            return $hours . ' jam ' . $remainingMinutes . ' menit';
        }

        $days = floor($hours / 24);
        $remainingHours = $hours % 24;

        return $days . ' hari ' . $remainingHours . ' jam';
    };
@endphp

<a href="{{ route('user.tickets.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="row">
    <div class="col-md-8">
        <div class="card-custom mb-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-wifi fs-3 text-primary"></i>
                </div>

                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted fw-bold">
                            ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                        <span class="badge badge-status {{ $ticket->status == 'Open' ? 'status-open' : ($ticket->status == 'In Progress' ? 'status-progress' : 'status-closed') }}">
                            {{ $statusLabel }}
                        </span>

                        <span class="badge badge-priority {{ $ticket->priority == 'High' ? 'priority-high' : ($ticket->priority == 'Medium' ? 'priority-medium' : 'priority-low') }}">
                            {{ $ticket->priority }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-0">{{ $ticket->title }}</h4>
                </div>
            </div>

            <p class="text-dark">{{ $ticket->description }}</p>

            @if($ticket->image)
                <div class="mt-3 mb-3">
                    <img src="{{ asset('storage/' . $ticket->image) }}"
                         class="img-fluid rounded border"
                         alt="Screenshot Error"
                         style="max-height: 300px;"
                         onerror="this.src='https://placehold.co/600x300?text=Image+Not+Found'">
                </div>
            @endif

            <hr class="text-muted opacity-25">

            <div class="d-flex flex-wrap gap-4 small text-muted">
                <div>
                    Pelapor:
                    <span class="fw-bold text-dark">{{ $ticket->user->name }}</span>
                </div>

                <div>
                    Kategori:
                    <span class="fw-bold text-dark">{{ $ticket->category }}</span>
                </div>

                <div>
                    Dibuat:
                    <span class="fw-bold text-dark">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                </div>

                <div>
                    Ditangani:
                    <span class="fw-bold text-dark">
                        {{ $ticket->assignedSupport?->name ?? 'Belum ditugaskan' }}
                    </span>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Riwayat & Diskusi</h5>

        <div class="chat-timeline">
            @if($ticket->ai_recommendation)
                <div class="mb-4 position-relative">
                    <div class="chat-avatar bg-purple text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; background: var(--accent-purple);">
                        <i class="bi bi-robot"></i>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold">
                            AI Assistant
                            <span class="badge bg-purple rounded-pill" style="background: var(--accent-purple);">
                                AI
                            </span>
                        </span>

                        <span class="text-muted small">{{ $ticket->created_at->format('H:i') }}</span>
                    </div>

                    <div class="chat-bubble ai">
                        {{ $ticket->ai_recommendation }}
                    </div>
                </div>
            @endif

            @forelse($ticket->comments as $comment)
                @php
                    $avatarColor = $comment->user->role == 'it_support'
                        ? 'var(--primary-blue)'
                        : '#94a3b8';
                @endphp

                <div class="mb-4 position-relative">
                    <div class="chat-avatar rounded-circle d-flex align-items-center justify-content-center text-white"
                         style="width: 32px; height: 32px; background-color: {{ $avatarColor }};">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold">
                            {{ $comment->user->name }}

                            @if($comment->user->role == 'it_support')
                                <span class="badge bg-primary rounded-pill">Support</span>
                            @endif
                        </span>

                        <span class="text-muted small">{{ $comment->created_at->format('H:i') }}</span>
                    </div>

                    <div class="chat-bubble border">
                        {{ $comment->comment }}
                    </div>
                </div>
            @empty
                <div class="text-muted small mb-4">
                    Belum ada diskusi pada tiket ini.
                </div>
            @endforelse

            @if($ticket->status != 'Closed')
                <div class="mt-4">
                    <form action="{{ route('user.tickets.comment', $ticket) }}" method="POST">
                        @csrf

                        <textarea name="comment"
                                  class="form-control form-control-custom mb-3"
                                  rows="3"
                                  placeholder="Tulis pesan.."
                                  required>{{ old('comment') }}</textarea>

                        @error('comment')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-paperclip"></i> Lampirkan
                            </button>

                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-send"></i> Kirim
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="alert alert-success rounded-4 mt-4 mb-0">
                    Tiket ini sudah selesai ditangani. Diskusi telah ditutup.
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="ai-card p-4 mb-4">
            <h6 class="fw-bold d-flex align-items-center gap-2" style="color: var(--accent-purple);">
                <i class="bi bi-stars"></i> Rekomendasi AI
            </h6>

            @if($ticket->ai_recommendation)
                <div class="p-3 small text-dark" style="background: rgba(255,255,255,0.8); border-radius: 12px; border: 1px solid #d8b4fe; white-space: pre-wrap;">{{ $ticket->ai_recommendation }}</div>
            @else
                <div class="p-3 text-center small"
                     style="background: rgba(255,255,255,0.5); border-radius: 12px; border: 1px dashed #d8b4fe; color: var(--accent-purple);">
                    Belum ada rekomendasi AI untuk tiket ini.
                </div>
            @endif
        </div>

        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-person-check me-1"></i> Ditangani Oleh
            </h6>

            @if($ticket->assignedSupport)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width: 45px; height: 45px;">
                        {{ strtoupper(substr($ticket->assignedSupport->name, 0, 1)) }}
                    </div>

                    <div>
                        <div class="fw-bold">{{ $ticket->assignedSupport->name }}</div>
                        <div class="small text-muted">{{ $ticket->assignedSupport->email }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        Sedang Ditangani
                    </span>
                </div>
            @else
                <div class="text-muted small">
                    Tiket Anda belum ditugaskan ke IT Support tertentu.
                </div>
            @endif
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3">SLA Tracking</h6>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="text-warning fs-4">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <div class="text-muted small">First Response</div>

                    <div class="fw-bold">
                        @if($ticket->first_response_at)
                            {{ $formatDuration($ticket->created_at, $ticket->first_response_at) }}
                        @else
                            Menunggu respon IT Support
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-success fs-4">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="text-muted small">Resolution Time</div>

                    <div class="fw-bold">
                        @if($ticket->resolved_at)
                            {{ $formatDuration($ticket->created_at, $ticket->resolved_at) }}
                        @else
                            Belum selesai
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection