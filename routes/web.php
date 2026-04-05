<?php

use App\Http\Controllers\AcademicSetupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarkController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/admin/academic-setup', [IndexController::class, 'academicSetupView'])->name('academic.setup.view');
    Route::get('/admin/access-management', [IndexController::class, 'accessManagementView'])->name('access.management.view');

    // student route
    Route::resource('/students', StudentController::class);
    // attendance routes
    Route::resource('attendances', AttendanceController::class);

    // fee routes
    Route::get('fee-types', [FeeTypeController::class, 'index'])->name('fee-types.index');
    Route::get('fee-structures', [FeeStructureController::class, 'index'])->name('fee-structures.index');

    Route::get('student-fees', [StudentFeeController::class, 'index'])->name('student-fees.index');
    Route::get('student-fees/create', [StudentFeeController::class, 'create'])->name('student-fees.create');
    Route::post('student-fees', [StudentFeeController::class, 'store'])->name('student-fees.store');
    Route::get('student-fees/{studentFee}', [StudentFeeController::class, 'show'])->name('student-fees.show');

    Route::get('student-fees-bulk-generate', [StudentFeeController::class, 'bulkCreate'])->name('student-fees.bulk-create');
    Route::post('student-fees-bulk-generate', [StudentFeeController::class, 'bulkStore'])->name('student-fees.bulk-store');

    Route::get('student-fees/{studentFee}/print-slip', [StudentFeeController::class, 'printSlip'])->name('student-fees.print-slip');

    Route::get('student-fees/{studentFee}/payment', [FeePaymentController::class, 'create'])->name('student-fees.payment.create');
    Route::post('student-fees/{studentFee}/payment', [FeePaymentController::class, 'store'])->name('student-fees.payment.store');

    // Exam routes
    Route::resource('exams', ExamController::class)->except(['show']);
    Route::get('exam-marks/create', [ExamMarkController::class, 'create'])->name('exam-marks.create');
    Route::get('exam-marks/subjects/{classId}', [ExamMarkController::class, 'getSubjects'])->name('exam-marks.get-subjects');
    Route::post('exam-marks', [ExamMarkController::class, 'store'])->name('exam-marks.store');

    Route::get('results', [ResultController::class, 'index'])->name('results.index');
    Route::get('results/{exam}/{student}', [ResultController::class, 'show'])->name('results.show');
    Route::get('results/{exam}/{student}/print', [ResultController::class, 'print'])->name('results.print');
});

require __DIR__ . '/auth.php';
