<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ConductController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarkController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\StudentPromotionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TransportController;
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

    // Report routes
    Route::middleware(['permission:reports.view'])->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/students', [ReportController::class, 'students'])->name('reports.students');
        Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('reports/fees', [ReportController::class, 'fees'])->name('reports.fees');
    });

    // Hostel routes
    Route::middleware(['permission:hostel.view'])->group(function () {
        Route::get('hostel', [HostelController::class, 'index'])->name('hostel.index');
    });

    // Sports routes
    Route::middleware(['permission:sports.view'])->group(function () {
        Route::get('sports', [SportsController::class, 'index'])->name('sports.index');
    });

    // Medical records routes
    Route::middleware(['permission:medical.view'])->group(function () {
        Route::get('medical', [MedicalRecordController::class, 'index'])->name('medical.index');
    });

    // Conduct routes
    Route::middleware(['permission:conduct.view'])->group(function () {
        Route::get('conduct', [ConductController::class, 'index'])->name('conduct.index');
    });

    // Leave routes
    Route::middleware(['permission:leaves.view'])->group(function () {
        Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
    });
    Route::middleware(['permission:leaves.manage'])->group(function () {
        Route::get('leaves/types', [LeaveController::class, 'types'])->name('leaves.types');
    });

    // Admission routes
    Route::middleware(['permission:admissions.view'])->group(function () {
        Route::get('admissions', [AdmissionController::class, 'index'])->name('admissions.index');
        Route::get('admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');
    });
    Route::middleware(['permission:admissions.process'])->group(function () {
        Route::post('admissions/{admission}/accept', [AdmissionController::class, 'accept'])->name('admissions.accept');
        Route::post('admissions/{admission}/reject', [AdmissionController::class, 'reject'])->name('admissions.reject');
        Route::post('admissions/{admission}/enroll', [AdmissionController::class, 'enroll'])->name('admissions.enroll');
    });

    // Student routes
    Route::middleware(['permission:students.create'])->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    });
    Route::middleware(['permission:students.view'])->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
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
    Route::middleware(['permission:attendance.mark'])->group(function () {
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    });
    Route::middleware(['permission:attendance.view'])->group(function () {
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
    });

    // Teacher attendance routes
    Route::middleware(['permission:attendance.mark'])->group(function () {
        Route::get('teacher-attendances/create', [TeacherAttendanceController::class, 'create'])->name('teacher-attendances.create');
        Route::get('teacher-attendances/{teacherAttendanceDate}/edit', [TeacherAttendanceController::class, 'edit'])->name('teacher-attendances.edit');
    });
    Route::middleware(['permission:attendance.view'])->group(function () {
        Route::get('teacher-attendances', [TeacherAttendanceController::class, 'index'])->name('teacher-attendances.index');
        Route::get('teacher-attendances/{teacherAttendanceDate}', [TeacherAttendanceController::class, 'show'])->name('teacher-attendances.show');
    });

    // Fee routes
    Route::middleware(['permission:fees.create'])->group(function () {
        Route::get('student-fees/create', [StudentFeeController::class, 'create'])->name('student-fees.create');
        Route::get('student-fees-bulk-generate', [StudentFeeController::class, 'bulkCreate'])->name('student-fees.bulk-create');
    });
    Route::middleware(['permission:fees.collect'])->group(function () {
        Route::get('student-fees/{studentFee}/payment', [FeePaymentController::class, 'create'])->name('student-fees.payment.create');
    });

    Route::middleware(['permission:fees.view'])->group(function () {
        Route::get('fee-types', [FeeTypeController::class, 'index'])->name('fee-types.index');
        Route::get('fee-structures', [FeeStructureController::class, 'index'])->name('fee-structures.index');
        Route::get('student-fees', [StudentFeeController::class, 'index'])->name('student-fees.index');
        Route::get('student-fees/{studentFee}', [StudentFeeController::class, 'show'])->name('student-fees.show');
        Route::get('student-fees/{studentFee}/print-slip', [StudentFeeController::class, 'printSlip'])->name('student-fees.print-slip');
    });

    // Exam routes
    Route::middleware(['permission:exams.view'])->group(function () {
        Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('exam-schedule', [ExamScheduleController::class, 'index'])->name('exam-schedule.index');
        Route::get('exam-schedule/print', [ExamScheduleController::class, 'print'])->name('exam-schedule.print');
    });
    Route::middleware(['permission:marks.create'])->group(function () {
        Route::get('exam-marks/create', [ExamMarkController::class, 'create'])->name('exam-marks.create');
    });

    // Result routes
    Route::middleware(['permission:marks.view'])->group(function () {
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/{exam}/{student}', [ResultController::class, 'show'])->name('results.show');
        Route::get('results/{exam}/{student}/print', [ResultController::class, 'print'])->name('results.print');
        Route::get('results/{exam}/{student}/report-card', [ResultController::class, 'reportCard'])->name('results.report-card');
    });

    // Academic Sessions
    Route::middleware(['permission:settings.view'])->group(function () {
        Route::get('academic-sessions', [AcademicSessionController::class, 'index'])->name('academic-sessions.index');
    });

    // Student Promotions
    Route::middleware(['permission:students.edit'])->group(function () {
        Route::get('student-promotions', [StudentPromotionController::class, 'index'])->name('student-promotions.index');
    });

    // Timetable routes
    Route::middleware(['permission:timetable.view'])->group(function () {
        Route::get('timetable', [TimetableController::class, 'index'])->name('timetable.index');
    });

    // Notice Board routes
    Route::middleware(['permission:notices.view'])->group(function () {
        Route::get('notices', [NoticeController::class, 'index'])->name('notices.index');
    });

    // Library routes
    Route::middleware(['permission:library.view'])->group(function () {
        Route::get('library', [LibraryController::class, 'index'])->name('library.index');
    });

    // Transport routes
    Route::middleware(['permission:settings.view'])->group(function () {
        Route::get('transport', [TransportController::class, 'index'])->name('transport.index');
    });

    // Events routes
    Route::middleware(['permission:notices.view'])->group(function () {
        Route::get('events', [EventController::class, 'index'])->name('events.index');
    });

    // Homework routes
    Route::middleware(['permission:homework.view'])->group(function () {
        Route::get('homework', [HomeworkController::class, 'index'])->name('homework.index');
    });

    // Accounting routes
    Route::middleware(['permission:accounting.view'])->group(function () {
        Route::get('accounting', [AccountingController::class, 'index'])->name('accounting.index');
    });

    // System Settings routes
    Route::middleware(['permission:settings.view'])->group(function () {
        Route::get('settings', [SchoolSettingController::class, 'index'])->name('settings.index');
    });

    // Activity Logs routes
    Route::middleware(['permission:settings.view'])->group(function () {
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Parent Portal routes
    Route::middleware(['role:parent'])->group(function () {
        Route::get('parent/dashboard', [ParentPortalController::class, 'dashboard'])->name('parent.dashboard');
    });

    // Certificate routes
    Route::middleware(['permission:students.view'])->group(function () {
        Route::get('certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('certificates/{student}/character', [CertificateController::class, 'characterCertificate'])->name('certificates.character');
        Route::get('certificates/{student}/character/print', [CertificateController::class, 'characterCertificatePrint'])->name('certificates.character.print');
        Route::get('certificates/{student}/leaving', [CertificateController::class, 'leavingCertificate'])->name('certificates.leaving');
        Route::get('certificates/{student}/leaving/print', [CertificateController::class, 'leavingCertificatePrint'])->name('certificates.leaving.print');
        Route::get('certificates/{student}/bonafide', [CertificateController::class, 'bonafideCertificate'])->name('certificates.bonafide');
        Route::get('certificates/{student}/bonafide/print', [CertificateController::class, 'bonafideCertificatePrint'])->name('certificates.bonafide.print');
        Route::get('certificates/{student}/id-card', [CertificateController::class, 'idCard'])->name('certificates.id-card');
        Route::get('certificates/{student}/id-card/print', [CertificateController::class, 'idCardPrint'])->name('certificates.id-card.print');
    });
});

require __DIR__ . '/auth.php';
