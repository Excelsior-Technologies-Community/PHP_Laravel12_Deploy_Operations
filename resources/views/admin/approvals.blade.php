@extends('admin.layout')
@section('title', 'Approval Gates')

@section('content')
<div class="page-title">🛡️ Manual Approval Gates</div>

<!-- Request New Approval -->
<div class="card mb-4">
    <h6 class="mb-3">➕ Request New Approval</h6>
    <form action="{{ route('admin.approvals.store') }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-4">
            <input type="text" name="operation_name" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Operation name (e.g. create_new_article)" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="requested_by" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Requested by" value="admin">
        </div>
        <div class="col-md-2">
            <button class="btn btn-sm btn-primary w-100">Request Approval</button>
        </div>
    </form>
</div>

<!-- Approvals Table -->
<div class="card">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Operation</th><th>Status</th><th>Requested By</th><th>Approved By</th><th>Reason</th><th>Time</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($approvals as $gate)
            <tr>
                <td>{{ $gate->id }}</td>
                <td><code style="color:#38bdf8">{{ $gate->operation_name }}</code></td>
                <td><span class="badge-{{ $gate->status }}">{{ $gate->status }}</span></td>
                <td>{{ $gate->requested_by ?? '-' }}</td>
                <td>{{ $gate->approved_by ?? '-' }}</td>
                <td>{{ Str::limit($gate->reason, 30) ?? '-' }}</td>
                <td>{{ $gate->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if($gate->status === 'pending')
                    <form action="{{ route('admin.approvals.approve', $gate->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="approved_by" value="admin">
                        <button class="btn btn-sm btn-success">✅ Approve</button>
                    </form>
                    <form action="{{ route('admin.approvals.reject', $gate->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="reason" value="Rejected by admin">
                        <button class="btn btn-sm btn-danger">❌ Reject</button>
                    </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted">No approval requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
