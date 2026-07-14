@extends('admin.layout')
@section('title', 'Audit Trail')

@section('content')
<div class="page-title">📓 Audit Trail</div>

<div class="card">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Operation</th><th>Action</th><th>Performed By</th><th>Environment</th><th>Result</th><th>Notes</th><th>Time</th></tr>
        </thead>
        <tbody>
            @forelse($audits as $audit)
            <tr>
                <td>{{ $audit->id }}</td>
                <td><code style="color:#38bdf8">{{ $audit->operation_name }}</code></td>
                <td><span class="badge bg-secondary">{{ $audit->action }}</span></td>
                <td>{{ $audit->performed_by }}</td>
                <td><span class="badge bg-dark border border-secondary">{{ $audit->environment }}</span></td>
                <td><span class="badge-{{ $audit->result }}">{{ $audit->result }}</span></td>
                <td>{{ Str::limit($audit->notes, 40) }}</td>
                <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted">No audit trail found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $audits->links() }}</div>
</div>
@endsection
