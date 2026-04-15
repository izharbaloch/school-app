<?php

namespace App\Http\Controllers;

use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('attendances.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load([
            'studentClass:id,name',
            'section:id,name',
            'takenBy:id,name',
            'attendanceStudents:id,attendance_id,student_id,status,remarks',
            'attendanceStudents.student:id,roll_no,first_name,last_name',
        ]);

        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        abort(404);
    }
}
