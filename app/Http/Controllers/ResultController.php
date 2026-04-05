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
        $student->load(['studentClass', 'section']);

        $results = ExamResult::with('subject')
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->get();

        $totalObtained = $results->sum('obtained_marks');
        $totalMarks = $results->sum('total_marks');
        $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
        $failedSubjects = $results->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

        $grade = $this->getGrade($percentage);
        $status = $failedSubjects > 0 ? 'Fail' : 'Pass';

        return view('results.show', compact(
            'exam',
            'student',
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
        $student->load(['studentClass', 'section']);

        $results = ExamResult::with('subject')
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->get();

        $totalObtained = $results->sum('obtained_marks');
        $totalMarks = $results->sum('total_marks');
        $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
        $failedSubjects = $results->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

        $grade = $this->getGrade($percentage);
        $status = $failedSubjects > 0 ? 'Fail' : 'Pass';

        return view('results.print', compact(
            'exam',
            'student',
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
