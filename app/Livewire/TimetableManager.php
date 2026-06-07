<?php

namespace App\Livewire;

use App\Models\Section;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use Livewire\Component;

class TimetableManager extends Component
{
    public ?int $timetableId = null;
    public bool $showForm = false;

    public string $student_class_id = '';
    public string $section_id = '';
    public string $subject_id = '';
    public string $teacher_id = '';
    public string $day_of_week = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $room = '';
    public bool $status = true;

    public string $filterClass = '';
    public string $filterDay = '';

    protected function rules(): array
    {
        return [
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id'       => ['nullable', 'exists:sections,id'],
            'subject_id'       => ['required', 'exists:subjects,id'],
            'teacher_id'       => ['nullable', 'exists:teachers,id'],
            'day_of_week'      => ['required', 'integer', 'between:1,7'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'room'             => ['nullable', 'string', 'max:100'],
            'status'           => ['boolean'],
        ];
    }

    public function updatedStudentClassId(): void
    {
        $this->section_id = '';
        $this->subject_id = '';
    }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) return collect();
        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->student_class_id))->get();
    }

    public function getSubjectsProperty()
    {
        if (!$this->student_class_id) return collect();
        $class = StudentClass::find($this->student_class_id);
        return $class ? $class->subjects()->get() : collect();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $tt = Timetable::findOrFail($id);
        $this->timetableId      = $tt->id;
        $this->student_class_id = (string) $tt->student_class_id;
        $this->section_id       = $tt->section_id ? (string) $tt->section_id : '';
        $this->subject_id       = (string) $tt->subject_id;
        $this->teacher_id       = $tt->teacher_id ? (string) $tt->teacher_id : '';
        $this->day_of_week      = (string) $tt->day_of_week;
        $this->start_time       = substr($tt->start_time, 0, 5);
        $this->end_time         = substr($tt->end_time, 0, 5);
        $this->room             = $tt->room ?? '';
        $this->status           = (bool) $tt->status;
        $this->showForm         = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['section_id'] = $data['section_id'] ?: null;
        $data['teacher_id'] = $data['teacher_id'] ?: null;

        if ($this->timetableId) {
            Timetable::findOrFail($this->timetableId)->update($data);
            session()->flash('success', 'Timetable entry updated.');
        } else {
            // Conflict check
            $conflict = Timetable::where('student_class_id', $data['student_class_id'])
                ->where('section_id', $data['section_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function ($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
                })->exists();

            if ($conflict) {
                $this->addError('start_time', 'A timetable conflict exists for this class/day/time.');
                return;
            }

            Timetable::create($data);
            session()->flash('success', 'Timetable entry added.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Timetable::findOrFail($id)->delete();
        session()->flash('success', 'Entry deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset(['timetableId', 'student_class_id', 'section_id', 'subject_id', 'teacher_id', 'day_of_week', 'start_time', 'end_time', 'room']);
        $this->status = true;
        $this->resetValidation();
    }

    public function render()
    {
        $classes  = StudentClass::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        $timetables = Timetable::with(['studentClass', 'section', 'subject', 'teacher'])
            ->when($this->filterClass, fn($q) => $q->where('student_class_id', $this->filterClass))
            ->when($this->filterDay, fn($q) => $q->where('day_of_week', $this->filterDay))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $days = Timetable::$days;

        return view('livewire.timetable-manager', compact('classes', 'teachers', 'timetables', 'days'));
    }
}
