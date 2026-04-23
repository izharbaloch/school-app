<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceTeacher;
use App\Models\Teacher;
use App\Models\TeacherAttendanceDate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeacherAttendanceForm extends Component
{
    public ?TeacherAttendanceDate $attendance = null;

    public $attendance_id = null;
    public $attendance_date = '';
    public $remarks = '';

    public $teachers = [];

    public $isEdit = false;

    public function mount($attendance = null)
    {
        $this->attendance_date = now()->toDateString();

        if ($attendance) {
            $this->attendance = $attendance;
            $this->attendance_id = $attendance->id;
            $this->isEdit = true;

            $attendance->load([
                'attendanceTeachers.teacher:id,name,employee_no'
            ]);

            $this->attendance_date = $attendance->attendance_date;
            $this->remarks = $attendance->remarks;

            $this->teachers = $attendance->attendanceTeachers->map(function ($item) {
                return [
                    'teacher_id' => $item->teacher_id,
                    'employee_no' => $item->teacher->employee_no ?? '-',
                    'name' => $item->teacher->name ?? '-',
                    'status' => $item->status,
                    'remarks' => $item->remarks,
                ];
            })->toArray();

        } else {
            $this->loadTeachers();
        }
    }

    // ✅ Load Teachers
    public function loadTeachers()
    {
        $teachers = Teacher::where('status', 1)
            ->orderBy('name')
            ->get();

        $this->teachers = $teachers->map(function ($teacher) {
            return [
                'teacher_id' => $teacher->id,
                'employee_no' => $teacher->employee_no ?? '-',
                'name' => $teacher->name,
                'status' => 'present',
                'remarks' => '',
            ];
        })->toArray();
    }

    // ✅ Rules
    public function rules()
    {
        return [
            'attendance_date' => 'required|date',
            'teachers' => 'required|array|min:1',
            'teachers.*.teacher_id' => 'required|exists:teachers,id',
            'teachers.*.status' => 'required|in:present,absent,leave,late',
        ];
    }

    // ✅ Reload on date change
    public function updatedAttendanceDate()
    {
        if (!$this->isEdit) {
            $this->loadTeachers();
        }
    }

    // ✅ Save / Update
    public function save()
    {
        $this->validate();

        // 🔥 Duplicate check (date unique hai)
        $alreadyExists = TeacherAttendanceDate::whereDate('attendance_date', $this->attendance_date)
            ->when($this->attendance_id, function ($q) {
                $q->where('id', '!=', $this->attendance_id);
            })
            ->exists();

        if ($alreadyExists) {
            session()->flash('error', 'Attendance already exists for this date.');
            return;
        }

        DB::transaction(function () {

            // ✅ Create or Update main record
            if ($this->isEdit && $this->attendance) {

                $this->attendance->update([
                    'remarks' => $this->remarks,
                ]);

                // delete old records
                $this->attendance->attendanceTeachers()->delete();

                $attendance = $this->attendance;

            } else {

                $attendance = TeacherAttendanceDate::create([
                    'attendance_date' => $this->attendance_date,
                    'remarks' => $this->remarks,
                    'taken_by' => auth()->id(),
                ]);

                $this->attendance = $attendance;
                $this->attendance_id = $attendance->id;
            }

            // ✅ Insert new
            $insertData = [];

            foreach ($this->teachers as $teacher) {
                $insertData[] = [
                    'attendance_date_id' => $attendance->id,
                    'teacher_id' => $teacher['teacher_id'],
                    'status' => $teacher['status'],
                    'remarks' => $teacher['remarks'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            AttendanceTeacher::insert($insertData);
        });

        if ($this->isEdit) {
            session()->flash('success', 'Teacher attendance updated successfully.');
            return redirect()->route('teacher-attendances.index');
        }

        session()->flash('success', 'Teacher attendance saved successfully.');

        $this->resetForm();
    }

    // ✅ Reset
    public function resetForm()
    {
        $this->reset([
            'attendance_id',
            'remarks',
            'teachers',
        ]);

        $this->attendance = null;
        $this->isEdit = false;
        $this->attendance_date = now()->toDateString();

        $this->loadTeachers();
    }

    public function render()
    {
        return view('livewire.attendance.teacher-attendance-form');
    }
}
