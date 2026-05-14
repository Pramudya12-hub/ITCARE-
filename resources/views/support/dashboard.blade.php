@extends('layouts.app')
@section('title', 'Dashboard Support')

@section('content')
@php
    $statusText = function ($status) {
        return $status == 'Open'
            ? 'Baru Masuk'
            : ($status == 'In Progress' ? 'Sedang Ditangani' : 'Selesai Ditangani');
    };

    $colors = [
        'var(--primary-blue)',
        '#22d3ee',
        'var(--accent-purple)',
        '#f59e0b',
        '#ef4444',
        '#10b981'
    ];

    $conicGradients = [];
    $start = 0;

    foreach ($categories as $idx => $cat) {
        $end = $start + $cat->percentage;
        $color = $colors[$idx % count($colors)];
        $conicGradients[] = $color . ' ' . $start . '% ' . $end . '%';
        $start = $end;
    }

    $conicGradientStr = empty($conicGradients)
        ? '#e5e7eb 0% 100%'
        : implode(', ', $conicGradients);

    $donutStyle = 'width: 150px; height: 150px; border-radius: 50%; background: conic-gradient(' . $conicGradientStr . ');';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard</h3>
        <p class="text-muted mb-0">Ringkasan operasional helpdesk IT.</p>
    </div>

    <a href="{{ route('support.tickets.export') }}"
       class="btn btn-success rounded-pill px-4">
        <i class="bi bi-file-earmark-spreadsheet me-2"></i>
        Export Laporan
    </a>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-custom text-center border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px; background-color: #e0e7ff; color: #3730a3;">
                    <i class="bi bi-inbox fs-5"></i>
                </div>
                <i class="bi bi-graph-up-arrow text-success"></i>
            </div>

            <div class="text-start">
                <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                <div class="text-muted small">Total Tiket</div>
                <div class="text-success small mt-1">Data real-time</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-custom text-center border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px; background-color: #fef08a; color: #854d0e;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <i class="bi bi-graph-up-arrow text-success"></i>
            </div>

            <div class="text-start">
                <h2 class="fw-bold mb-0">{{ $stats['in_progress'] }}</h2>
                <div class="text-muted small">Sedang Ditangani</div>
                <div class="text-success small mt-1">Dalam proses</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-custom text-center border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px; background-color: #dcfce7; color: #166534;">
                    <i class="bi bi-check-circle fs-5"></i>
                </div>
                <i class="bi bi-graph-up-arrow text-success"></i>
            </div>

            <div class="text-start">
                <h2 class="fw-bold mb-0">{{ $stats['closed'] }}</h2>
                <div class="text-muted small">Tiket Selesai</div>
                <div class="text-success small mt-1">Selesai ditangani</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-custom text-center border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px; background-color: #f3e8ff; color: #6b21a8;">
                    <i class="bi bi-stars fs-5"></i>
                </div>
                <i class="bi bi-graph-up-arrow text-success"></i>
            </div>

            <div class="text-start">
                <h2 class="fw-bold mb-0">{{ $stats['kb_total'] }}</h2>
                <div class="text-muted small">Artikel KB</div>
                <div class="text-success small mt-1">Knowledge base</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card-custom h-100">
            <h6 class="fw-bold mb-1">Volume Tiket Mingguan</h6>
            <p class="text-muted small mb-4">Pengajuan masuk vs diselesaikan</p>

            @php
                $maxVol = collect($weeklyVolume)->max('total') ?: 1;
            @endphp

            <div class="d-flex align-items-end justify-content-between px-2" style="height: 200px;">
                @foreach(array_reverse($weeklyVolume) as $vol)
                    @php
                        $hTotal = ($vol['total'] / $maxVol) * 100;
                        $hResolved = ($vol['resolved'] / $maxVol) * 100;

                        $totalStyle = 'width: 25px; height: ' . $hTotal . '%; background-color: var(--primary-blue); border-radius: 4px 4px 0 0;';
                        $resolvedStyle = 'width: 25px; height: ' . $hResolved . '%; background-color: #22d3ee; border-radius: 4px 4px 0 0;';
                    @endphp

                    <div class="d-flex gap-1 align-items-end h-100">
                        <div style="{{ $totalStyle }}" title="Masuk: {{ $vol['total'] }}"></div>
                        <div style="{{ $resolvedStyle }}" title="Selesai: {{ $vol['resolved'] }}"></div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between text-muted small mt-2 px-2 border-top pt-2">
                @foreach(array_reverse($weeklyVolume) as $vol)
                    <span>{{ $vol['day'] }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-custom h-100">
            <h6 class="fw-bold mb-1">Per Kategori</h6>
            <p class="text-muted small mb-4">Distribusi pengajuan keseluruhan</p>

            <div class="d-flex justify-content-center mb-4">
                <div style="{{ $donutStyle }}">
                    <div style="width: 100px; height: 100px; background-color: white; border-radius: 50%; margin: 25px;"></div>
                </div>
            </div>

            <ul class="list-unstyled small mb-0">
                @forelse($categories as $idx => $cat)
                    @php
                        $dotStyle = 'width:10px; height:10px; background-color:' . $colors[$idx % count($colors)] . ';';
                    @endphp

                    <li class="d-flex justify-content-between mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-block" style="{{ $dotStyle }}"></span>
                            {{ $cat->category }}
                        </span>

                        <span>{{ $cat->percentage }}%</span>
                    </li>
                @empty
                    <li class="text-center text-muted">Belum ada data</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">Tiket Terbaru</h6>

        <a href="{{ route('support.tickets.index') }}"
           class="text-decoration-none small fw-bold"
           style="color: var(--text-main);">
            Lihat semua &rarr;
        </a>
    </div>

    @forelse($latestTickets as $ticket)
        <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                 style="width: 50px; height: 50px;">
                <i class="bi bi-display fs-4 text-primary"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-muted small fw-bold">
                        ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                    </span>

                    <span class="badge badge-status {{ $ticket->status == 'Open' ? 'status-open' : ($ticket->status == 'In Progress' ? 'status-progress' : 'status-closed') }}">
                        {{ $statusText($ticket->status) }}
                    </span>

                    <span class="badge badge-priority {{ $ticket->priority == 'High' ? 'priority-high' : ($ticket->priority == 'Medium' ? 'priority-medium' : 'priority-low') }}">
                        {{ $ticket->priority }}
                    </span>
                </div>

                <h6 class="fw-bold mb-0 text-dark">{{ $ticket->title }}</h6>
            </div>

            <div class="text-muted small text-end">
                <div>{{ $ticket->user->name }}</div>
                <div>{{ $ticket->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
            <h6 class="fw-bold text-dark">Belum ada tiket</h6>
            <p class="small mb-0">Tiket yang baru masuk akan muncul di sini.</p>
        </div>
    @endforelse
</div>
<div class="card-custom mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="fw-bold mb-1">Recent Active Users</h6>
            <p class="text-muted small mb-0">
                Monitoring aktivitas pengguna sistem terbaru.
            </p>
        </div>

        <span class="badge bg-light text-dark border">
            Live Activity
        </span>
    </div>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>
                <tr class="text-muted small">
                    <th>User</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Last Active</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($activeUsers as $user)

                    @php
                        $isOnline =
                            $user->last_active_at &&
                            \Carbon\Carbon::parse($user->last_active_at)->gt(now()->subMinutes(5));
                    @endphp

                    <tr>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width:35px; height:35px; font-size:0.8rem;">

                                    {{ strtoupper(substr($user->name, 0, 1)) }}

                                </div>

                                <div>
                                    <div class="fw-bold">
                                        {{ $user->name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $user->email }}
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td class="small text-muted">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : '-' }}
                        </td>

                        <td class="small text-muted">
                            {{ $user->last_active_at ? \Carbon\Carbon::parse($user->last_active_at)->diffForHumans() : '-' }}
                        </td>

                        <td>

                            @if($isOnline)

                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    ● Online
                                </span>

                            @else

                                <span class="badge bg-secondary-subtle text-secondary border">
                                    ● Offline
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada aktivitas user.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection
