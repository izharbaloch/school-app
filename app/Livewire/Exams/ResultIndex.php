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

    // public function getResultsProperty()
    // {
    //     if (!$this->exam_id || !$this->student_class_id) {
    //         return [];
    //     }

    //     $studentsQuery = Student::with([
    //             'studentClass:id,name',
    //             'section:id,name',
    //         ])
    //         ->select('id', 'first_name', 'last_name', 'roll_no', 'student_class_id', 'section_id')
    //         ->where('student_class_id', $this->student_class_id);

    //     if ($this->section_id) {
    //         $studentsQuery->where('section_id', $this->section_id);
    //     }

    //     $students = $studentsQuery
    //         ->orderBy('roll_no')
    //         ->orderBy('first_name')
    //         ->get();

    //     // Get all exam results in one query for efficiency
    //     $examResults = ExamResult::with('subject:id,name')
    //         ->where('exam_id', $this->exam_id)
    //         ->whereIn('student_id', $students->pluck('id'))
    //         ->select('id', 'exam_id', 'student_id', 'subject_id', 'obtained_marks', 'total_marks', 'passing_marks')
    //         ->get()
    //         ->groupBy('student_id');

    //     $results = [];

    //     foreach ($students as $student) {
    //         $studentResults = $examResults->get($student->id, collect());
    //         $totalObtained = $studentResults->sum('obtained_marks');
    //         $totalMarks = $studentResults->sum('total_marks');
    //         $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
    //         $failedSubjects = $studentResults->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

    //         $results[] = [
    //             'student' => $student,
    //             'total_obtained' => $totalObtained,
    //             'total_marks' => $totalMarks,
    //             'percentage' => round($percentage, 2),
    //             'grade' => $this->getGrade($percentage),
    //             'status' => $failedSubjects > 0 ? 'Fail' : 'Pass',
    //         ];
    //     }

    //     return $results;
    // }

    public function getResultsProperty()
    {
        if (!$this->exam_id || !$this->student_class_id) {
            return [];
        }

        // 🔥 ONLY THOSE STUDENTS WHO HAVE RESULT IN THIS CLASS
        $studentIds = ExamResult::where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
            ->pluck('student_id')
            ->unique();

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

        // 🔥 RESULTS
        $examResults = ExamResult::with('subject:id,name')
            ->where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
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

    public function render()
    {
        return view('livewire.exams.result-index', [
            'exams' => $this->exams,
            'classes' => $this->classes,
            'sections' => $this->sections,
            'results' => $this->results,
        ]);
    }
}
