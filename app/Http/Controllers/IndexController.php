<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AttendanceStudent;
use App\Models\Event;
use App\Models\Exam;
use App\Models\FeePayment;
use App\Models\HostelAllocation;
use App\Models\LeaveApplication;
use App\Models\Notice;
use App\Models\Student;
use App\Models\StudentActivityEnrollment;
use App\Models\StudentClass;
use App\Models\StudentFee;
use App\Models\StudentIncident;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function dashboard()
    {
        $user  = Auth::user();
        $today = now()->toDateString();

        // ── Student stats ──────────────────────────────────────────
        $totalStudents   = Student::allowedForUser($user)->count();
        $activeStudents  = Student::allowedForUser($user)->where('status', true)->count();
        $maleStudents    = Student::allowedForUser($user)->where('gender', 'male')->count();
        $femaleStudents  = Student::allowedForUser($user)->where('gender', 'female')->count();

        // New admissions this month & last 7 days
        $newAdmissionsThisMonth = Student::allowedForUser($user)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $newAdmissionsAlert = Student::allowedForUser($user)
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();

        // ── Teacher stats ──────────────────────────────────────────
        $totalTeachers  = Teacher::allowedForUser($user)->count();
        $activeTeachers = Teacher::allowedForUser($user)->where('status', true)->count();

        // ── Class stats ────────────────────────────────────────────
        $totalClasses = StudentClass::allowedForUser($user)->count();

        // Students per class (for chart, top 8)
        $studentsByClass = StudentClass::allowedForUser($user)
            ->withCount(['students as student_count' => fn($q) => $q->where('status', true)])
            ->orderByDesc('student_count')
            ->take(8)
            ->get();

        // ── Today's attendance ─────────────────────────────────────
        $todayAttRows = AttendanceStudent::allowedForUser($user)
            ->whereHas('attendance', fn($q) => $q->whereDate('attendance_date', $today))
            ->selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $attendancePresent = (int) ($todayAttRows[AttendanceStudent::PRESENT] ?? 0);
        $attendanceAbsent  = (int) ($todayAttRows[AttendanceStudent::ABSENT] ?? 0);
        $attendanceLeave   = (int) (($todayAttRows[AttendanceStudent::LEAVE] ?? 0) + ($todayAttRows[AttendanceStudent::LATE] ?? 0));
        $attendanceTotal   = $attendancePresent + $attendanceAbsent + $attendanceLeave;
        $attendancePct     = $attendanceTotal > 0 ? round(($attendancePresent / $attendanceTotal) * 100) : 0;

        // ── Fee stats ──────────────────────────────────────────────
        $totalFeeCollected = FeePayment::allowedForUser($user)->sum('amount');

        $thisMonthFee = FeePayment::allowedForUser($user)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $feeStats = StudentFee::allowedForUser($user)
            ->selectRaw('
                SUM(amount + COALESCE(fine,0) - COALESCE(discount,0)) as all_amount,
                SUM(paid_amount) as paid_amount
            ')->first();
        $pendingFees = max(0, ($feeStats->all_amount ?? 0) - ($feeStats->paid_amount ?? 0));

        $feeDefaultersCount = StudentFee::allowedForUser($user)
            ->where('status', '!=', StudentFee::PAID)
            ->where('due_date', '<', now())
            ->count();

        $feeDefaulters = StudentFee::allowedForUser($user)
            ->with(['student.studentClass'])
            ->where('status', '!=', StudentFee::PAID)
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->take(6)
            ->get();

        // ── Charts — last 6 months ─────────────────────────────────
        $chartMonths      = [];
        $admissionsData   = [];
        $feeCollectedData = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $chartMonths[]      = $m->format('M');
            $admissionsData[]   = Student::allowedForUser($user)
                ->whereMonth('created_at', $m->month)->whereYear('created_at', $m->year)->count();
            $feeCollectedData[] = (float) FeePayment::allowedForUser($user)
                ->whereMonth('payment_date', $m->month)->whereYear('payment_date', $m->year)->sum('amount');
        }

        // ── Recent lists ───────────────────────────────────────────
        $recentAdmissions = Student::allowedForUser($user)
            ->with('studentClass')
            ->latest()
            ->take(7)
            ->get();

        $recentPayments = FeePayment::allowedForUser($user)
            ->with(['studentFee.student.studentClass'])
            ->latest()
            ->take(6)
            ->get();

        $upcomingExams = Exam::where('start_date', '>=', $today)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        $upcomingEvents = Event::where('start_date', '>=', $today)
            ->where('status', true)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        $recentNotices = Notice::where('status', true)
            ->where('publish_date', '<=', now())
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->orderByDesc('is_pinned')
            ->orderByDesc('publish_date')
            ->take(5)
            ->get();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // ── New module quick-stats (permission-gated) ───────────────
        $pendingLeavesCount   = $user->can('leaves.view')
            ? LeaveApplication::allowedForUser($user)->where('status', 'pending')->count()
            : null;

        $openIncidentsCount   = $user->can('conduct.view')
            ? StudentIncident::allowedForUser($user)->where('status', 'open')->count()
            : null;

        $hostelOccupied       = $user->can('hostel.view')
            ? HostelAllocation::where('status', 'active')->count()
            : null;

        $activeSportsMembers  = $user->can('sports.view')
            ? StudentActivityEnrollment::where('status', true)->count()
            : null;

        return view('dashboard', compact(
            'totalStudents', 'activeStudents', 'maleStudents', 'femaleStudents',
            'newAdmissionsThisMonth', 'newAdmissionsAlert',
            'totalTeachers', 'activeTeachers',
            'totalClasses', 'studentsByClass',
            'attendancePresent', 'attendanceAbsent', 'attendanceLeave',
            'attendanceTotal', 'attendancePct',
            'totalFeeCollected', 'thisMonthFee', 'pendingFees',
            'feeDefaultersCount', 'feeDefaulters',
            'chartMonths', 'admissionsData', 'feeCollectedData',
            'recentAdmissions', 'recentPayments',
            'upcomingExams', 'upcomingEvents', 'recentNotices',
            'recentActivities',
            'pendingLeavesCount', 'openIncidentsCount',
            'hostelOccupied', 'activeSportsMembers'
        ));
    }

    public function academicSetupView()
    {
        return view('admin.academic-setup');
    }

    public function accessManagementView()
    {
        return view('admin.access-management');
    }
}
