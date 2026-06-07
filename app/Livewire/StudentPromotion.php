<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentPromotion as PromotionModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class StudentPromotion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $from_class_id = '';
    public string $from_section_id = '';
    public string $to_class_id = '';
    public string $to_section_id = '';
    public string $exam_id = '';
    public bool $promote_only_passed = false;

    public array $selectedStudents = [];
    public array $studentList = [];
    public bool $studentsLoaded = false;

    protected function rules(): array
    {
        return [
            'from_class_id'    => ['required', 'exists:student_classes,id'],
            'from_section_id'  => ['nullable', 'exists:sections,id'],
            'to_class_id'      => ['required', 'exists:student_classes,id', 'different:from_class_id'],
            'to_section_id'    => ['nullable', 'exists:sections,id'],
            'exam_id'          => ['nullable', 'exists:exams,id'],
            'selectedStudents' => ['required', 'array', 'min:1'],
        ];
    }

    public function updatedFromClassId(): void
    {
        $this->from_section_id = '';
        $this->studentsLoaded  = false;
        $this->studentList     = [];
        $this->selectedStudents = [];
    }

    public function getFromSectionsProperty()
    {
        if (!$this->from_class_id) return collect();
        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->from_class_id))->get();
    }

    public function getToSectionsProperty()
    {
        if (!$this->to_class_id) return collect();
        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->to_class_id))->get();
    }

    public function loadStudents(): void
    {
        if (!$this->from_class_id) {
            $this->addError('from_class_id', 'Select a class first.');
            return;
        }

        $query = Student::where('student_class_id', $this->from_class_id)
            ->where('status', 'active');

        if ($this->from_section_id) {
            $query->where('section_id', $this->from_section_id);
        }

        $this->studentList = $query->orderBy('first_name')->get()
            ->map(fn($s) => [
                'id'       => $s->id,
                'name'     => $s->first_name . ' ' . $s->last_name,
                'roll_no'  => $s->roll_no,
                'is_failed'=> (bool) $s->is_failed,
            ])
            ->toArray();

        // Auto-select all passing students
        $this->selectedStudents = collect($this->studentList)
            ->when($this->promote_only_passed, fn($c) => $c->where('is_failed', false))
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->studentsLoaded = true;
    }

    public function selectAll(): void
    {
        $this->selectedStudents = collect($this->studentList)->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedStudents = [];
    }

    public function promote(): void
    {
        $this->validate();

        if (empty($this->selectedStudents)) {
            session()->flash('error', 'No students selected for promotion.');
            return;
        }

        DB::transaction(function () {
            foreach ($this->selectedStudents as $studentId) {
                $student = Student::find($studentId);
                if (!$student) continue;

                // Record promotion
                PromotionModel::create([
                    'student_id'      => $student->id,
                    'from_class_id'   => $this->from_class_id,
                    'to_class_id'     => $this->to_class_id,
                    'from_section_id' => $this->from_section_id ?: null,
                    'to_section_id'   => $this->to_section_id ?: null,
                    'exam_id'         => $this->exam_id ?: null,
                ]);

                // Update student record
                $student->update([
                    'student_class_id' => $this->to_class_id,
                    'section_id'       => $this->to_section_id ?: $student->section_id,
                    'is_failed'        => false,
                ]);
            }
        });

        $count = count($this->selectedStudents);
        session()->flash('success', "{$count} student(s) promoted successfully.");

        $this->reset(['from_class_id', 'from_section_id', 'to_class_id', 'to_section_id', 'exam_id', 'selectedStudents', 'studentList']);
        $this->studentsLoaded = false;
        $this->resetPage();
    }

    public function render()
    {
        $classes  = StudentClass::orderBy('name')->get();
        $exams    = Exam::orderByDesc('id')->get();
        $history  = PromotionModel::with(['student', 'fromClass', 'toClass'])->latest('id')->paginate(15);

        return view('livewire.student-promotion', compact('classes', 'exams', 'history'));
    }
}
