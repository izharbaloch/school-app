<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectAdmissionRequest;
use App\Mail\AdmissionStatusMail;
use App\Models\Admission;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\StudentEnrollmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdmissionController extends Controller
{
    public function index()
    {
        return view('admissions.index');
    }

    public function show(Admission $admission)
    {
        abort_unless(Auth::user()?->can('admissions.view'), 403);
        $admission->load(['appliedClass', 'appliedSection', 'reviewer', 'enrolledStudent', 'createdBy']);
        return view('admissions.show', compact('admission'));
    }

    public function accept(Admission $admission)
    {
        abort_unless(Auth::user()?->can('admissions.process'), 403);

        abort_if(
            in_array($admission->status, [Admission::STATUS_ENROLLED, Admission::STATUS_ACCEPTED]),
            422,
            'Application is already accepted or enrolled.'
        );

        $admission->update([
            'status'      => Admission::STATUS_ACCEPTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->sendAdmissionMail($admission);

        return back()->with('success', 'Application accepted.');
    }

    public function reject(RejectAdmissionRequest $request, Admission $admission)
    {
        abort_if(
            $admission->status === Admission::STATUS_ENROLLED,
            422,
            'Cannot reject an already enrolled application.'
        );

        $admission->update([
            'status'           => Admission::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $this->sendAdmissionMail($admission);

        return back()->with('success', 'Application rejected.');
    }

    public function enroll(Admission $admission)
    {
        abort_unless(Auth::user()?->can('admissions.process'), 403);

        abort_if(
            $admission->status !== Admission::STATUS_ACCEPTED,
            422,
            'Only accepted applications can be enrolled.'
        );

        $temporaryPassword = null;
        $service = app(StudentEnrollmentService::class);

        DB::transaction(function () use ($admission, &$temporaryPassword, $service) {

            ['guardian' => $guardian, 'temporary_password' => $temporaryPassword] = $service->findOrCreateGuardian([
                'father_name' => $admission->father_name,
                'mother_name' => $admission->mother_name,
                'guardian_phone' => $admission->guardian_phone,
                'guardian_cnic_no' => $admission->guardian_cnic_no,
                'email' => $admission->guardian_email,
                'address' => $admission->address,
            ]);

            $admissionNo = $service->generateAdmissionNo();
            $rollNo = $service->generateRollNo($admission->applied_class_id);

            // Create student record
            $student = Student::create([
                'guardian_id'      => $guardian->id,
                'admission_no'     => $admissionNo,
                'roll_no'          => $rollNo,
                'first_name'       => $admission->first_name,
                'last_name'        => $admission->last_name,
                'gender'           => $admission->gender,
                'date_of_birth'    => $admission->date_of_birth,
                'father_name'      => $admission->father_name,
                'mother_name'      => $admission->mother_name,
                'guardian_phone'   => $admission->guardian_phone,
                'guardian_cnic_no' => $admission->guardian_cnic_no,
                'guardian_email'   => $admission->guardian_email,
                'address'          => $admission->address,
                'admission_date'   => today(),
                'student_class_id' => $admission->applied_class_id,
                'section_id'       => $admission->applied_section_id,
                'status'           => 'active',
            ]);

            // Mark admission as enrolled
            $admission->update([
                'status'             => Admission::STATUS_ENROLLED,
                'enrolled_student_id' => $student->id,
                'reviewed_by'        => Auth::id(),
                'reviewed_at'        => now(),
            ]);
        });

        $this->sendAdmissionMail($admission);

        $message = 'Student enrolled successfully.';
        if ($temporaryPassword) {
            $message .= " Guardian login: {$admission->guardian_email} / {$temporaryPassword} (must be changed on first login).";
        }

        return redirect()
            ->route('admissions.show', $admission)
            ->with('success', $message);
    }

    private function sendAdmissionMail(Admission $admission): void
    {
        if (SchoolSetting::get('notifications_enabled', '1') !== '1') {
            return;
        }

        $email = $admission->guardian_email;
        if (!$email) {
            return;
        }

        try {
            Mail::to($email)->queue(new AdmissionStatusMail($admission));
        } catch (\Exception) {
            // mail failure must not break the admission workflow
        }
    }

}
