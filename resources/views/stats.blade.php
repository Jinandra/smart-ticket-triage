<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Dashboard</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
</head>
<body>
    <div class="ticket-form">
        <h2>Ticket Stats</h2>

        <h3>Status Overview</h3>
        <ul>
            <li>🟢 Open: {{ $statusCounts['open'] ?? 0 }}</li>
            <li>🟡 Pending: {{ $statusCounts['pending'] ?? 0 }}</li>
            <li>🔴 Closed: {{ $statusCounts['closed'] ?? 0 }}</li>
        </ul>

        <h3>Category Overview</h3>
        <ul>
            @foreach($categoryCounts as $category => $count)
                <li><strong>{{ $category ?? 'Uncategorized' }}</strong>: {{ $count }}</li>
            @endforeach
        </ul>

        <a href="{{route('tickets')}}" class="btn">Back to Tickets</a>
    </div>
</body>
</html>
