@php
$notifications = auth()->check()
? \App\Models\Notification::where('user_id', auth()->id())
->latest()
->take(5)
->get()
: collect();

$unreadCount = auth()->check()
? \App\Models\Notification::where('user_id', auth()->id())
->where('is_read', false)
->count()
: 0;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITCARE - @yield('title', 'Helpdesk IT')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('css')
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-display"></i>
            <div>
                <div>ITCARE</div>
                <div style="font-size: 0.6rem; font-weight: 400; letter-spacing: 1px;">HELPDESK . KB . AI ASSISTANT</div>
            </div>
        </div>

        <ul class="sidebar-menu">
            @if(auth()->user()->role == 'it_support')
            <li><a href="{{ route('support.dashboard') }}" class="{{ request()->routeIs('support.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="{{ route('support.tickets.index') }}" class="{{ request()->routeIs('support.tickets.*') ? 'active' : '' }}"><i class="bi bi-inbox"></i> Manajemen Pengaduan</a></li>
            <li><a href="{{ route('support.kb.index') }}" class="{{ request()->routeIs('support.kb.*') ? 'active' : '' }}"><i class="bi bi-book"></i> Knowledge Base</a></li>
            <li><a href="{{ route('support.ai.index') }}" class="{{ request()->routeIs('support.ai.*') ? 'active' : '' }}"><i class="bi bi-stars"></i> AI Assistant</a></li>
            @else
            <li><a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Beranda</a></li>
            <li><a href="{{ route('user.tickets.index') }}" class="{{ request()->routeIs('user.tickets.index') || request()->routeIs('user.tickets.show') ? 'active' : '' }}"><i class="bi bi-card-list"></i> Ajukan Keluhan Saya</a></li>
            <li><a href="{{ route('user.tickets.create') }}" class="{{ request()->routeIs('user.tickets.create') ? 'active' : '' }}"><i class="bi bi-plus-lg"></i> Ajukan Keluhan</a></li>
            <li><a href="{{ route('user.kb.index') }}" class="{{ request()->routeIs('user.kb.*') ? 'active' : '' }}"><i class="bi bi-book"></i> Panduan IT</a></li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-white text-decoration-none p-0 d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <form action="{{ route('search') }}" method="GET" class="search-bar">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari pengajuan, artikel, atau solusi...">
            </form>

            <div class="topbar-right">
                <div class="dropdown">
                    <button class="btn border-0 position-relative"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-bell fs-5 text-muted"></i>

                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-2 shadow border-0"
                        style="width: 320px; border-radius: 16px;">

                        <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                            <h6 class="fw-bold mb-0">Notifikasi</h6>
                        </div>

                        @forelse($notifications as $notif)
                        <div class="p-3 rounded mb-2"
                            style="background-color: {{ $notif->is_read ? '#ffffff' : '#f1f5ff' }};">

                            <div class="fw-bold small">
                                {{ $notif->title }}
                            </div>

                            <div class="small text-muted">
                                {{ $notif->message }}
                            </div>

                            <div class="small text-muted mt-1">
                                {{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted small py-4">
                            Belum ada notifikasi
                        </div>
                        @endforelse
                    </div>
                </div>
                <div style="width: 1px; height: 30px; background-color: var(--border-color);"></div>
                <a href="{{ auth()->user()->role == 'it_support' ? route('support.profile') : route('user.profile') }}"
                    class="profile-dropdown text-decoration-none text-dark">

                    @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                        alt="Profile Photo"
                        class="rounded-circle"
                        style="width: 45px; height: 45px; object-fit: cover;">
                    @else
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif

                    <div>
                        <div class="fw-bold fs-6" style="line-height: 1;">
                            {{ auth()->user()->name }}
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ auth()->user()->role == 'it_support' ? 'IT Support' : 'User' }}
                        </small>
                    </div>
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="content-area">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('js')
</body>

</html>