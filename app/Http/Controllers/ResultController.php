<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;

class ResultController extends Controller
{
    public function index()
    {
        return view('results.index');
    }

    public function show(Exam $exam, Student $student)
    {
        $student->load('section:id,name');

        $results = ExamResult::with(['subject:id,name', 'studentClass:id,name'])
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->select('id', 'exam_id', 'student_id', 'subject_id', 'student_class_id', 'obtained_marks', 'total_marks', 'passing_marks')
            ->get();

        // Get the class from exam results (the class during exam time)
        $studentClass = $results->first()?->studentClass;

        $totalObtained = $results->sum('obtained_marks');
        $totalMarks = $results->sum('total_marks');
        $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
        $failedSubjects = $results->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

        $grade = $this->getGrade($percentage);
        $status = $failedSubjects > 0 ? 'Fail' : 'Pass';

        return view('results.show', compact(
            'exam',
            'student',
            'studentClass',
            'results',
            'totalObtained',
            'totalMarks',
            'percentage',
            'grade',
            'status'
        ));
    }

    public function print(Exam $exam, Student $student)
    {
        $student->load('section:id,name');

        $results = ExamResult::with(['subject:id,name', 'studentClass:id,name'])
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->select('id', 'exam_id', 'student_id', 'subject_id', 'student_class_id', 'obtained_marks', 'total_marks', 'passing_marks')
            ->get();

        // Get the class from exam results (the class during exam time)
        $studentClass = $results->first()?->studentClass;

        $totalObtained = $results->sum('obtained_marks');
        $totalMarks = $results->sum('total_marks');
        $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
        $failedSubjects = $results->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

        $grade = $this->getGrade($percentage);
        $status = $failedSubjects > 0 ? 'Fail' : 'Pass';

        return view('results.print', compact(
            'exam',
            'student',
            'studentClass',
            'results',
            'totalObtained',
            'totalMarks',
            'percentage',
            'grade',
            'status'
        ));
    }

    private function getGrade($percentage): string
    {
        if ($percentage >= 90) {
            return 'A+';
        } elseif ($percentage >= 80) {
            return 'A';
        } elseif ($percentage >= 70) {
            return 'B';
        } elseif ($percentage >= 60) {
            return 'C';
        } elseif ($percentage >= 50) {
            return 'D';
        }

        return 'F';
    }
}
