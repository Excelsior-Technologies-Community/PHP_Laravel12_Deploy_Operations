@extends('admin.layout')
@section('title', 'Operation History')

@section('content')
<div class="page-title">📋 Operation History</div>

<div class="card">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Operation Name</th><th>Status</th><th>Executed At</th><th>Created At</th></tr>
        </thead>
        <tbody>
            @forelse($histories as $h)
            <tr>
                <td>{{ $h->id }}</td>
                <td><code style="color:#38bdf8">{{ $h->operation_name }}</code></td>
                <td><span class="badge-{{ $h->status }}">{{ $h->status }}</span></td>
                <td>{{ $h->executed_at }}</td>
                <td>{{ $h->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">No operation history found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $histories->links() }}</div>
</div>
@endsection
