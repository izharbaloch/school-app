<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Section;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExamScheduleManager extends Component
{
    // ── Filters ──
    public string $filter_exam_id    = '';
    public string $filter_class_id   = '';
    public string $filter_section_id = '';

    // ── Form fields ──
    public string $exam_id    = '';
    public string $class_id   = '';
    public string $section_id = '';
    public string $subject_id = '';
    public string $date        = '';
    public string $start_time  = '';
    public string $end_time    = '';
    public string $room        = '';
    public string $remarks     = '';
    public bool   $status      = true;

    public ?int  $editId    = null;
    public bool  $showForm  = false;

    protected $queryString = [
        'filter_exam_id'    => ['except' => ''],
        'filter_class_id'   => ['except' => ''],
        'filter_section_id' => ['except' => ''],
    ];

    public function rules(): array
    {
        return [
            'exam_id'    => ['required', 'exists:exams,id'],
            'class_id'   => ['required', 'exists:student_classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'subject_id' => [
                'required',
                'exists:subjects,id',
                Rule::unique('exam_schedules', 'subject_id')
                    ->where('exam_id',          $this->exam_id)
                    ->where('student_class_id', $this->class_id)
                    ->where('section_id',       $this->section_id ?: null)
                    ->ignore($this->editId),
            ],
            'date'       => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'room'       => ['nullable', 'string', 'max:100'],
            'remarks'    => ['nullable', 'string'],
            'status'     => ['boolean'],
        ];
    }

    protected $messages = [
        'subject_id.unique' => 'This subject is already scheduled for this exam and class.',
        'end_time.after'    => 'End time must be after start time.',
    ];

    // ── Filter watchers ──
    public function updatedFilterClassId(): void
    {
        $this->filter_section_id = '';
        $this->showForm = false;
        $this->resetForm();
    }

    public function updatedFilterExamId(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    // ── Form actions ──
    public function openForm(): void
    {
        $this->resetForm();
        $this->exam_id  = $this->filter_exam_id;
        $this->class_id = $this->filter_class_id;
        $this->section_id = $this->filter_section_id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        ExamSchedule::create([
            'exam_id'          => $this->exam_id,
            'student_class_id' => $this->class_id,
            'section_id'       => $this->section_id ?: null,
            'subject_id'       => $this->subject_id,
            'date'             => $this->date,
            'start_time'       => $this->start_time,
            'end_time'         => $this->end_time,
            'room'             => $this->room ?: null,
            'remarks'          => $this->remarks ?: null,
            'status'           => $this->status,
        ]);

        session()->flash('success', 'Schedule entry added.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function edit(int $id): void
    {
        $s = ExamSchedule::findOrFail($id);

        $this->editId     = $s->id;
        $this->exam_id    = (string) $s->exam_id;
        $this->class_id   = (string) $s->student_class_id;
        $this->section_id = (string) ($s->section_id ?? '');
        $this->subject_id = (string) $s->subject_id;
        $this->date       = $s->date->format('Y-m-d');
        $this->start_time = $s->start_time;
        $this->end_time   = $s->end_time;
        $this->room       = $s->room ?? '';
        $this->remarks    = $s->remarks ?? '';
        $this->status     = $s->status;

        $this->showForm = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();

        $s = ExamSchedule::findOrFail($this->editId);

        $s->update([
            'exam_id'          => $this->exam_id,
            'student_class_id' => $this->class_id,
            'section_id'       => $this->section_id ?: null,
            'subject_id'       => $this->subject_id,
            'date'             => $this->date,
            'start_time'       => $this->start_time,
            'end_time'         => $this->end_time,
            'room'             => $this->room ?: null,
            'remarks'          => $this->remarks ?: null,
            'status'           => $this->status,
        ]);

        session()->flash('success', 'Schedule entry updated.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        ExamSchedule::findOrFail($id)->delete();
        session()->flash('success', 'Schedule entry deleted.');

        if ($this->editId === $id) {
            $this->resetForm();
            $this->showForm = false;
        }
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset(['subject_id', 'date', 'start_time', 'end_time', 'room', 'remarks', 'editId']);
        $this->status = true;
        $this->resetValidation();
    }

    // ── Data for dropdowns ──
    public function getExamsProperty()
    {
        return Exam::where('status', true)->orderByDesc('id')->get(['id', 'name', 'academic_year']);
    }

    public function getClassesProperty()
    {
        return StudentClass::where('status', 1)->orderBy('numeric_name')->get(['id', 'name']);
    }

    public function getSectionsProperty()
    {
        if (!$this->filter_class_id) {
            return collect();
        }

        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->filter_class_id))
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getFormSubjectsProperty()
    {
        $classId = $this->class_id ?: $this->filter_class_id;
        if (!$classId) {
            return collect();
        }

        return Subject::whereHas('classes', fn($q) => $q->where('student_classes.id', $classId))
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getSchedulesProperty()
    {
        if (!$this->filter_exam_id || !$this->filter_class_id) {
            return collect();
        }

        $query = ExamSchedule::with(['subject:id,name', 'section:id,name'])
            ->where('exam_id', $this->filter_exam_id)
            ->where('student_class_id', $this->filter_class_id);

        if ($this->filter_section_id) {
            $query->where(function ($q) {
                $q->where('section_id', $this->filter_section_id)->orWhereNull('section_id');
            });
        }

        return $query->orderBy('date')->orderBy('start_time')->get();
    }

    public function render()
    {
        return view('livewire.exams.exam-schedule-manager', [
            'exams'       => $this->exams,
            'classes'     => $this->classes,
            'sections'    => $this->sections,
            'formSubjects'=> $this->formSubjects,
            'schedules'   => $this->schedules,
        ]);
    }
}
