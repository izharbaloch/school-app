<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\SchoolSetting;
use App\Models\StudentClass;

class ExamScheduleController extends Controller
{
    public function index()
    {
        return view('exams.schedule');
    }

    public function print()
    {
        $examId  = request('exam_id');
        $classId = request('class_id');

        $exam  = Exam::findOrFail($examId);
        $class = StudentClass::findOrFail($classId);

        $schedules = ExamSchedule::with(['subject:id,name', 'section:id,name'])
            ->where('exam_id', $examId)
            ->where('student_class_id', $classId)
            ->where('status', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $school = [
            'name'    => SchoolSetting::get('school_name', config('app.name')),
            'address' => SchoolSetting::get('school_address', ''),
            'phone'   => SchoolSetting::get('school_phone', ''),
        ];

        return view('exams.schedule-print', compact('exam', 'class', 'schedules', 'school'));
    }
}
