<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; margin: 40px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #c00; padding-bottom: 15px; margin-bottom: 30px; }
    .school-name { font-size: 24px; font-weight: bold; }
    .cert-title { font-size: 18px; text-transform: uppercase; letter-spacing: 3px; margin: 20px 0; color: #c00; }
    table.info { width: 100%; border-collapse: collapse; margin: 20px 0; }
    table.info tr td { padding: 8px 10px; border: 1px solid #ddd; font-size: 13px; }
    table.info tr td:first-child { background: #f5f5f5; font-weight: bold; width: 40%; }
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
    <div class="cert-title">School Leaving Certificate</div>
</div>

<table class="info">
    <tr><td>Student Name</td><td>{{ $student->first_name }} {{ $student->last_name }}</td></tr>
    <tr><td>Father's Name</td><td>{{ $student->father_name }}</td></tr>
    <tr><td>Mother's Name</td><td>{{ $student->mother_name ?? 'N/A' }}</td></tr>
    <tr><td>Date of Birth</td><td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</td></tr>
    <tr><td>Admission No.</td><td>{{ $student->admission_no }}</td></tr>
    <tr><td>Roll No.</td><td>{{ $student->roll_no ?? 'N/A' }}</td></tr>
    <tr><td>Class</td><td>{{ $student->studentClass->name ?? 'N/A' }} @if($student->section) – Section {{ $student->section->name }} @endif</td></tr>
    <tr><td>Date of Admission</td><td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A' }}</td></tr>
    <tr><td>Date of Leaving</td><td>{{ now()->format('d M Y') }}</td></tr>
    <tr><td>Reason for Leaving</td><td>As requested by parent/guardian</td></tr>
    <tr><td>Progress at Leaving</td><td>Satisfactory</td></tr>
    <tr><td>Character</td><td>Good</td></tr>
    <tr><td>Fee Status</td><td>All dues cleared</td></tr>
</table>

<p style="font-size:13px; margin-top:20px;">
    This is to certify that the above mentioned student has left this school on the date mentioned above.
    He/She was a student of good conduct and is hereby issued this Leaving Certificate.
</p>

<div class="footer">
    <div class="sig-block">
        <div class="sig-line">Class Teacher</div>
    </div>
    <div class="sig-block">
        <div class="sig-line">Principal / Head</div>
    </div>
</div>

<div style="margin-top:20px; font-size:11px; color:#888; text-align:right;">
    Issue Date: {{ now()->format('d M Y') }} | LC No: LC-{{ $student->admission_no }}-{{ date('Y') }}
</div>
</body>
</html>
