<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; margin: 40px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; }
    .school-name { font-size: 24px; font-weight: bold; }
    .cert-title { font-size: 18px; text-transform: uppercase; letter-spacing: 3px; margin: 20px 0; color: #5c5c5c; }
    .content { line-height: 2; font-size: 14px; text-align: justify; }
    .student-name { font-size: 16px; font-weight: bold; text-decoration: underline; }
    .footer { margin-top: 60px; display: flex; justify-content: space-between; }
    .sig-block { text-align: center; width: 200px; }
    .sig-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; font-size: 12px; }
</style>
</head>
<body>
<div class="header">
    <div class="school-name">{{ \App\Models\SchoolSetting::get('school_name', config('app.name')) }}</div>
    <div style="font-size:13px; color:#666;">{{ \App\Models\SchoolSetting::get('school_address', '') }}</div>
</div>

<div style="text-align:center;">
    <div class="cert-title">Bonafide Certificate</div>
</div>

<div class="content">
    <p>This is to certify that <span class="student-name">{{ $student->first_name }} {{ $student->last_name }}</span>,
    son/daughter of <strong>{{ $student->father_name }}</strong>,
    is a <em>bonafide</em> student of Class <strong>{{ $student->studentClass->name ?? 'N/A' }}</strong>
    @if($student->section) Section <strong>{{ $student->section->name }}</strong> @endif
    at this institution for the current academic year.</p>

    <p>His/Her Admission No. is <strong>{{ $student->admission_no }}</strong>
    and Roll No. is <strong>{{ $student->roll_no ?? 'N/A' }}</strong>.</p>

    <p>This certificate is issued on his/her request for the purpose of
    @if(request()->query('purpose')) {{ request()->query('purpose') }} @else whatever it may serve @endif.</p>
</div>

<div style="margin-top:20px;">
    <table>
        <tr><td style="padding-right:20px;font-weight:bold;">Date of Birth:</td><td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</td></tr>
        <tr><td style="font-weight:bold;">Date of Issue:</td><td>{{ now()->format('d M Y') }}</td></tr>
    </table>
</div>

<div class="footer">
    <div class="sig-block">
        <div class="sig-line">Class Teacher</div>
    </div>
    <div class="sig-block">
        <div class="sig-line">Principal / Head</div>
    </div>
</div>
</body>
</html>
