<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Exam;
use App\Models\StudentFee;
use App\Models\FeePayment;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard()
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalGuardians' => Guardian::count(),
            'totalExams' => Exam::count(),
            'totalFees' => StudentFee::count(),
            'feesPaid' => FeePayment::count(),
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
