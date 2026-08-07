<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $event->name }} - Ehsebly Report</title>
    <style>
        @page {
            margin: 30px 36px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        .header {
            background: linear-gradient(90deg, #10b981, #6366f1);
            background-color: #10b981;
            color: #ffffff;
            padding: 20px 24px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .header .brand {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 0.85;
            margin: 0 0 6px 0;
        }

        .header h1 {
            font-size: 26px;
            margin: 0 0 4px 0;
        }

        .header p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }

        h2.section-title {
            font-size: 15px;
            color: #059669;
            border-bottom: 2px solid #d1fae5;
            padding-bottom: 6px;
            margin: 26px 0 12px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f0fdf4;
            color: #059669;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border-bottom: 1px solid #d1fae5;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 11.5px;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            background-color: #eef2ff;
            color: #4f46e5;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .empty {
            color: #9ca3af;
            font-style: italic;
            padding: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <p class="brand">Ehsebly</p>
        <h1>{{ $event->name }}</h1>
        <p>
            {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('F d, Y') : 'Date TBD' }}
            &nbsp;&middot;&nbsp; Organized by {{ $event->creator->name }}
            &nbsp;&middot;&nbsp; Currency: {{ $event->currency }}
            @if ($event->budget)
                &nbsp;&middot;&nbsp; Budget: {{ number_format($event->totalSpent(), 2) }} /
                {{ number_format($event->budget, 2) }} {{ $event->currency }}
            @endif
        </p>
    </div>

    <h2 class="section-title">Participants ({{ $event->participants->count() }})</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->participants as $participant)
                <tr>
                    <td>{{ $participant->user->name ?? $participant->guest_name }}</td>
                    <td>{{ $participant->user ? 'Registered' : 'Guest' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Expenses ({{ $event->expenses->count() }})</h2>
    @if ($event->expenses->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Paid By</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->expenses as $expense)
                    <tr>
                        <td>{{ $expense->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                        <td>
                            @foreach ($expense->payers as $payer)
                                {{ $payer->participant->user->name ?? $payer->participant->guest_name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="text-right amount">{{ number_format($expense->total_amount, 2) }}
                            {{ $event->currency }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td class="text-right amount">{{ number_format($event->expenses->sum('total_amount'), 2) }}
                        {{ $event->currency }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="empty">No expenses recorded.</p>
    @endif

    <h2 class="section-title">Settlements &mdash; Who Owes Whom</h2>
    @if ($settlements->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settlements as $settlement)
                    <tr>
                        <td>{{ $settlement->fromParticipant->user->name ?? $settlement->fromParticipant->guest_name }}
                        </td>
                        <td>{{ $settlement->toParticipant->user->name ?? $settlement->toParticipant->guest_name }}</td>
                        <td><span class="badge">{{ ucfirst($settlement->status) }}</span></td>
                        <td class="text-right amount">{{ number_format($settlement->amount, 2) }}
                            {{ $event->currency }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty">No settlements recorded yet.</p>
    @endif

    <div class="footer">
        Generated by Ehsebly on {{ now()->format('F d, Y \a\t h:i A') }}
    </div>
</body>

</html>
