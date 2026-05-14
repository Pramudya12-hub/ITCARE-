@extends('layouts.app')
@section('title', 'Daftar Keluhan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="card-custom mb-4 p-3">
        <form method="GET" action="{{ route('user.tickets.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search"></i>
                </span>

                <input type="text"
                    name="search"
                    class="form-control border-0"
                    placeholder="Cari ticket, kategori, status..."
                    value="{{ request('search') }}">

                <button class="btn btn-primary-custom px-4" type="submit">
                    Cari
                </button>
            </div>
        </form>
    </div>
    <div>
        <h4 class="fw-bold mb-1">Pengajuan Saya</h4>
        <p class="text-muted mb-0">Daftar keluhan IT yang pernah Anda ajukan.</p>
    </div>
    <a href="{{ route('user.tickets.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Buat Pengajuan
    </a>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="text-muted">
                <tr>
                    <th class="border-0 pb-3">ID TIKET</th>
                    <th class="border-0 pb-3">KATEGORI</th>
                    <th class="border-0 pb-3">JUDUL</th>
                    <th class="border-0 pb-3">STATUS</th>
                    <th class="border-0 pb-3">PRIORITAS</th>
                    <th class="border-0 pb-3 text-end">AKSI</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tickets as $ticket)
                @php
                $statusLabel = $ticket->status == 'Open'
                ? 'Baru Masuk'
                : ($ticket->status == 'In Progress'
                ? 'Sedang Ditangani'
                : 'Selesai Ditangani');
                @endphp

                <tr>
                    <td class="fw-medium">
                        ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                    </td>

                    <td>
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="bi bi-tag text-muted"></i> {{ $ticket->category }}
                        </span>
                    </td>

                    <td>
                        <span class="fw-bold text-dark">{{ $ticket->title }}</span>
                        <br>
                        <small class="text-muted">{{ $ticket->created_at->format('d M Y') }}</small>

                        @if($ticket->assignedSupport)
                        <div class="small text-muted mt-1">
                            Ditangani oleh:
                            <span class="fw-bold">{{ $ticket->assignedSupport->name }}</span>
                        </div>
                        @else
                        <div class="small text-muted mt-1">Belum ditugaskan</div>
                        @endif
                    </td>

                    <td>
                        <span class="badge badge-status {{ $ticket->status == 'Open' ? 'status-open' : ($ticket->status == 'In Progress' ? 'status-progress' : 'status-closed') }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-priority {{ $ticket->priority == 'High' ? 'priority-high' : ($ticket->priority == 'Medium' ? 'priority-medium' : 'priority-low') }}">
                            {{ $ticket->priority }}
                        </span>
                    </td>

                    <td class="text-end">
                        <a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-light btn-sm rounded-pill px-3">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-emoji-smile fs-1 d-block mb-3 text-secondary opacity-50"></i>
                        <h6 class="fw-bold text-dark">Belum ada pengajuan</h6>
                        <p class="small mb-0">Anda belum pernah mengajukan tiket IT.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $tickets->links('pagination::bootstrap-5') }}
</div>
@endsection