<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::where('status', true)->orderBy('name')->get();
        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        $selectedExamId = $request->exam_id;
        $selectedClassId = $request->student_class_id;
        $selectedSectionId = $request->section_id;

        $students = collect();
        $results = [];

        if ($selectedExamId && $selectedClassId) {
            $studentsQuery = Student::with(['studentClass', 'section'])
                ->where('student_class_id', $selectedClassId);

            if ($selectedSectionId) {
                $studentsQuery->where('section_id', $selectedSectionId);
            }

            $students = $studentsQuery->orderBy('roll_no')->orderBy('first_name')->get();

            foreach ($students as $student) {
                $examResults = ExamResult::with('subject')
                    ->where('exam_id', $selectedExamId)
                    ->where('student_id', $student->id)
                    ->get();

                $totalObtained = $examResults->sum('obtained_marks');
                $totalMarks = $examResults->sum('total_marks');
                $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
                $failedSubjects = $examResults->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

                $results[] = [
                    'student' => $student,
                    'total_obtained' => $totalObtained,
                    'total_marks' => $totalMarks,
                    'percentage' => round($percentage, 2),
                    'grade' => $this->getGrade($percentage),
                    'status' => $failedSubjects > 0 ? 'Fail' : 'Pass',
                ];
            }
        }

        return view('results.index', compact(
            'exams',
            'classes',
            'sections',
            'selectedExamId',
            'selectedClassId',
            'selectedSectionId',
            'results'
        ));
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
