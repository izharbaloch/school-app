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
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $today = now()->toDateString();

        // Get this month's fees
        $thisMonthFees = StudentFee::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();

        $monthlyPendingAmount = $thisMonthFees->where('status', StudentFee::UNPAID)
            ->sum('amount');

        $monthlyPaidAmount = $thisMonthFees->where('status', StudentFee::PAID)
            ->sum('paid_amount');

        // Get today's attendance
        $todayAttendance = AttendanceStudent::whereHas('attendance', function ($query) use ($today) {
            $query->whereDate('attendance_date', $today);
        })->get();

        $todayPresent = $todayAttendance->where('status', AttendanceStudent::PRESENT)->count();
        $todayAbsent = $todayAttendance->where('status', AttendanceStudent::ABSENT)->count();

        // Total student fees amount
        $totalStudentFeesAmount = StudentFee::sum(DB::raw('amount + fine - discount'));

        $data = [
            'totalStudents' => Student::select('id')->count(),
            'totalTeachers' => Teacher::select('id')->count(),
            'totalGuardians' => Guardian::select('id')->count(),
            'totalExams' => Exam::select('id')->count(),
            'totalFees' => StudentFee::select('id')->count(),
            'feesPaid' => FeePayment::select('id')->count(),
            'totalAttendances' => Attendance::select('id')->count(),
            'monthlyPendingAmount' => $monthlyPendingAmount,
            'monthlyPaidAmount' => $monthlyPaidAmount,
            'totalStudentFeesAmount' => $totalStudentFeesAmount,
            'todayPresent' => $todayPresent,
            'todayAbsent' => $todayAbsent,
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
