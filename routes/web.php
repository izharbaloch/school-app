<?php

use App\Http\Controllers\AcademicSetupController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/admin/student-management', [IndexController::class, 'studentManagementView'])->name('student.management.view');
});

require __DIR__.'/auth.php';
