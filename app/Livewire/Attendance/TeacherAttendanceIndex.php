<?php

namespace App\Livewire\Attendance;

use App\Models\TeacherAttendanceDate;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherAttendanceIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $attendance_date = '';
    public $teacher_id = '';
    public $deleteId = null;

    protected $queryString = [
        'attendance_date' => ['except' => ''],
        'teacher_id' => ['except' => ''],
    ];

    public function updatingAttendanceDate()
    {
        $this->resetPage();
    }

    public function updatingTeacherId()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if (!$this->deleteId) {
            return;
        }

        $attendanceDate = TeacherAttendanceDate::find($this->deleteId);

        if ($attendanceDate) {
            $attendanceDate->delete();
        }

        $this->deleteId = null;

        session()->flash('success', 'Teacher attendance deleted successfully.');
    }

    public function resetFilters()
    {
        $this->reset(['attendance_date', 'teacher_id']);
        $this->resetPage();
    }

    public function getTeachersProperty()
    {
        return \App\Models\Teacher::orderBy('name')->get();
    }

    public function getReportStats()
    {
        $date = $this->attendance_date ?: \Carbon\Carbon::today()->toDateString();

        $query = \App\Models\AttendanceTeacher::whereHas('attendanceDate', function ($q) use ($date) {
            $q->whereDate('attendance_date', $date);
        });

        if ($this->teacher_id) {
            $query->where('teacher_id', $this->teacher_id);
        }

        $total = $query->count();
        $present = (clone $query)->where('status', \App\Models\AttendanceTeacher::PRESENT)->count();
        $absent = (clone $query)->where('status', \App\Models\AttendanceTeacher::ABSENT)->count();

        $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'percentage' => $percentage,
            'date' => $date
        ];
    }

    public function render()
    {
        $attendances = TeacherAttendanceDate::query()
            ->select('id', 'attendance_date', 'taken_by', 'remarks', 'created_at')
            ->withCount([
                'attendanceTeachers as total_teachers' => function($q) {
                    if ($this->teacher_id) {
                        $q->where('teacher_id', $this->teacher_id);
                    }
                },
                'attendanceTeachers as present_count' => function($q) {
                    $q->where('status', 'present');
                    if ($this->teacher_id) {
                        $q->where('teacher_id', $this->teacher_id);
                    }
                },
                'attendanceTeachers as absent_count' => function($q) {
                    $q->where('status', 'absent');
                    if ($this->teacher_id) {
                        $q->where('teacher_id', $this->teacher_id);
                    }
                },
                'attendanceTeachers as leave_count' => function($q) {
                    $q->where('status', 'leave');
                    if ($this->teacher_id) {
                        $q->where('teacher_id', $this->teacher_id);
                    }
                },
                'attendanceTeachers as late_count' => function($q) {
                    $q->where('status', 'late');
                    if ($this->teacher_id) {
                        $q->where('teacher_id', $this->teacher_id);
                    }
                },
            ]);

        if ($this->attendance_date) {
            $attendances = $attendances->whereDate('attendance_date', $this->attendance_date);
        }

        return view('livewire.attendance.teacher-attendance-index', [
            'attendances' => $attendances->orderByDesc('attendance_date')->paginate(10),
            'teachers' => $this->teachers,
            'reportStats' => $this->getReportStats(),
        ]);
    }
}
