<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentPromotion;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExamMarkEntry extends Component
{
    public $exam_id = '';
    public $student_class_id = '';
    public $section_id = '';

    public $students = [];
    public $subjects = [];

    public $is_promoted = false; // 🔥 NEW

    protected $queryString = [
        'exam_id' => ['except' => ''],
        'student_class_id' => ['except' => ''],
        'section_id' => ['except' => ''],
    ];

    public function mount()
    {
        $this->students = [];

        if ($this->exam_id && $this->student_class_id) {
            $this->loadData();
        }
    }

    public function updatedStudentClassId()
    {
        $this->section_id = '';
        $this->students = [];

        if ($this->exam_id) {
            $this->loadData();
        }
    }

    public function updatedSectionId()
    {
        $this->students = [];

        if ($this->exam_id && $this->student_class_id) {
            $this->loadData();
        }
    }

    public function updatedExamId()
    {
        $this->students = [];

        if ($this->student_class_id) {
            $this->loadData();
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
        if (!$this->student_class_id) return collect();

        return Section::whereHas('classes', function ($q) {
            $q->where('student_classes.id', $this->student_class_id);
        })->get();
    }

    public function loadData()
    {
        $this->subjects = Subject::whereHas('classes', function ($q) {
            $q->where('student_classes.id', $this->student_class_id);
        })->get();

        $students = Student::where('student_class_id', $this->student_class_id)
            ->when($this->section_id, fn($q) => $q->where('section_id', $this->section_id))
            ->orderBy('roll_no')
            ->get();

        $results = ExamResult::where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy(fn($item) => $item->student_id . '_' . $item->subject_id);

        $this->students = $students->map(function ($student) use ($results) {

            $subjectData = [];

            foreach ($this->subjects as $subject) {

                $key = $student->id . '_' . $subject->id;
                $existing = $results[$key][0] ?? null;

                $subjectData[$subject->id] = [
                    'obtained_marks' => $existing->obtained_marks ?? 0,
                    'remarks' => $existing->remarks ?? '',
                ];
            }

            return [
                'student_id' => $student->id,
                'roll_no' => $student->roll_no,
                'name' => $student->full_name,
                'subjects' => $subjectData,
            ];
        })->toArray();
    }

    public function save()
    {
        DB::transaction(function () {

            $exam = Exam::find($this->exam_id);

            foreach ($this->students as $student) {

                $isFail = false;

                foreach ($student['subjects'] as $subject_id => $marks) {

                    $subject = Subject::find($subject_id);

                    if ($marks['obtained_marks'] > $subject->total_marks) {
                        $this->addError('error', "Marks exceed for {$subject->name}");
                        return;
                    }

                    ExamResult::updateOrCreate(
                        [
                            'exam_id' => $this->exam_id,
                            'student_id' => $student['student_id'],
                            'subject_id' => $subject_id,
                            'student_class_id' => $this->student_class_id,
                        ],
                        [
                            'student_class_id' => $this->student_class_id,
                            'obtained_marks' => $marks['obtained_marks'],
                            'total_marks' => $subject->total_marks,
                            'passing_marks' => $subject->passing_marks,
                            'remarks' => $marks['remarks'],
                        ]
                    );

                    if ($marks['obtained_marks'] < $subject->passing_marks) {
                        $isFail = true;
                    }
                }

                // 🔥 ONLY IF CHECKBOX TRUE
                if ($this->is_promoted && $exam && stripos($exam->name, 'final') !== false) {

                    $studentModel = Student::find($student['student_id']);

                    if ($isFail) {
                        $studentModel->update([
                            'is_failed' => 1,
                        ]);
                    } else {

                        $nextClass = StudentClass::where('id', '>', $studentModel->student_class_id)
                            ->orderBy('id')
                            ->first();

                        if ($nextClass) {

                            StudentPromotion::create([
                                'student_id' => $studentModel->id,
                                'from_class_id' => $studentModel->student_class_id,
                                'to_class_id' => $nextClass->id,
                                'from_section_id' => $studentModel->section_id,
                                'to_section_id' => $studentModel->section_id,
                                'exam_id' => $this->exam_id,
                            ]);

                            $studentModel->update([
                                'student_class_id' => $nextClass->id,
                                'status' => 'pass_out',
                                'is_failed' => 0,
                            ]);
                        }
                    }
                }
            }
        });

        session()->flash('success', 'Marks saved successfully ✅');

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.exams.exam-mark-entry', [
            'exams' => $this->exams,
            'classes' => $this->classes,
            'sections' => $this->sections,
        ]);
    }
}
