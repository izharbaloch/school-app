<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendanceDate;

class TeacherAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teacher-attendances.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teacher-attendances.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherAttendanceDate $teacherAttendanceDate)
    {
        $teacherAttendanceDate->load([
            'attendanceTeachers:id,attendance_date_id,teacher_id,status,remarks',
            'attendanceTeachers.teacher:id,name,employee_no',
            'takenBy:id,name',
        ]);

        return view('teacher-attendances.show', ['attendance' => $teacherAttendanceDate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherAttendanceDate $teacherAttendanceDate)
    {
        return view('teacher-attendances.edit', ['attendanceDate' => $teacherAttendanceDate]);
    }
}
