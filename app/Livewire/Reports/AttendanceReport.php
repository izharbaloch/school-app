<?php

namespace App\Livewire\Reports;

use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AttendanceReport extends Component
{
    public string $filter_class    = '';
    public string $filter_from     = '';
    public string $filter_to       = '';

    public function mount(): void
    {
        $this->filter_from = now()->startOfMonth()->format('Y-m-d');
        $this->filter_to   = now()->format('Y-m-d');
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        $rows = collect();
        $workingDays = 0;

        if ($this->filter_from && $this->filter_to) {
            $rows = DB::table('students as s')
                ->leftJoin('student_classes as sc', 'sc.id', '=', 's.student_class_id')
                ->leftJoin('sections as sec', 'sec.id', '=', 's.section_id')
                ->leftJoin('attendance_students as ast', 'ast.student_id', '=', 's.id')
                ->leftJoin('attendances as a', function ($join) {
                    $join->on('a.id', '=', 'ast.attendance_id')
                         ->whereBetween('a.attendance_date', [$this->filter_from, $this->filter_to]);
                })
                ->when($this->filter_class, fn($q) => $q->where('s.student_class_id', $this->filter_class))
                ->select([
                    's.id',
                    's.first_name',
                    's.last_name',
                    's.admission_no',
                    'sc.name as class_name',
                    'sec.name as section_name',
                    DB::raw("SUM(CASE WHEN ast.status = 'present' THEN 1 ELSE 0 END) as present_count"),
                    DB::raw("SUM(CASE WHEN ast.status = 'absent' THEN 1 ELSE 0 END) as absent_count"),
                    DB::raw("SUM(CASE WHEN ast.status IN ('leave','late') THEN 1 ELSE 0 END) as leave_count"),
                    DB::raw('COUNT(ast.id) as marked_days'),
                ])
                ->where('s.status', true)
                ->groupBy('s.id', 's.first_name', 's.last_name', 's.admission_no', 'sc.name', 'sec.name')
                ->orderBy('sc.name')
                ->orderBy('s.first_name')
                ->get()
                ->map(function ($row) {
                    $row->attendance_pct = $row->marked_days > 0
                        ? round(($row->present_count / $row->marked_days) * 100, 1)
                        : 0;
                    return $row;
                });

            // Count distinct working days in range (days where attendance was taken)
            $workingDays = DB::table('attendances')
                ->whereBetween('attendance_date', [$this->filter_from, $this->filter_to])
                ->when($this->filter_class, fn($q) => $q->where('student_class_id', $this->filter_class))
                ->distinct('attendance_date')
                ->count('attendance_date');
        }

        return view('livewire.reports.attendance-report', [
            'rows'        => $rows,
            'classes'     => $this->classes,
            'workingDays' => $workingDays,
        ]);
    }
}
