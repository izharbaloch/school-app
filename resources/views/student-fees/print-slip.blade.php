<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Fee Slip - {{ $studentFee->slip_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #000;
        }

        .slip {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .info-table,
        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td,
        .amount-table td,
        .amount-table th {
            border: 1px solid #000;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
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

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }

            .slip {
                border: 1px solid #000;
            }
        }
    </style>
</head>

<body>
    <div class="slip">
        <div class="header">
            <h2>School Fee Slip</h2>
            <p><strong>Slip No:</strong> {{ $studentFee->slip_no }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Student Name</strong></td>
                <td>{{ $studentFee->student->full_name ?: $studentFee->student->name ?? '-' }}</td>
                <td><strong>Roll No</strong></td>
                <td>{{ $studentFee->student->roll_no ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Class</strong></td>
                <td>{{ $studentFee->student->studentClass->name ?? '-' }}</td>
                <td><strong>Section</strong></td>
                <td>{{ $studentFee->student->section->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Fee Type</strong></td>
                <td>{{ $studentFee->feeType->name ?? '-' }}</td>
                <td><strong>Month / Year</strong></td>
                <td>{{ $studentFee->month_name }}/{{ $studentFee->year ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Due Date</strong></td>
                <td>{{ $studentFee->due_date?->format('d-m-Y') ?? '-' }}</td>
                <td><strong>Status</strong></td>
                <td>{{ ucfirst($studentFee->status) }}</td>
            </tr>
        </table>

        <table class="amount-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fee Amount</td>
                    <td class="text-right">{{ number_format($studentFee->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">{{ number_format($studentFee->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Fine</td>
                    <td class="text-right">{{ number_format($studentFee->fine, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Payable Amount</strong></td>
                    <td class="text-right"><strong>{{ number_format($studentFee->payable_amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td class="text-right">{{ number_format($studentFee->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Remaining Amount</strong></td>
                    <td class="text-right"><strong>{{ number_format($studentFee->remaining_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Remarks:</strong> {{ $studentFee->remarks ?? '-' }}</p>
        </div>

        <div class="signature">
            <div>Parent Signature</div>
            <div>Authorized Signature</div>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()">Print Slip</button>
        </div>
    </div>
</body>

</html>
