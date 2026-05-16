<?php

use App\Http\Controllers\AcademicSetupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarkController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [IndexController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Admin routes — super admin only
    Route::middleware(['permission:settings.view'])->group(function () {
        Route::get('/admin/academic-setup', [IndexController::class, 'academicSetupView'])->name('academic.setup.view');
        Route::get('/admin/access-management', [IndexController::class, 'accessManagementView'])->name('access.management.view');
    });

    // Student routes
    Route::middleware(['permission:students.view'])->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    });
    Route::middleware(['permission:students.create'])->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    });
    Route::middleware(['permission:students.edit'])->group(function () {
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    });
    Route::middleware(['permission:students.delete'])->group(function () {
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    // Guardian routes
    Route::middleware(['permission:parents.view'])->group(function () {
        Route::get('/guardians', [GuardianController::class, 'index'])->name('guardians.index');
    });

    // Teacher routes
    Route::middleware(['permission:teachers.view'])->group(function () {
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    });

    // Attendance routes
    Route::middleware(['permission:attendance.view'])->group(function () {
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
    });
    Route::middleware(['permission:attendance.mark'])->group(function () {
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    });

    // Teacher attendance routes
    Route::middleware(['permission:attendance.view'])->group(function () {
        Route::get('teacher-attendances', [TeacherAttendanceController::class, 'index'])->name('teacher-attendances.index');
        Route::get('teacher-attendances/{teacherAttendanceDate}', [TeacherAttendanceController::class, 'show'])->name('teacher-attendances.show');
    });
    Route::middleware(['permission:attendance.mark'])->group(function () {
        Route::get('teacher-attendances/create', [TeacherAttendanceController::class, 'create'])->name('teacher-attendances.create');
        Route::get('teacher-attendances/{teacherAttendanceDate}/edit', [TeacherAttendanceController::class, 'edit'])->name('teacher-attendances.edit');
    });

    // Fee routes
    Route::middleware(['permission:fees.view'])->group(function () {
        Route::get('fee-types', [FeeTypeController::class, 'index'])->name('fee-types.index');
        Route::get('fee-structures', [FeeStructureController::class, 'index'])->name('fee-structures.index');
        Route::get('student-fees', [StudentFeeController::class, 'index'])->name('student-fees.index');
        Route::get('student-fees/{studentFee}', [StudentFeeController::class, 'show'])->name('student-fees.show');
        Route::get('student-fees/{studentFee}/print-slip', [StudentFeeController::class, 'printSlip'])->name('student-fees.print-slip');
    });
    Route::middleware(['permission:fees.create'])->group(function () {
        Route::get('student-fees/create', [StudentFeeController::class, 'create'])->name('student-fees.create');
        Route::get('student-fees-bulk-generate', [StudentFeeController::class, 'bulkCreate'])->name('student-fees.bulk-create');
    });
    Route::middleware(['permission:fees.collect'])->group(function () {
        Route::get('student-fees/{studentFee}/payment', [FeePaymentController::class, 'create'])->name('student-fees.payment.create');
    });

    // Exam routes
    Route::middleware(['permission:exams.view'])->group(function () {
        Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
    });
    Route::middleware(['permission:marks.create'])->group(function () {
        Route::get('exam-marks/create', [ExamMarkController::class, 'create'])->name('exam-marks.create');
    });

    // Result routes
    Route::middleware(['permission:marks.view'])->group(function () {
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/{exam}/{student}', [ResultController::class, 'show'])->name('results.show');
        Route::get('results/{exam}/{student}/print', [ResultController::class, 'print'])->name('results.print');
    });
});

require __DIR__ . '/auth.php';
