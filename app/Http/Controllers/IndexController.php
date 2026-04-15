<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Exam;
use App\Models\StudentFee;
use App\Models\FeePayment;
use App\Models\Attendance;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard()
    {
        $data = [
            'totalStudents' => Student::select('id')->count(),
            'totalTeachers' => Teacher::select('id')->count(),
            'totalGuardians' => Guardian::select('id')->count(),
            'totalExams' => Exam::select('id')->count(),
            'totalFees' => StudentFee::select('id')->count(),
            'feesPaid' => FeePayment::select('id')->count(),
            'totalAttendances' => Attendance::select('id')->count(),
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
