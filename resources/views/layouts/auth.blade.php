<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITCARE - @yield('title', 'Login')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .auth-wrapper { min-height: 100vh; display: flex; }
        .auth-left { background-color: #0f172a; color: white; width: 45%; padding: 4rem; display: flex; flex-direction: column; justify-content: center; }
        .auth-right { width: 55%; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-form-container { width: 100%; max-width: 400px; }
        .auth-card { background: white; border-radius: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 2.5rem; }
        .form-control-custom { border-radius: 12px; padding: 12px 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; }
        .form-control-custom:focus { background-color: #fff; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-primary-custom { background-color: #3b82f6; color: white; border-radius: 12px; padding: 12px; font-weight: 500; width: 100%; border: none; }
        .btn-primary-custom:hover { background-color: #2563eb; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left d-none d-md-flex">
            <h1 class="fw-bold mb-4 d-flex align-items-center gap-3">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                ITCARE
            </h1>
            <p class="fs-5 mb-4 text-light opacity-75">Sistem Helpdesk IT & Knowledge Base Internal Perusahaan.</p>
            <ul class="list-unstyled d-flex flex-column gap-3">
                <li class="d-flex align-items-center gap-3"><span class="badge bg-primary rounded-circle p-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span> Pelaporan Cepat</li>
                <li class="d-flex align-items-center gap-3"><span class="badge bg-primary rounded-circle p-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span> Solusi Mandiri (KB)</li>
                <li class="d-flex align-items-center gap-3"><span class="badge bg-primary rounded-circle p-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span> AI Powered Assistance</li>
            </ul>
        </div>
        <div class="auth-right">
            <div class="auth-form-container">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
