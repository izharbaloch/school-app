<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Result Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #000;
        }

        .card {
            max-width: 900px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info-table,
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td,
        .marks-table th,
        .marks-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            margin: 5px 0;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 40%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h2>Student Result Card</h2>
            <p><strong>{{ $exam->name }}</strong></p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Student Name</strong></td>
                <td>{{ $student->full_name ?: $student->name ?? '-' }}</td>
                <td><strong>Roll No</strong></td>
                <td>{{ $student->roll_no ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Class</strong></td>
                <td>{{ $studentClass->name ?? '-' }}</td>
                <td><strong>Section</strong></td>
                <td>{{ $student->section->name ?? '-' }}</td>
            </tr>
        </table>

        <table class="marks-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Total</th>
                    <th>Passing</th>
                    <th>Obtained</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $result->subject->name ?? '-' }}</td>
                        <td>{{ $result->total_marks }}</td>
                        <td>{{ $result->passing_marks }}</td>
                        <td>{{ $result->obtained_marks }}</td>
                        <td>{{ $result->is_pass ? 'Pass' : 'Fail' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <p><strong>Total Obtained:</strong> {{ $totalObtained }}</p>
            <p><strong>Total Marks:</strong> {{ $totalMarks }}</p>
            <p><strong>Percentage:</strong> {{ round($percentage, 2) }}%</p>
            <p><strong>Grade:</strong> {{ $grade }}</p>
            <p><strong>Status:</strong> {{ $status }}</p>
        </div>

        <div class="signature">
            <div>Class Teacher Signature</div>
            <div>Principal Signature</div>
        </div>

        <div class="no-print">
            <button onclick="window.print()">Print Result Card</button>
        </div>
    </div>
</body>

</html>
