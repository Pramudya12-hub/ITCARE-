@extends('layouts.app')
@section('title', 'Manajemen Pengaduan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pengaduan</h4>
        <p class="text-muted mb-0">Kelola dan tangani semua tiket keluhan masuk.</p>
    </div>

    <a href="{{ route('support.tickets.export') }}"
       class="btn btn-success rounded-pill px-4">
        <i class="bi bi-file-earmark-spreadsheet me-2"></i>
        Export CSV
    </a>
</div>

<div class="card-custom mb-4 p-3">
    <form method="GET" action="{{ route('support.tickets.index') }}">
        <div class="input-group">

            <span class="input-group-text bg-white border-0">
                <i class="bi bi-search"></i>
            </span>

            <input type="text"
                   name="search"
                   class="form-control border-0"
                   placeholder="Cari ticket, user, kategori..."
                   value="{{ request('search') }}">

            <button class="btn btn-primary-custom px-4" type="submit">
                Cari
            </button>

        </div>
    </form>
</div>

<div class="card-custom mb-4 p-3">
    <form action="{{ route('support.tickets.index') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <select name="status" class="form-select form-control-custom">
                <option value="">Semua Status</option>
                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Baru Masuk</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Selesai Ditangani</option>
            </select>
        </div>

        <div class="col-md-4">
            <select name="category" class="form-select form-control-custom">
                <option value="">Semua Kategori</option>
                <option value="Network" {{ request('category') == 'Network' ? 'selected' : '' }}>Network</option>
                <option value="Software" {{ request('category') == 'Software' ? 'selected' : '' }}>Software</option>
                <option value="Hardware" {{ request('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="Account & Access" {{ request('category') == 'Account & Access' ? 'selected' : '' }}>Account & Access</option>
            </select>
        </div>

        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
            <a href="{{ route('support.tickets.index') }}" class="btn btn-light border">Reset</a>
        </div>
    </form>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="text-muted">
                <tr>
                    <th class="border-0 pb-3">ID TIKET</th>
                    <th class="border-0 pb-3">PELAPOR</th>
                    <th class="border-0 pb-3">JUDUL KELUHAN</th>
                    <th class="border-0 pb-3">DITANGANI</th>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width:30px; height:30px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                </div>

                                <div>
                                    <div class="fw-bold" style="font-size:0.9rem;">{{ $ticket->user->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">
                                        {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ Str::limit($ticket->title, 40) }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-tag"></i> {{ $ticket->category }}
                            </div>
                        </td>

                        <td>
                            @if($ticket->assignedSupport)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                         style="width:30px; height:30px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($ticket->assignedSupport->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="fw-bold" style="font-size:0.9rem;">
                                            {{ $ticket->assignedSupport->name }}
                                        </div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            {{ $ticket->assignedSupport->email }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-secondary rounded-pill">
                                    Belum Ditugaskan
                                </span>
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
                            <a href="{{ route('support.tickets.show', $ticket) }}" class="btn btn-light btn-sm rounded-pill px-3">
                                Proses Tiket
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada data pengajuan.
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