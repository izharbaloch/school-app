<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AttendanceForm extends Component
{
    public ?Attendance $attendance = null;

    public $attendance_id = null;
    public $attendance_date = '';
    public $student_class_id = '';
    public $section_id = '';
    public $remarks = '';

    public $students = [];

    public $isEdit = false;

    public function mount($attendance = null)
    {
        $this->attendance_date = now()->toDateString();

        if ($attendance) {
            $this->attendance = $attendance;
            $this->attendance_id = $attendance->id;
            $this->isEdit = true;

            $attendance->load([
                'attendanceStudents.student',
                'studentClass',
                'section',
            ]);

            $this->attendance_date = $attendance->attendance_date->format('Y-m-d');
            $this->student_class_id = $attendance->student_class_id;
            $this->section_id = $attendance->section_id;
            $this->remarks = $attendance->remarks;

            $this->students = $attendance->attendanceStudents
                ->sortBy(fn($item) => $item->student->roll_no ?? 999999)
                ->values()
                ->map(function ($attendanceStudent) {
                    return [
                        'student_id' => $attendanceStudent->student_id,
                        'roll_no' => $attendanceStudent->student->roll_no ?? '-',
                        'name' => trim(($attendanceStudent->student->first_name ?? '') . ' ' . ($attendanceStudent->student->last_name ?? '')),
                        'status' => $attendanceStudent->status,
                        'remarks' => $attendanceStudent->remarks,
                    ];
                })
                ->toArray();
        }
    }

    public function rules()
    {
        return [
            'attendance_date' => ['required', 'date'],
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'remarks' => ['nullable', 'string'],
            'students' => ['required', 'array', 'min:1'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.status' => ['required', 'in:present,absent,leave,late'],
            'students.*.remarks' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'attendance_date' => 'attendance date',
            'student_class_id' => 'class',
            'section_id' => 'section',
        ];
    }

    public function updatedStudentClassId()
    {
        $this->section_id = '';
        $this->students = [];
    }

    public function updatedSectionId()
    {
        $this->loadStudents();
    }

    public function updatedAttendanceDate()
    {
        if ($this->student_class_id && $this->section_id) {
            $this->loadStudents();
        }
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
        })
            ->orderBy('name')
            ->get();
    }

    public function loadStudents()
    {
        if (!$this->student_class_id || !$this->section_id) {
            $this->students = [];
            return;
        }

        $students = Student::query()
            ->select('id', 'roll_no', 'first_name', 'last_name', 'student_class_id', 'section_id')
            ->where('student_class_id', $this->student_class_id)
            ->where('section_id', $this->section_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END')
            ->orderBy('roll_no')
            ->orderBy('first_name')
            ->get();

        if ($this->isEdit && !empty($this->students)) {
            $oldStatuses = collect($this->students)->keyBy('student_id');

            $this->students = $students->map(function ($student) use ($oldStatuses) {
                $existing = $oldStatuses->get($student->id);

                return [
                    'student_id' => $student->id,
                    'roll_no' => $student->roll_no ?? '-',
                    'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                    'status' => $existing['status'] ?? 'present',
                    'remarks' => $existing['remarks'] ?? '',
                ];
            })->toArray();

            return;
        }

        $this->students = $students->map(function ($student) {
            return [
                'student_id' => $student->id,
                'roll_no' => $student->roll_no ?? '-',
                'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                'status' => 'present',
                'remarks' => '',
            ];
        })->toArray();
    }

    public function markAllPresent()
    {
        foreach ($this->students as $index => $student) {
            $this->students[$index]['status'] = 'present';
        }
    }

    public function markAllAbsent()
    {
        foreach ($this->students as $index => $student) {
            $this->students[$index]['status'] = 'absent';
        }
    }

    public function save()
    {
        $this->validate();

        $alreadyExists = Attendance::query()
            ->whereDate('attendance_date', $this->attendance_date)
            ->where('student_class_id', $this->student_class_id)
            ->where('section_id', $this->section_id)
            ->when($this->attendance_id, function ($query) {
                $query->where('id', '!=', $this->attendance_id);
            })
            ->exists();

        if ($alreadyExists) {
            $this->addError('attendance_date', 'Attendance already exists for this class, section and date.');
            return;
        }

        DB::transaction(function () {
            if ($this->isEdit && $this->attendance) {
                $this->attendance->update([
                    'attendance_date' => $this->attendance_date,
                    'student_class_id' => $this->student_class_id,
                    'section_id' => $this->section_id,
                    'remarks' => $this->remarks,
                ]);

                $this->attendance->attendanceStudents()->delete();

                $attendance = $this->attendance;
            } else {
                $attendance = Attendance::create([
                    'attendance_date' => $this->attendance_date,
                    'student_class_id' => $this->student_class_id,
                    'section_id' => $this->section_id,
                    'taken_by' => auth()->id(),
                    'remarks' => $this->remarks,
                ]);

                $this->attendance = $attendance;
                $this->attendance_id = $attendance->id;
            }

            $insertData = [];

            foreach ($this->students as $student) {
                $insertData[] = [
                    'attendance_id' => $attendance->id,
                    'student_id' => $student['student_id'],
                    'status' => $student['status'],
                    'remarks' => $student['remarks'] ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            AttendanceStudent::insert($insertData);
        });

        if ($this->isEdit) {
            session()->flash('success', 'Attendance updated successfully.');
            return redirect()->route('attendances.index');
        }

        session()->flash('success', 'Attendance marked successfully.');

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'student_class_id',
            'section_id',
            'remarks',
            'students',
            'attendance_id',
        ]);

        $this->attendance = null;
        $this->isEdit = false;
        $this->attendance_date = now()->toDateString();
    }

    public function render()
    {
        return view('livewire.attendance.attendance-form', [
            'classes' => $this->classes,
            'sections' => $this->sections,
        ]);
    }
}
