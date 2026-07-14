@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="page-title">📊 Deployment Analytics Dashboard</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2>{{ $total }}</h2>
            <p>Total Deploys</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2 style="color:#22c55e">{{ $success }}</h2>
            <p>Successful</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2 style="color:#ef4444">{{ $failed }}</h2>
            <p>Failed</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2 style="color:#f59e0b">{{ $running }}</h2>
            <p>Running</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2 style="color:#6366f1">{{ $pendingApprovals }}</h2>
            <p>Pending Approvals</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stat-card text-center">
            <h2 style="color:#38bdf8">{{ $activeLocks }}</h2>
            <p>Active Locks</p>
        </div>
    </div>
</div>

<!-- Success Rate -->
<div class="card mb-4 text-center" style="background: linear-gradient(135deg, rgba(34,197,94,0.1), rgba(59,130,246,0.1));">
    <h5 style="color:#94a3b8">Overall Success Rate</h5>
    <h1 style="font-size:48px; font-weight:700; color:#22c55e">{{ $successRate }}%</h1>
    <div class="progress mt-2" style="max-width:400px; margin:0 auto;">
        <div class="progress-bar bg-success" style="width:{{ $successRate }}%"></div>
    </div>
</div>

<!-- Recent Deployments -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">📦 Recent Deployments</h5>
        <a href="{{ route('admin.deployments') }}" class="btn btn-sm btn-outline-info">View All</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Environment</th><th>Status</th><th>Description</th><th>Time</th></tr>
        </thead>
        <tbody>
            @forelse($latestDeployments as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->deployment_name }}</td>
                <td><span class="badge bg-secondary">{{ $log->environment }}</span></td>
                <td><span class="badge-{{ $log->status }}">{{ $log->status }}</span></td>
                <td>{{ Str::limit($log->description, 40) }}</td>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No deployments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
