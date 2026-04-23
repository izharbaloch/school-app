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
    public $deleteId = null;

    protected $queryString = [
        'attendance_date' => ['except' => ''],
    ];

    public function updatingAttendanceDate()
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
        $this->reset(['attendance_date']);
        $this->resetPage();
    }

    public function render()
    {
        $attendances = TeacherAttendanceDate::query()
            ->select('id', 'attendance_date', 'taken_by', 'remarks', 'created_at')
            ->withCount([
                'attendanceTeachers as total_teachers',
                'attendanceTeachers as present_count' => fn($q) => $q->where('status', 'present'),
                'attendanceTeachers as absent_count' => fn($q) => $q->where('status', 'absent'),
                'attendanceTeachers as leave_count' => fn($q) => $q->where('status', 'leave'),
                'attendanceTeachers as late_count' => fn($q) => $q->where('status', 'late'),
            ]);

        if ($this->attendance_date) {
            $attendances = $attendances->whereDate('attendance_date', $this->attendance_date);
        }

        return view('livewire.attendance.teacher-attendance-index', [
            'attendances' => $attendances->orderByDesc('attendance_date')->paginate(10),
        ]);
    }
}
