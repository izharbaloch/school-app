<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Exam;
use App\Models\StudentFee;
use App\Models\FeePayment;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();

        // 1. Top Summary Cards
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = \App\Models\StudentClass::count();
        $totalFeeCollected = FeePayment::sum('amount');
        
        // Pending fees: total payable - paid amount
        $stats = StudentFee::selectRaw('
            SUM(amount + COALESCE(fine, 0) - COALESCE(discount, 0)) as all_amount,
            SUM(paid_amount) as paid_amount
        ')->first();
        
        $pendingFees = max(0, ($stats->all_amount ?? 0) - ($stats->paid_amount ?? 0));

        // 2. Charts Data (Last 6 Months)
        $months = [];
        $admissionsData = [];
        $feeData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $months[] = $monthDate->format('M');
            
            $admissionsData[] = Student::whereMonth('created_at', $monthDate->month)
                                       ->whereYear('created_at', $monthDate->year)
                                       ->count();

            $feeData[] = FeePayment::whereMonth('payment_date', $monthDate->month)
                                   ->whereYear('payment_date', $monthDate->year)
                                   ->sum('amount');
        }

        // Attendance Overview Chart (Today)
        $todayAttendance = AttendanceStudent::whereHas('attendance', function ($q) use ($today) {
            $q->whereDate('attendance_date', $today);
        })->get();

        $attendancePresent = $todayAttendance->where('status', AttendanceStudent::PRESENT)->count();
        $attendanceAbsent = $todayAttendance->where('status', AttendanceStudent::ABSENT)->count();
        $attendanceLeave = $todayAttendance->whereIn('status', [AttendanceStudent::LEAVE, AttendanceStudent::LATE])->count();
        
        // 3. Data Tables
        $recentStudents = Student::with('studentClass')->latest()->take(5)->get();
        $teacherRoster = Teacher::latest()->take(5)->get();

        // 4. Notifications & Activity
        $pendingFeesAlert = StudentFee::where('status', '!=', StudentFee::PAID)
            ->where('due_date', '<', now())
            ->count();
            
        $newAdmissionsAlert = Student::whereDate('created_at', '>=', now()->subDays(7))->count();

        // Combine latest FeePayments and Students for recent activity
        $latestPayments = FeePayment::with('studentFee.student')->latest()->take(3)->get()->map(function($p) {
            return [
                'type' => 'payment',
                'title' => $p->studentFee->student->full_name ?? ($p->studentFee->student->name ?? 'Student'),
                'description' => 'Paid fee of $' . number_format($p->amount, 2),
                'time_str' => $p->created_at->diffForHumans(),
                'timestamp' => $p->created_at->timestamp,
            ];
        });
        
        $latestAdmissions = Student::latest()->take(3)->get()->map(function($s) {
            return [
                'type' => 'admission',
                'title' => $s->full_name ?: ($s->name ?? 'Student'),
                'description' => 'Added to student roster',
                'time_str' => $s->created_at->diffForHumans(),
                'timestamp' => $s->created_at->timestamp,
            ];
        });

        $recentActivities = $latestPayments->concat($latestAdmissions)->sortByDesc('timestamp')->take(5);

        $data = [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalClasses' => $totalClasses,
            'totalFeeCollected' => $totalFeeCollected,
            'pendingFees' => $pendingFees,
            
            'chartMonths' => $months,
            'admissionsData' => $admissionsData,
            'feeData' => $feeData,
            
            'attendancePresent' => $attendancePresent,
            'attendanceAbsent' => $attendanceAbsent,
            'attendanceLeave' => $attendanceLeave,
            
            'recentStudents' => $recentStudents,
            'teacherRoster' => $teacherRoster,
            
            'pendingFeesAlert' => $pendingFeesAlert,
            'newAdmissionsAlert' => $newAdmissionsAlert,
            'recentActivities' => $recentActivities,
        ];

        return view('dashboard', $data);
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
