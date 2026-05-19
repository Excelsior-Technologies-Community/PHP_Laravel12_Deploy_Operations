<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Deployment Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #1e293b, #0f172a);
            color: #ffffff;
            padding: 40px;
        }

        .title {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 40px;
        }

        /* CARDS */
        .card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 25px;
            backdrop-filter: blur(10px);
            transition: 0.3s;
            color: #fff;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .card h2 {
            font-size: 34px;
            font-weight: 700;
            color: #fff;
        }

        .card p {
            margin-top: 8px;
            color: #cbd5e1;
        }

        /* RATE CARD */
        .rate-card {
            margin-top: 30px;
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(59, 130, 246, 0.15));
        }

        .rate-card h1 {
            font-size: 42px;
            font-weight: 700;
        }

        /* TABLE */
        .table-container {
            margin-top: 50px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 18px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        table {
            color: #fff !important;
        }

        thead {
            background: rgba(255, 255, 255, 0.08);
        }

        th {
            color: #cbd5e1 !important;
            font-weight: 600;
            border: none !important;
        }

        td {
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* BADGES */
        .badge-success {
            background: #22c55e;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .badge-failed {
            background: #ef4444;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .badge-running {
            background: #f59e0b;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- TITLE -->
    <div class="title">
        🚀 Deployment Analytics Dashboard
    </div>

    <!-- CARDS -->
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card text-center">
                <h2>{{ $total }}</h2>
                <p>Total Deployments</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <h2>{{ $success }}</h2>
                <p>Successful</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <h2>{{ $failed }}</h2>
                <p>Failed</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <h2>{{ $running }}</h2>
                <p>Running</p>
            </div>
        </div>

    </div>

    <!-- SUCCESS RATE -->
    <div class="card rate-card mt-4">
        <h3>Success Rate</h3>
        <h1>{{ $successRate }}%</h1>
    </div>

    <!-- TABLE -->
    <div class="table-container mt-5">

        <h4 class="mb-3">📦 Recent Deployments</h4>

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Environment</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($latestDeployments as $log)

                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->deployment_name }}</td>
                    <td>{{ $log->environment }}</td>
                    <td>
                        <span class="
                            @if($log->status=='success') badge-success
                            @elseif($log->status=='failed') badge-failed
                            @else badge-running
                            @endif
                        ">
                            {{ $log->status }}
                        </span>
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

</body>
</html>