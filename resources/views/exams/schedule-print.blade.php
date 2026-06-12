<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Timetable — {{ $class->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111;
            background: #fff;
        }

        .page {
            max-width: 800px;
            margin: 20px auto;
            border: 2px solid #1a1a2e;
            padding: 24px 28px;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px double #1a1a2e;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .header h1 { font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p  { font-size: 12px; color: #444; margin-top: 3px; }
        .header .doc-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 8px;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Meta info */
        .meta-row {
            display: flex;
            justify-content: space-between;
            background: #f0f0f0;
            padding: 8px 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            font-size: 12px;
        }

        .meta-row span strong { margin-right: 4px; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead th {
            background: #1a1a2e;
            color: #fff;
            padding: 7px 10px;
            text-align: left;
            font-size: 12px;
        }

        thead th:nth-child(n+3) { text-align: center; }

        tbody td {
            padding: 6px 10px;
            border: 1px solid #ccc;
            vertical-align: middle;
        }

        tbody td:nth-child(n+3) { text-align: center; }

        tbody tr:nth-child(even) td { background: #f9f9f9; }

        .subject-name { font-weight: bold; font-size: 13px; }

        .day-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            background: #e8ecf0;
            color: #333;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .sig-block { width: 30%; text-align: center; }
        .sig-line {
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 11px;
            color: #555;
        }

        /* Footer note */
        .footer-note {
            font-size: 11px;
            color: #777;
            text-align: center;
            margin-top: 16px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        /* Print controls */
        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .btn-print {
            background: #1a1a2e;
            color: #fff;
            border: none;
            padding: 10px 30px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
            text-decoration: none;
            display: inline-block;
        }

        @media print {
            body { margin: 0; }
            .page { margin: 0; border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="page">

    <div class="header">
        <h1>{{ $school['name'] }}</h1>
        @if($school['address'])<p>{{ $school['address'] }}</p>@endif
        @if($school['phone'])<p>Tel: {{ $school['phone'] }}</p>@endif
        <div class="doc-title">Examination Timetable</div>
    </div>

    <div class="meta-row">
        <span><strong>Exam:</strong> {{ $exam->name }}</span>
        <span><strong>Academic Year:</strong> {{ $exam->academic_year }}</span>
        <span><strong>Class:</strong> {{ $class->name }}</span>
        <span><strong>Total Papers:</strong> {{ $schedules->count() }}</span>
    </div>

    @if($schedules->isNotEmpty())

        @php
            $byDate = $schedules->groupBy(fn($s) => $s->date->format('Y-m-d'));
        @endphp

        @foreach ($byDate as $dateKey => $entries)
            @php $dateObj = \Carbon\Carbon::parse($dateKey); @endphp
            <table>
                <thead>
                    <tr>
                        <th colspan="6" style="background:#2c3e6b; font-size:12px; letter-spacing:.5px;">
                            {{ $dateObj->format('l, d F Y') }}
                        </th>
                    </tr>
                    <tr>
                        <th style="width:30px">#</th>
                        <th>Subject</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Room / Hall</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $s)
                        @php
                            $start = \Carbon\Carbon::createFromTimeString($s->start_time);
                            $end   = \Carbon\Carbon::createFromTimeString($s->end_time);
                            $mins  = $start->diffInMinutes($end);
                            $dur   = ($mins >= 60 ? floor($mins/60).'h ' : '') . ($mins % 60 ? ($mins % 60).'m' : '');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="subject-name">
                                {{ $s->subject->name ?? '—' }}
                                @if($s->section)
                                    <span class="day-badge" style="margin-left:6px;">Sec {{ $s->section->name }}</span>
                                @endif
                                @if($s->remarks)
                                    <br><small class="text-muted">{{ $s->remarks }}</small>
                                @endif
                            </td>
                            <td>{{ $start->format('h:i A') }}</td>
                            <td>{{ $end->format('h:i A') }}</td>
                            <td>{{ $dur }}</td>
                            <td>{{ $s->room ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

    @else
        <p style="text-align:center; color:#999; padding:30px 0;">No schedule entries found for this exam and class.</p>
    @endif

    <div class="signatures">
        <div class="sig-block"><div class="sig-line">Class Teacher</div></div>
        <div class="sig-block"><div class="sig-line">Examination Controller</div></div>
        <div class="sig-block"><div class="sig-line">Principal</div></div>
    </div>

    <div class="footer-note">
        Printed on {{ now()->format('d M Y, h:i A') }} &nbsp;|&nbsp; {{ $school['name'] }}
    </div>

</div>

<div class="no-print">
    <a href="{{ route('exam-schedule.index') }}" class="btn-back">← Back</a>
    <button class="btn-print" onclick="window.print()">Print Timetable</button>
</div>

</body>
</html>
