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
    public $student_id = '';

    public $students = [];
    public $subjects = [];
    public $filteredStudents = [];

    public $is_promoted = false;

    protected $queryString = [
        'exam_id' => ['except' => ''],
        'student_class_id' => ['except' => ''],
        'section_id' => ['except' => ''],
        'student_id' => ['except' => ''],
    ];

    public function mount()
    {
        if ($this->exam_id && $this->student_class_id) {
            $this->loadData();
        }
    }

    public function updatedStudentClassId()
    {
        $this->reset(['section_id', 'student_id', 'students']);
        $this->loadData();
    }

    public function updatedSectionId()
    {
        $this->reset(['student_id', 'students']);
        $this->loadData();
    }

    public function updatedStudentId()
    {
        $this->students = [];
        $this->loadData();
    }

    public function updatedExamId()
    {
        $this->students = [];
        $this->loadData();
    }

    public function getExamsProperty()
    {
        return Exam::where('status', true)->get();
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
        $exam = Exam::find($this->exam_id);
        if (!$exam) return;

        // Subjects
        $this->subjects = Subject::whereHas('classes', function ($q) {
            $q->where('student_classes.id', $this->student_class_id);
        })->get();

        // Students dropdown
        $this->filteredStudents = Student::where('student_class_id', $this->student_class_id)
            ->when($this->section_id, fn($q) => $q->where('section_id', $this->section_id))
            ->orderBy('roll_no')
            ->get();

        // Students table
        $students = Student::where('student_class_id', $this->student_class_id)
            ->when($this->section_id, fn($q) => $q->where('section_id', $this->section_id))
            ->when($this->student_id, fn($q) => $q->where('id', $this->student_id))
            ->orderBy('roll_no')
            ->get();

        // Results (IMPORTANT FIX)
        $results = ExamResult::where('exam_id', $this->exam_id)
            ->where('student_class_id', $this->student_class_id)
            ->where('academic_year', $exam->academic_year)
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
            if (!$exam) return;

            // preload subjects (optimization)
            $subjects = Subject::whereIn('id', collect($this->subjects)->pluck('id'))->get()->keyBy('id');

            foreach ($this->students as $student) {

                $isFail = false;

                foreach ($student['subjects'] as $subject_id => $marks) {

                    $subject = $subjects[$subject_id] ?? null;
                    if (!$subject) continue;

                    // 🔥 SMART CHECK (NO OVERWRITE ISSUE)
                    $existingResult = ExamResult::where('exam_id', $this->exam_id)
                        ->where('student_id', $student['student_id'])
                        ->where('subject_id', $subject_id)
                        ->where('academic_year', $exam->academic_year)
                        ->where('student_class_id', $this->student_class_id)
                        ->first();

                    if ($existingResult) {
                        // UPDATE
                        $existingResult->update([
                            'obtained_marks' => $marks['obtained_marks'],
                            'total_marks' => $subject->total_marks,
                            'passing_marks' => $subject->passing_marks,
                            'remarks' => $marks['remarks'],
                        ]);
                    } else {
                        // CREATE NEW (history safe)
                        ExamResult::create([
                            'exam_id' => $this->exam_id,
                            'student_id' => $student['student_id'],
                            'subject_id' => $subject_id,
                            'student_class_id' => $this->student_class_id,
                            'academic_year' => $exam->academic_year,
                            'obtained_marks' => $marks['obtained_marks'],
                            'total_marks' => $subject->total_marks,
                            'passing_marks' => $subject->passing_marks,
                            'remarks' => $marks['remarks'],
                        ]);
                    }

                    // Fail check
                    if ($marks['obtained_marks'] < $subject->passing_marks) {
                        $isFail = true;
                    }
                }

                // 🔥 PROMOTION LOGIC (FINAL TERM ONLY)
                if ($this->is_promoted && stripos($exam->name, 'final') !== false) {

                    $studentModel = Student::find($student['student_id']);
                    if (!$studentModel) continue;

                    if ($isFail) {
                        $studentModel->update(['is_failed' => 1]);
                    } else {

                        $nextClass = StudentClass::where('id', '>', $studentModel->student_class_id)
                            ->orderBy('id')
                            ->first();

                        if ($nextClass) {

                            // prevent duplicate promotion
                            $alreadyPromoted = StudentPromotion::where('student_id', $studentModel->id)
                                ->where('exam_id', $this->exam_id)
                                ->exists();

                            if (!$alreadyPromoted) {

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
                                    // 'status' => 'pass_out',
                                    'is_failed' => 0,
                                ]);
                            }
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
