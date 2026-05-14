@extends('layouts.app')
@section('title', 'Proses Tiket')

@section('content')
<a href="{{ route('support.tickets.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali</a>

<div class="row">
    <div class="col-md-8">
        <div class="card-custom mb-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-display fs-3 text-primary"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted fw-bold">ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="badge badge-status {{ $ticket->status == 'Open' ? 'Baru Masuk' : ($ticket->status == 'In Progress' ? 'Sedang Ditangani' : 'Selesai Ditangani') }}">{{ $ticket->status }}</span>
                        <span class="badge badge-priority {{ $ticket->priority == 'High' ? 'priority-high' : ($ticket->priority == 'Medium' ? 'priority-medium' : 'priority-low') }}">{{ $ticket->priority }}</span>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $ticket->title }}</h4>
                </div>
            </div>

            <p class="text-dark">{{ $ticket->description }}</p>

            @if($ticket->image)
            <div class="mt-3 mb-3">
                <img src="{{ asset('storage/' . $ticket->image) }}" class="img-fluid rounded border" alt="Screenshot Error" style="max-height: 300px;">
            </div>
            @endif

            <hr class="text-muted opacity-25">
            <div class="d-flex gap-4 small text-muted">
                <div>Pelapor: <span class="fw-bold text-dark">{{ $ticket->user->name }}</span></div>
                <div>Kategori: <span class="fw-bold text-dark">{{ $ticket->category }}</span></div>
                <div>Dibuat: <span class="fw-bold text-dark">{{ $ticket->created_at->format('Y-m-d H:i') }}</span></div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Diskusi Penanganan</h5>
        <div class="chat-timeline">

            @if($ticket->ai_recommendation)
            <div class="mb-4 position-relative">
                <div class="chat-avatar bg-purple text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--accent-purple);"><i class="bi bi-robot"></i></div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-bold">AI Assistant <span class="badge bg-purple rounded-pill" style="background: var(--accent-purple);">AI</span></span>
                    <span class="text-muted small">{{ $ticket->created_at->format('H:i') }}</span>
                </div>
                <div class="chat-bubble ai">
                    {{ $ticket->ai_recommendation }}
                </div>
            </div>
            @endif

            @foreach($ticket->comments as $comment)
            <div class="mb-4 position-relative">
                <div class="chat-avatar rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: {{ $comment->user->role == 'it_support' ? 'var(--primary-blue)' : '#94a3b8' }};">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-bold">{{ $comment->user->name }}
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
            @endforeach

            <div class="mt-4">
                <form action="{{ route('support.tickets.comment', $ticket) }}" method="POST">
                    @csrf
                    <textarea name="comment" class="form-control form-control-custom mb-3" rows="3" placeholder="Balas ke user.." required></textarea>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-light"><i class="bi bi-paperclip"></i> Lampirkan</button>
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-send"></i> Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-person-check me-1"></i> Assign IT Support
            </h6>

            <div class="mb-3 small text-muted">
                Saat ini ditangani:
                <div class="fw-bold text-dark mt-1">
                    {{ $ticket->assignedSupport?->name ?? 'Belum ditugaskan' }}
                </div>
            </div>

            <form action="{{ route('support.tickets.assign', $ticket) }}" method="POST">
                @csrf

                <select name="assigned_to" class="form-select form-control-custom mb-3">
                    <option value="">Belum ditugaskan</option>

                    @foreach($supportUsers as $support)
                    <option value="{{ $support->id }}" {{ $ticket->assigned_to == $support->id ? 'selected' : '' }}>
                        {{ $support->name }} - {{ $support->email }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">
                    Simpan Assign
                </button>
            </form>
        </div>
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form action="{{ route('support.tickets.status', $ticket) }}" method="POST">
                @csrf
                <select name="status" class="form-select form-control-custom mb-3">
                    <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Baru Masuk</option>
                    <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                    <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Selesai Ditangani</option>
                </select>
                <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">Simpan Status</button>
            </form>
        </div>

        <div class="ai-card p-4 mb-4">
            <h6 class="fw-bold d-flex align-items-center gap-2" style="color: var(--accent-purple);"><i class="bi bi-stars"></i> Rekomendasi AI</h6>
            
            @if($ticket->ai_recommendation)
                <div class="p-3 small mb-3 text-dark" style="background: rgba(255,255,255,0.8); border-radius: 12px; border: 1px solid #d8b4fe; white-space: pre-wrap;">{{ $ticket->ai_recommendation }}</div>
            @else
                <p class="small text-muted mb-3">Dapatkan rekomendasi penanganan otomatis menggunakan AI berdasarkan detail tiket ini.</p>
            @endif
            
            <form action="{{ route('support.tickets.generate-ai', $ticket) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm w-100 border fw-bold" style="background: white; color: var(--accent-purple);">
                    <i class="bi bi-robot"></i> {{ $ticket->ai_recommendation ? 'Generate Ulang' : 'Generate AI Suggestion' }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection