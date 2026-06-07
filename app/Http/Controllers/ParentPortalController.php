<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Notice;
use App\Models\StudentFee;
use Illuminate\Support\Facades\DB;

class ParentPortalController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Guardian linked to this user (via email or user_id on guardian)
        $guardian = \App\Models\Guardian::where('email', $user->email)
            ->orWhere('user_id', $user->id)
            ->with(['students.studentClass', 'students.section'])
            ->first();

        $children = $guardian?->students ?? collect();

        // For each child, compute summary stats
        $childData = $children->map(function ($student) {
            $totalDays     = Attendance::where('student_id', $student->id)->count();
            $presentDays   = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
            $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

            $pendingFees = StudentFee::where('student_id', $student->id)
                ->where('status', '!=', 'paid')
                ->sum(DB::raw('amount - paid_amount'));

            $recentAttendance = Attendance::where('student_id', $student->id)
                ->latest('date')
                ->limit(7)
                ->get();

            return [
                'student'          => $student,
                'totalDays'        => $totalDays,
                'presentDays'      => $presentDays,
                'attendancePct'    => $attendancePct,
                'pendingFees'      => $pendingFees,
                'recentAttendance' => $recentAttendance,
            ];
        });

        $notices = Notice::active()->pinned()->limit(5)->get()
            ->merge(Notice::active()->where('is_pinned', false)->limit(5)->get());

        return view('parent.dashboard', compact('guardian', 'childData', 'notices'));
    }
}
