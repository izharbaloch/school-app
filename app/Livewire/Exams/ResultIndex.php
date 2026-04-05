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

    public function getResultsProperty()
    {
        if (!$this->exam_id || !$this->student_class_id) {
            return [];
        }

        $studentsQuery = Student::with(['studentClass', 'section'])
            ->where('student_class_id', $this->student_class_id);

        if ($this->section_id) {
            $studentsQuery->where('section_id', $this->section_id);
        }

        $students = $studentsQuery
            ->orderBy('roll_no')
            ->orderBy('first_name')
            ->get();

        $results = [];

        foreach ($students as $student) {
            $examResults = ExamResult::with('subject')
                ->where('exam_id', $this->exam_id)
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
