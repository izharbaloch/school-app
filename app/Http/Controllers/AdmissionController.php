<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return back()->with('success', 'Application accepted.');
    }

    public function reject(Request $request, Admission $admission)
    {
        abort_unless(Auth::user()?->can('admissions.process'), 403);

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

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

        DB::transaction(function () use ($admission) {

            // Find or create guardian
            $guardian = null;
            if ($admission->guardian_cnic_no) {
                $guardian = Guardian::where('guardian_cnic_no', $admission->guardian_cnic_no)->first();
            }
            if (!$guardian && $admission->guardian_phone) {
                $guardian = Guardian::where('guardian_phone', $admission->guardian_phone)->first();
            }
            if (!$guardian && $admission->guardian_email) {
                $guardian = Guardian::where('email', $admission->guardian_email)->first();
            }

            // Create guardian user account if email provided
            $guardianUser = null;
            if ($admission->guardian_email) {
                $guardianUser = User::firstOrCreate(
                    ['email' => $admission->guardian_email],
                    [
                        'name'     => $admission->father_name,
                        'password' => bcrypt('changeme123!'),
                    ]
                );
                $guardianUser->update(['name' => $admission->father_name]);
                if (!$guardianUser->hasRole('parent')) {
                    $guardianUser->assignRole('parent');
                }
            }

            if ($guardian) {
                $guardian->update([
                    'user_id'          => $guardianUser?->id ?? $guardian->user_id,
                    'father_name'      => $admission->father_name,
                    'mother_name'      => $admission->mother_name,
                    'guardian_phone'   => $admission->guardian_phone,
                    'guardian_cnic_no' => $admission->guardian_cnic_no,
                    'email'            => $admission->guardian_email,
                    'address'          => $admission->address,
                ]);
            } else {
                $guardian = Guardian::create([
                    'user_id'          => $guardianUser?->id,
                    'father_name'      => $admission->father_name,
                    'mother_name'      => $admission->mother_name,
                    'guardian_phone'   => $admission->guardian_phone,
                    'guardian_cnic_no' => $admission->guardian_cnic_no,
                    'email'            => $admission->guardian_email,
                    'address'          => $admission->address,
                    'status'           => 1,
                ]);
            }

            // Generate admission no and roll no
            $year = date('Y');
            $lastStudent = Student::where('admission_no', 'like', 'ADM-%-' . $year)->orderByDesc('id')->first();
            $nextNum = 1;
            if ($lastStudent && preg_match('/ADM-(\d+)-' . $year . '/', $lastStudent->admission_no, $m)) {
                $nextNum = (int) $m[1] + 1;
            }
            $admissionNo = 'ADM-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT) . '-' . $year;

            $lastInClass = Student::where('student_class_id', $admission->applied_class_id)
                ->orderByRaw('CAST(roll_no AS UNSIGNED) DESC')->first();
            $rollNo = $lastInClass ? ((int) $lastInClass->roll_no + 1) : 1;

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

        return redirect()
            ->route('admissions.show', $admission)
            ->with('success', 'Student enrolled successfully. Login credentials: email / changeme123!');
    }

}
