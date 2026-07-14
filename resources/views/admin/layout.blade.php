<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Deploy Operations Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; margin: 0; }
        .sidebar { width: 240px; min-height: 100vh; background: #1e293b; position: fixed; top: 0; left: 0; padding: 20px 0; border-right: 1px solid rgba(255,255,255,0.08); }
        .sidebar .brand { padding: 10px 20px 30px; font-size: 18px; font-weight: 700; color: #38bdf8; }
        .sidebar a { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(56,189,248,0.1); color: #38bdf8; border-left: 3px solid #38bdf8; }
        .main { margin-left: 240px; padding: 30px; }
        .page-title { font-size: 24px; font-weight: 700; margin-bottom: 25px; }
        .card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 20px; color: #e2e8f0; }
        .stat-card h2 { font-size: 36px; font-weight: 700; margin: 0; }
        .stat-card p { color: #94a3b8; margin: 5px 0 0; font-size: 13px; }
        .badge-success { background: #22c55e; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-failed  { background: #ef4444; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-running { background: #f59e0b; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-pending { background: #6366f1; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-approved{ background: #22c55e; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-rejected{ background: #ef4444; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        table { color: #e2e8f0 !important; }
        thead { background: rgba(255,255,255,0.06); }
        th { color: #94a3b8 !important; font-weight: 600; border: none !important; font-size: 13px; }
        td { border-color: rgba(255,255,255,0.05) !important; font-size: 13px; vertical-align: middle; }
        .btn-sm { font-size: 12px; }
        .alert { border-radius: 10px; font-size: 13px; }
        .pipeline-step { display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 8px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); margin-bottom: 6px; font-size: 13px; }
        .pipeline-step .step-icon { font-size: 16px; }
        .step-success { border-left: 3px solid #22c55e; }
        .step-failed  { border-left: 3px solid #ef4444; }
        .step-running { border-left: 3px solid #f59e0b; }
        .step-pending { border-left: 3px solid #6366f1; }
        .progress { background: rgba(255,255,255,0.1); border-radius: 10px; height: 8px; }
        .progress-bar { border-radius: 10px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">🚀 Deploy Ops</div>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('admin.history') }}" class="{{ request()->routeIs('admin.history') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Operation History
    </a>
    <a href="{{ route('admin.pipeline') }}" class="{{ request()->routeIs('admin.pipeline') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> Pipeline
    </a>
    <a href="{{ route('admin.approvals') }}" class="{{ request()->routeIs('admin.approvals') ? 'active' : '' }}">
        <i class="bi bi-shield-check"></i> Approvals
    </a>
    <a href="{{ route('admin.audit') }}" class="{{ request()->routeIs('admin.audit') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Audit Trail
    </a>
    <a href="{{ route('admin.deployments') }}" class="{{ request()->routeIs('admin.deployments') ? 'active' : '' }}">
        <i class="bi bi-list-ul"></i> Deployment Logs
    </a>
</div>

<div class="main">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
