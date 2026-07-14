@extends('admin.layout')
@section('title', 'Deployment Logs')

@section('content')
<div class="page-title">📋 Deployment Logs</div>

<div class="card">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Deployment Name</th><th>Environment</th><th>Status</th><th>Description</th><th>Created At</th></tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td><code style="color:#38bdf8">{{ $log->deployment_name }}</code></td>
                <td><span class="badge bg-secondary">{{ $log->environment }}</span></td>
                <td><span class="badge-{{ $log->status }}">{{ $log->status }}</span></td>
                <td>{{ Str::limit($log->description, 50) ?? '-' }}</td>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No deployment logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection
