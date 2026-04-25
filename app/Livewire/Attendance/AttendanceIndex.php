<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\StudentClass;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $attendance_date = '';
    public $student_class_id = '';
    public $section_id = '';

    public $deleteId = null;

    protected $queryString = [
        'attendance_date' => ['except' => ''],
        'student_class_id' => ['except' => ''],
        'section_id' => ['except' => ''],
    ];

    public function updatingAttendanceDate()
    {
        $this->resetPage();
    }

    public function updatingStudentClassId()
    {
        $this->section_id = '';
        $this->resetPage();
    }

    public function updatingSectionId()
    {
        $this->resetPage();
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

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if (!$this->deleteId) {
            return;
        }

        $attendance = Attendance::find($this->deleteId);

        if ($attendance) {
            $attendance->delete();
        }

        $this->deleteId = null;

        session()->flash('success', 'Attendance deleted successfully.');
    }

    public function resetFilters()
    {
        $this->reset(['attendance_date', 'student_class_id', 'section_id']);
        $this->resetPage();
    }

    public function getReportStats()
    {
        $date = $this->attendance_date ?: \Carbon\Carbon::today()->toDateString();

        $query = \App\Models\AttendanceStudent::whereHas('attendance', function ($q) use ($date) {
            $q->whereDate('attendance_date', $date);
            
            if ($this->student_class_id) {
                $q->where('student_class_id', $this->student_class_id);
            }
            if ($this->section_id) {
                $q->where('section_id', $this->section_id);
            }
        });

        $total = $query->count();
        $present = (clone $query)->where('status', \App\Models\AttendanceStudent::PRESENT)->count();
        $absent = (clone $query)->where('status', \App\Models\AttendanceStudent::ABSENT)->count();

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
        $attendances = Attendance::query()
            ->select('id', 'attendance_date', 'student_class_id', 'section_id', 'taken_by', 'remarks', 'created_at')
            ->with([
                'studentClass:id,name',
                'section:id,name',
            ])
            ->withCount([
                'attendanceStudents as total_students',
                'attendanceStudents as present_count' => function ($query) {
                    $query->where('status', 'present');
                },
                'attendanceStudents as absent_count' => function ($query) {
                    $query->where('status', 'absent');
                },
                'attendanceStudents as leave_count' => function ($query) {
                    $query->where('status', 'leave');
                },
                'attendanceStudents as late_count' => function ($query) {
                    $query->where('status', 'late');
                },
            ])
            ->when($this->attendance_date, function ($query) {
                $query->whereDate('attendance_date', $this->attendance_date);
            })
            ->when($this->student_class_id, function ($query) {
                $query->where('student_class_id', $this->student_class_id);
            })
            ->when($this->section_id, function ($query) {
                $query->where('section_id', $this->section_id);
            })
            ->latest('attendance_date')
            ->paginate(20);

        return view('livewire.attendance.attendance-index', [
            'attendances' => $attendances,
            'classes' => $this->classes,
            'sections' => $this->sections,
            'reportStats' => $this->getReportStats(),
        ]);
    }
}
