@extends('admin.layout')
@section('title', 'Deployment Pipeline')

@section('content')
<div class="page-title">🔄 Visual Deployment Pipeline</div>

@if($pipelines->isEmpty())
    <div class="card text-center text-muted py-5">
        <i class="bi bi-diagram-3" style="font-size:48px; color:#334155"></i>
        <p class="mt-3">No pipeline data yet. Run <code>php artisan deploy:run</code> to generate pipeline steps.</p>
    </div>
@else

@foreach($pipelines as $deploymentName => $steps)
<div class="card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0" style="color:#38bdf8"><i class="bi bi-rocket-takeoff me-2"></i>{{ $deploymentName }}</h6>
        @php
            $allSuccess = $steps->every(fn($s) => $s->status === 'success');
            $anyFailed  = $steps->contains(fn($s) => $s->status === 'failed');
        @endphp
        <span class="badge-{{ $allSuccess ? 'success' : ($anyFailed ? 'failed' : 'running') }}">
            {{ $allSuccess ? 'Completed' : ($anyFailed ? 'Failed' : 'In Progress') }}
        </span>
    </div>

    <!-- Mermaid Flow Diagram -->
    <div class="mermaid mb-3" style="background:rgba(255,255,255,0.02); border-radius:10px; padding:15px;">
        graph LR
        @foreach($steps->sortBy('step_order') as $step)
            @php
                $icon = match($step->status) {
                    'success' => '✅',
                    'failed'  => '❌',
                    'running' => '⏳',
                    default   => '⬜',
                };
                $nodeId = 'S' . $step->step_order;
                $label  = $icon . ' ' . $step->step_name;
            @endphp
            {{ $nodeId }}["{{ $label }}"]
            @if(!$loop->last)
            {{ $nodeId }} --> S{{ $step->step_order + 1 }}
            @endif
        @endforeach

        @foreach($steps->sortBy('step_order') as $step)
            @php $nodeId = 'S' . $step->step_order; @endphp
            @if($step->status === 'success')
                style {{ $nodeId }} fill:#166534,stroke:#22c55e,color:#fff
            @elseif($step->status === 'failed')
                style {{ $nodeId }} fill:#7f1d1d,stroke:#ef4444,color:#fff
            @elseif($step->status === 'running')
                style {{ $nodeId }} fill:#78350f,stroke:#f59e0b,color:#fff
            @else
                style {{ $nodeId }} fill:#1e1b4b,stroke:#6366f1,color:#fff
            @endif
        @endforeach
    </div>

    <!-- Step Details -->
    @foreach($steps->sortBy('step_order') as $step)
    <div class="pipeline-step step-{{ $step->status }}">
        <span class="step-icon">
            @if($step->status === 'success') ✅
            @elseif($step->status === 'failed') ❌
            @elseif($step->status === 'running') ⏳
            @else ⬜
            @endif
        </span>
        <span style="font-weight:600; min-width:180px">{{ $step->step_name }}</span>
        <span class="badge-{{ $step->status }} me-2">{{ $step->status }}</span>
        @if($step->started_at)
            <small class="text-muted">Started: {{ $step->started_at->format('H:i:s') }}</small>
        @endif
        @if($step->completed_at)
            <small class="text-muted ms-2">Completed: {{ $step->completed_at->format('H:i:s') }}</small>
        @endif
        @if($step->output)
            <small class="text-muted ms-2">— {{ Str::limit($step->output, 50) }}</small>
        @endif
    </div>
    @endforeach
</div>
@endforeach

@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    mermaid.initialize({
        startOnLoad: true,
        theme: 'dark',
        themeVariables: { primaryColor: '#1e293b', edgeLabelBackground: '#0f172a' }
    });
</script>
@endsection
