<?php

use App\Http\Controllers\AcademicSetupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
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
    Route::resource('fee-types', FeeTypeController::class)->except(['show']);
    Route::resource('fee-structures', FeeStructureController::class)->except(['show']);
    Route::resource('student-fees', StudentFeeController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('students/{student}/assign-fees', [StudentFeeController::class, 'createFromStructure'])->name('students.assign-fees');
    Route::post('students/{student}/assign-fees', [StudentFeeController::class, 'storeFromStructure'])->name('students.store-assigned-fees');

    Route::get('student-fees/{studentFee}/payment', [FeePaymentController::class, 'create'])->name('student-fees.payment.create');
    Route::post('student-fees/{studentFee}/payment', [FeePaymentController::class, 'store'])->name('student-fees.payment.store');
});

require __DIR__ . '/auth.php';
