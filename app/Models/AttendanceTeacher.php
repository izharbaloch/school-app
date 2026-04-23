<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceTeacher extends Model
{
    const PRESENT = 'present';
    const ABSENT  = 'absent';
    const LEAVE   = 'leave';
    const LATE    = 'late';

    protected $fillable = [
        'attendance_date_id',
        'teacher_id',
        'status',
        'remarks',
    ];

    public static function statuses(): array
    {
        return [
            self::PRESENT => 'Present',
            self::ABSENT  => 'Absent',
            self::LEAVE   => 'Leave',
            self::LATE    => 'Late',
        ];
    }

    public function attendanceDate()
    {
        return $this->belongsTo(TeacherAttendanceDate::class, 'attendance_date_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
