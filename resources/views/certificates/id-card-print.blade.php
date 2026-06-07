<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; }
    .card {
        width: 85.6mm; height: 54mm;
        border: 2px solid #1a5276;
        border-radius: 6px;
        overflow: hidden;
        page-break-inside: avoid;
    }
    .card-header {
        background: #1a5276;
        color: white;
        text-align: center;
        padding: 4px 8px;
        font-size: 9px;
        font-weight: bold;
    }
    .card-body {
        display: flex;
        padding: 6px 8px;
        gap: 8px;
    }
    .photo-area {
        width: 25mm;
        height: 32mm;
        border: 1px solid #ddd;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        color: #999;
        flex-shrink: 0;
        border-radius: 3px;
        overflow: hidden;
    }
    .info-area { flex: 1; font-size: 8px; }
    .info-area .name { font-size: 10px; font-weight: bold; color: #1a5276; margin-bottom: 3px; }
    .info-row { margin-bottom: 2px; color: #444; }
    .info-label { font-weight: bold; color: #666; }
    .qr-area {
        width: 18mm;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        flex-shrink: 0;
        padding-bottom: 2px;
    }
    .qr-area img { width: 17mm; height: 17mm; }
    .card-footer {
        background: #1a5276;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px 8px;
        font-size: 8px;
    }
</style>
</head>
<body>
<div class="card">
    <div class="card-header">
        {{ \App\Models\SchoolSetting::get('school_name', config('app.name')) }} — STUDENT ID CARD
    </div>
    <div class="card-body">
        <div class="photo-area">
            @if($student->profilePhoto)
                <img src="{{ storage_path('app/public/' . $student->profilePhoto->file_path) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                Photo
            @endif
        </div>
        <div class="info-area">
            <div class="name">{{ $student->first_name }} {{ $student->last_name }}</div>
            <div class="info-row"><span class="info-label">Adm No:</span> {{ $student->admission_no }}</div>
            <div class="info-row"><span class="info-label">Class:</span> {{ $student->studentClass->name ?? 'N/A' }} @if($student->section)– {{ $student->section->name }}@endif</div>
            <div class="info-row"><span class="info-label">Roll No:</span> {{ $student->roll_no ?? 'N/A' }}</div>
            <div class="info-row"><span class="info-label">Father:</span> {{ $student->father_name }}</div>
            <div class="info-row"><span class="info-label">Phone:</span> {{ $student->guardian_phone ?? 'N/A' }}</div>
        </div>
        @if(!empty($qrDataUri))
        <div class="qr-area">
            <img src="{{ $qrDataUri }}" alt="QR">
        </div>
        @endif
    </div>
    <div class="card-footer">
        <span>Valid: {{ date('Y') }} – {{ date('Y') + 1 }}</span>
        <span style="font-size:7px; font-style:italic;">If found, please return to school</span>
    </div>
</div>
</body>
</html>
