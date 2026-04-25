<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Livewire\Component;

class ResultIndex extends Component
{
    public $exam_id = '';
    public $student_class_id = '';
    public $section_id = '';

    protected $queryString = [
        'exam_id' => ['except' => ''],
        'student_class_id' => ['except' => ''],
        'section_id' => ['except' => ''],
    ];

    public function updatedStudentClassId()
    {
        $this->section_id = '';
    }

    public function getExamsProperty()
    {
        return Exam::where('status', true)->orderBy('name')->get();
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        return Section::whereHas('classes', function ($query) {
            $query->where('student_classes.id', $this->student_class_id);
        })->orderBy('name')->get();
    }

    private function getGrade($percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        return 'F';
    }

    public function getResultsProperty()
    {
        if (!$this->exam_id || !$this->student_class_id) {
            return [];
        }

        $exam = Exam::find($this->exam_id);

        if (!$exam) {
            return [];
        }

        // 🔥 STEP 1: Get result students only from same academic year + class
        $studentIds = ExamResult::where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
            ->where('academic_year', $exam->academic_year)
            ->pluck('student_id')
            ->unique();

        // 🔥 STEP 2: Students load
        $students = Student::with([
            'studentClass:id,name',
            'section:id,name',
        ])
            ->select('id', 'first_name', 'last_name', 'roll_no', 'student_class_id', 'section_id')
            ->whereIn('id', $studentIds);

        if ($this->section_id) {
            $students->where('section_id', $this->section_id);
        }

        $students = $students
            ->orderBy('roll_no')
            ->orderBy('first_name')
            ->get();

        // 🔥 STEP 3: Results load (SYNC FIX)
        $examResults = ExamResult::with('subject:id,name')
            ->where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
            ->where('academic_year', $exam->academic_year)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        $results = [];

        foreach ($students as $student) {

            $studentResults = $examResults->get($student->id, collect());

            $totalObtained = $studentResults->sum('obtained_marks');
            $totalMarks = $studentResults->sum('total_marks');

            $percentage = $totalMarks > 0
                ? ($totalObtained / $totalMarks) * 100
                : 0;

            $failedSubjects = $studentResults
                ->filter(fn($item) => $item->obtained_marks < $item->passing_marks)
                ->count();

            $results[] = [
                'student' => $student,
                'total_obtained' => $totalObtained,
                'total_marks' => $totalMarks,
                'percentage' => round($percentage, 2),
                'grade' => $this->getGrade($percentage),
                'status' => $failedSubjects > 0 ? 'Fail' : 'Pass',
            ];
        }

        return $results;
    }

    public function getStatsProperty()
    {
        $results = collect($this->results);
        
        if ($results->isEmpty()) {
            return [
                'pass_percentage' => 0,
                'fail_percentage' => 0,
                'first_position' => '-',
                'second_position' => '-',
                'third_position' => '-',
            ];
        }

        $totalStudents = $results->count();
        $passedCount = $results->where('status', 'Pass')->count();
        $failedCount = $results->where('status', 'Fail')->count();

        $passPercentage = $totalStudents > 0 ? round(($passedCount / $totalStudents) * 100, 1) : 0;
        $failPercentage = $totalStudents > 0 ? round(($failedCount / $totalStudents) * 100, 1) : 0;

        $ranked = $results->where('status', 'Pass')
            ->sortByDesc('percentage')
            ->values();

        $firstPosition = $ranked->get(0) ? ($ranked->get(0)['student']->full_name ?: ($ranked->get(0)['student']->name ?? '-')) : '-';
        $secondPosition = $ranked->get(1) ? ($ranked->get(1)['student']->full_name ?: ($ranked->get(1)['student']->name ?? '-')) : '-';
        $thirdPosition = $ranked->get(2) ? ($ranked->get(2)['student']->full_name ?: ($ranked->get(2)['student']->name ?? '-')) : '-';

        return [
            'pass_percentage' => $passPercentage,
            'fail_percentage' => $failPercentage,
            'first_position' => $firstPosition,
            'second_position' => $secondPosition,
            'third_position' => $thirdPosition,
        ];
    }

    public function render()
    {
        return view('livewire.exams.result-index', [
            'exams' => $this->exams,
            'classes' => $this->classes,
            'sections' => $this->sections,
            'results' => $this->results,
            'stats' => $this->stats,
        ]);
    }
}
