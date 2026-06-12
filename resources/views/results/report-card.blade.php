@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card — {{ $student->full_name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111;
            background: #fff;
        }

        .page {
            max-width: 780px;
            margin: 20px auto;
            border: 2px solid #1a1a2e;
            padding: 24px 28px;
        }

        /* ── Header ── */
        .school-header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px double #1a1a2e;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .school-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .school-logo-placeholder {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #aaa;
            flex-shrink: 0;
        }

        .school-info { flex: 1; text-align: center; }
        .school-info h1 { font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .school-info p  { font-size: 12px; color: #444; margin-top: 2px; }
        .school-info .doc-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 6px;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .student-photo {
            width: 70px;
            height: 85px;
            object-fit: cover;
            border: 1px solid #999;
            flex-shrink: 0;
        }

        .student-photo-placeholder {
            width: 70px;
            height: 85px;
            border: 1px solid #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #aaa;
            flex-shrink: 0;
        }

        /* ── Student Info ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid #aaa;
            margin-bottom: 14px;
        }

        .info-grid .row {
            display: contents;
        }

        .info-grid .cell {
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }

        .info-grid .cell:nth-child(odd) {
            border-right: 1px solid #ddd;
            background: #f8f8f8;
            font-weight: bold;
            width: 35%;
        }

        /* ── Section Heading ── */
        .section-title {
            background: #1a1a2e;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0;
        }

        /* ── Marks Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead th {
            background: #e8ecf0;
            padding: 6px 8px;
            text-align: center;
            border: 1px solid #aaa;
            font-size: 12px;
        }

        tbody td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            text-align: center;
        }

        tbody tr:nth-child(even) td { background: #fafafa; }
        tbody td:nth-child(2)       { text-align: left; }

        .fail-row td { color: #c0392b; }

        .grade-pill {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 11px;
        }

        .grade-A-plus  { background: #d4edda; color: #155724; }
        .grade-A       { background: #d4edda; color: #155724; }
        .grade-B       { background: #cce5ff; color: #004085; }
        .grade-C       { background: #fff3cd; color: #856404; }
        .grade-D       { background: #ffeeba; color: #856404; }
        .grade-F       { background: #f8d7da; color: #721c24; }

        /* ── Summary Row ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }

        .summary-box {
            border: 1px solid #aaa;
            border-radius: 4px;
            text-align: center;
            padding: 8px 4px;
        }

        .summary-box .label { font-size: 10px; color: #555; text-transform: uppercase; }
        .summary-box .value { font-size: 18px; font-weight: bold; margin-top: 2px; }
        .summary-box.pass .value { color: #155724; }
        .summary-box.fail .value { color: #721c24; }

        /* ── Attendance ── */
        .attendance-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }

        .att-box {
            border: 1px solid #aaa;
            border-radius: 4px;
            text-align: center;
            padding: 6px 4px;
        }

        .att-box .label { font-size: 10px; color: #555; text-transform: uppercase; }
        .att-box .value { font-size: 16px; font-weight: bold; margin-top: 2px; }

        /* ── Remarks ── */
        .remarks-box {
            border: 1px solid #aaa;
            padding: 8px 12px;
            min-height: 40px;
            margin-bottom: 14px;
            font-size: 12px;
            color: #555;
        }

        /* ── Signatures ── */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .sig-block {
            width: 30%;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 11px;
            color: #444;
        }

        /* ── Print button ── */
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

    {{-- ── School Header ── --}}
    <div class="school-header">
        @if($school['logo'] && Storage::disk('public')->exists($school['logo']))
            <img src="{{ Storage::disk('public')->url($school['logo']) }}" alt="Logo" class="school-logo">
        @else
            <div class="school-logo-placeholder">LOGO</div>
        @endif

        <div class="school-info">
            <h1>{{ $school['name'] }}</h1>
            @if($school['address'])<p>{{ $school['address'] }}</p>@endif
            @if($school['phone'])<p>Tel: {{ $school['phone'] }}</p>@endif
            <div class="doc-title">Student Report Card</div>
            <p style="margin-top:4px;font-size:11px;">Exam: <strong>{{ $exam->name }}</strong> &nbsp;|&nbsp; Academic Year: <strong>{{ $exam->academic_year }}</strong></p>
        </div>

        @php $photo = $student->profilePhoto; @endphp
        @if($photo && Storage::disk('public')->exists($photo->file_path))
            <img src="{{ Storage::disk('public')->url($photo->file_path) }}" alt="Photo" class="student-photo">
        @else
            <div class="student-photo-placeholder">Photo</div>
        @endif
    </div>

    {{-- ── Student Information ── --}}
    <div class="section-title">Student Information</div>
    <div class="info-grid" style="margin-top:0; border-top:none;">
        <div class="cell">Student Name</div>
        <div class="cell">{{ $student->full_name }}</div>

        <div class="cell">Father's Name</div>
        <div class="cell">{{ $student->father_name ?? '—' }}</div>

        <div class="cell">Class</div>
        <div class="cell">{{ $studentClass->name ?? '—' }}</div>

        <div class="cell">Section</div>
        <div class="cell">{{ $student->section->name ?? '—' }}</div>

        <div class="cell">Roll No.</div>
        <div class="cell">{{ $student->roll_no ?? '—' }}</div>

        <div class="cell">Admission No.</div>
        <div class="cell">{{ $student->admission_no ?? '—' }}</div>

        <div class="cell">Date of Birth</div>
        <div class="cell">{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</div>

        <div class="cell">Gender</div>
        <div class="cell">{{ ucfirst($student->gender ?? '—') }}</div>
    </div>

    {{-- ── Subject Marks ── --}}
    <div class="section-title">Subject-wise Result</div>
    <table>
        <thead>
            <tr>
                <th style="width:32px">#</th>
                <th style="text-align:left">Subject</th>
                <th>Total<br>Marks</th>
                <th>Pass<br>Marks</th>
                <th>Obtained<br>Marks</th>
                <th>%</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                @php
                    $subPct   = $result->total_marks > 0 ? ($result->obtained_marks / $result->total_marks) * 100 : 0;
                    $subGrade = match(true) {
                        $subPct >= 90 => 'A+',
                        $subPct >= 80 => 'A',
                        $subPct >= 70 => 'B',
                        $subPct >= 60 => 'C',
                        $subPct >= 50 => 'D',
                        default       => 'F',
                    };
                    $isPassing = $result->obtained_marks >= $result->passing_marks;
                    $gradeClass = 'grade-' . str_replace('+', '-plus', $subGrade);
                @endphp
                <tr class="{{ !$isPassing ? 'fail-row' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align:left">{{ $result->subject->name ?? '—' }}</td>
                    <td>{{ $result->total_marks }}</td>
                    <td>{{ $result->passing_marks }}</td>
                    <td><strong>{{ $result->obtained_marks }}</strong></td>
                    <td>{{ round($subPct, 1) }}%</td>
                    <td><span class="grade-pill {{ $gradeClass }}">{{ $subGrade }}</span></td>
                    <td>{{ $isPassing ? 'Pass' : 'Fail' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Overall Summary ── --}}
    <div class="section-title">Overall Result</div>
    <div class="summary-grid" style="margin-top:10px;">
        <div class="summary-box">
            <div class="label">Total Marks</div>
            <div class="value">{{ $totalObtained }} / {{ $totalMarks }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Percentage</div>
            <div class="value">{{ round($percentage, 1) }}%</div>
        </div>
        <div class="summary-box">
            <div class="label">Grade</div>
            <div class="value">{{ $grade }}</div>
        </div>
        <div class="summary-box {{ $status === 'Pass' ? 'pass' : 'fail' }}">
            <div class="label">Result</div>
            <div class="value">{{ $status }}</div>
        </div>
    </div>

    @if($rank)
    <div class="summary-grid" style="grid-template-columns: repeat(2,1fr); margin-bottom:14px;">
        <div class="summary-box">
            <div class="label">Class Rank</div>
            <div class="value">{{ $rank }} / {{ $totalInClass }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Position</div>
            <div class="value">
                {{ $rank == 1 ? '1st' : ($rank == 2 ? '2nd' : ($rank == 3 ? '3rd' : $rank . 'th')) }}
            </div>
        </div>
    </div>
    @endif

    {{-- ── Attendance Summary ── --}}
    <div class="section-title">Attendance Summary ({{ $exam->academic_year }})</div>
    <div class="attendance-row" style="margin-top:10px;">
        <div class="att-box">
            <div class="label">Total Days</div>
            <div class="value">{{ $attendanceSummary->total_days ?? 0 }}</div>
        </div>
        <div class="att-box" style="border-color:#28a745;">
            <div class="label">Present</div>
            <div class="value" style="color:#155724;">{{ $attendanceSummary->present ?? 0 }}</div>
        </div>
        <div class="att-box" style="border-color:#dc3545;">
            <div class="label">Absent</div>
            <div class="value" style="color:#721c24;">{{ $attendanceSummary->absent ?? 0 }}</div>
        </div>
        <div class="att-box" style="border-color:#ffc107;">
            <div class="label">Leave</div>
            <div class="value" style="color:#856404;">{{ $attendanceSummary->leave ?? 0 }}</div>
        </div>
        <div class="att-box" style="border-color:#17a2b8;">
            <div class="label">Late</div>
            <div class="value" style="color:#0c5460;">{{ $attendanceSummary->late ?? 0 }}</div>
        </div>
    </div>

    {{-- ── Remarks ── --}}
    <div class="section-title">Remarks</div>
    <div class="remarks-box" style="margin-top:0;">
        &nbsp;
    </div>

    {{-- ── Signatures ── --}}
    <div class="signatures">
        <div class="sig-block">
            <div class="sig-line">Class Teacher</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">Examination Controller</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">Principal</div>
        </div>
    </div>

</div>

{{-- ── Print / Back Buttons ── --}}
<div class="no-print">
    <a href="{{ route('results.show', [$exam->id, $student->id]) }}" class="btn-back">← Back</a>
    <button class="btn-print" onclick="window.print()">Print Report Card</button>
</div>

</body>
</html>
