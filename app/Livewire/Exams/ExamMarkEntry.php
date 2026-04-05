<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamMarkEntry extends Component
{
    public $exam_id = '';
    public $student_class_id = '';
    public $section_id = '';
    public $subject_id = '';

    public $students = [];
    public $subject = null;

    public function rules()
    {
        return [
            'exam_id' => ['required', 'exists:exams,id'],
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'students' => ['required', 'array', 'min:1'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.obtained_marks' => ['required', 'numeric', 'min:0'],
            'students.*.remarks' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'exam_id' => 'exam',
            'student_class_id' => 'class',
            'section_id' => 'section',
            'subject_id' => 'subject',
        ];
    }

    public function mount()
    {
        $this->students = [];
    }

    public function updatedStudentClassId()
    {
        $this->section_id = '';
        $this->subject_id = '';
        $this->students = [];
        $this->subject = null;
    }

    public function updatedSectionId()
    {
        $this->students = [];
        $this->subject = null;

        if ($this->exam_id && $this->student_class_id && $this->subject_id) {
            $this->loadStudentsAndResults();
        }
    }

    public function updatedExamId()
    {
        $this->students = [];
        $this->subject = null;

        if ($this->exam_id && $this->student_class_id && $this->subject_id) {
            $this->loadStudentsAndResults();
        }
    }

    public function updatedSubjectId()
    {
        $this->students = [];
        $this->subject = null;

        if ($this->exam_id && $this->student_class_id && $this->subject_id) {
            $this->loadStudentsAndResults();
        }
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

    public function getSubjectsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        $class = StudentClass::with('subjects')->find($this->student_class_id);

        return $class ? $class->subjects->sortBy('name')->values() : collect();
    }

    public function loadStudentsAndResults()
    {
        if (!$this->exam_id || !$this->student_class_id || !$this->subject_id) {
            $this->students = [];
            $this->subject = null;
            return;
        }

        $studentsQuery = Student::with(['studentClass', 'section'])
            ->where('student_class_id', $this->student_class_id);

        if ($this->section_id) {
            $studentsQuery->where('section_id', $this->section_id);
        }

        $studentCollection = $studentsQuery
            ->orderBy('roll_no')
            ->orderBy('first_name')
            ->get();

        $this->subject = Subject::find($this->subject_id);

        $existingResults = collect();

        if ($studentCollection->count()) {
            $existingResults = ExamResult::where('exam_id', $this->exam_id)
                ->where('subject_id', $this->subject_id)
                ->whereIn('student_id', $studentCollection->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        $this->students = $studentCollection->map(function ($student) use ($existingResults) {
            $existing = $existingResults[$student->id] ?? null;

            return [
                'student_id' => $student->id,
                'roll_no' => $student->roll_no ?? '-',
                'name' => $student->full_name ?: ($student->name ?? '-'),
                'obtained_marks' => $existing->obtained_marks ?? 0,
                'remarks' => $existing->remarks ?? '',
            ];
        })->toArray();
    }

    public function save()
    {
        $this->validate();

        $subject = Subject::findOrFail($this->subject_id);

        foreach ($this->students as $index => $studentData) {
            if ($studentData['obtained_marks'] > $subject->total_marks) {
                $this->addError("students.{$index}.obtained_marks", "Obtained marks cannot be greater than total marks ({$subject->total_marks}).");
                return;
            }
        }

        DB::transaction(function () use ($subject) {
            foreach ($this->students as $studentData) {
                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $this->exam_id,
                        'student_id' => $studentData['student_id'],
                        'subject_id' => $this->subject_id,
                    ],
                    [
                        'obtained_marks' => $studentData['obtained_marks'],
                        'total_marks' => $subject->total_marks,
                        'passing_marks' => $subject->passing_marks,
                        'remarks' => $studentData['remarks'] ?? null,
                    ]
                );
            }
        });

        session()->flash('success', 'Marks saved successfully.');

        $this->loadStudentsAndResults();
    }

    public function render()
    {
        return view('livewire.exams.exam-mark-entry', [
            'exams' => $this->exams,
            'classes' => $this->classes,
            'sections' => $this->sections,
            'subjects' => $this->subjects,
        ]);
    }
}
