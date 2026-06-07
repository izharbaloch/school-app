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
    .cert-no { font-size: 12px; color: #888; }
    table.info td { padding: 4px 15px 4px 0; font-size: 13px; }
    table.info td:first-child { font-weight: bold; color: #555; }
</style>
</head>
<body>
<div class="header">
    <div class="school-name">{{ \App\Models\SchoolSetting::get('school_name', config('app.name')) }}</div>
    <div style="font-size:13px; color:#666;">{{ \App\Models\SchoolSetting::get('school_address', '') }}</div>
    <div style="font-size:13px; color:#666;">Phone: {{ \App\Models\SchoolSetting::get('school_phone', '') }}</div>
</div>

<div style="text-align:center;">
    <div class="cert-title">Character Certificate</div>
</div>

<div class="content">
    <p>This is to certify that <span class="student-name">{{ $student->first_name }} {{ $student->last_name }}</span>,
    son/daughter of <strong>{{ $student->father_name }}</strong>,
    bearing Admission No. <strong>{{ $student->admission_no }}</strong>,
    was a student of Class <strong>{{ $student->studentClass->name ?? 'N/A' }}</strong>
    @if($student->section) Section <strong>{{ $student->section->name }}</strong> @endif
    at this institution.</p>

    <p>During his/her stay at this institution, he/she was found to be of <strong>good moral character</strong>
    and conducted himself/herself in a praiseworthy manner. He/she was regular in studies and obedient to the
    school discipline.</p>

    <p>This certificate is issued on his/her request for whatever purpose it may serve.</p>
</div>

<table class="info" style="margin-top:20px;">
    <tr><td>Date of Birth:</td><td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</td></tr>
    <tr><td>Roll No:</td><td>{{ $student->roll_no ?? 'N/A' }}</td></tr>
    <tr><td>Admission Date:</td><td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A' }}</td></tr>
    <tr><td>Issue Date:</td><td>{{ now()->format('d M Y') }}</td></tr>
</table>

<div class="footer">
    <div class="sig-block">
        <div class="sig-line">Class Teacher</div>
    </div>
    <div class="sig-block">
        <div class="sig-line">Principal</div>
    </div>
</div>

<div style="margin-top:20px; text-align:right;" class="cert-no">Cert No: CC-{{ $student->admission_no }}-{{ date('Y') }}</div>
</body>
</html>
