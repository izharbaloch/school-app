<?php

namespace App\Services;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;

class StudentEnrollmentService
{
    public function generateAdmissionNo(): string
    {
        $year = date('Y');

        $lastStudent = Student::where('admission_no', 'like', "ADM-%-{$year}")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastStudent && preg_match('/ADM-(\d+)-' . $year . '/', $lastStudent->admission_no, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return 'ADM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }

    public function generateRollNo(int $classId): int
    {
        $lastStudent = Student::where('student_class_id', $classId)
            ->orderByRaw('CAST(roll_no AS UNSIGNED) DESC')
            ->first();

        return $lastStudent ? ((int) $lastStudent->roll_no + 1) : 1;
    }

    /**
     * Find an existing guardian by CNIC/phone/email, or create one — creating a
     * linked user account with a random temporary password when an email is
     * given and no account exists yet for it.
     *
     * @param array{father_name: string, mother_name: ?string, guardian_phone: ?string, guardian_cnic_no: ?string, email: ?string, address: ?string} $data
     * @return array{guardian: Guardian, temporary_password: ?string}
     */
    public function findOrCreateGuardian(array $data): array
    {
        $cnic = $this->emptyToNull($data['guardian_cnic_no'] ?? null);
        $phone = $this->emptyToNull($data['guardian_phone'] ?? null);
        $email = $this->emptyToNull($data['email'] ?? null);

        $guardian = null;
        if ($cnic) {
            $guardian = Guardian::where('guardian_cnic_no', $cnic)->first();
        }
        if (!$guardian && $phone) {
            $guardian = Guardian::where('guardian_phone', $phone)->first();
        }
        if (!$guardian && $email) {
            $guardian = Guardian::where('email', $email)->first();
        }

        $user = null;
        $temporaryPassword = null;

        if ($email) {
            $temporaryPassword = User::generateTemporaryPassword();

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['father_name'],
                    'password' => bcrypt($temporaryPassword),
                    'must_change_password' => true,
                ]
            );

            if (!$user->wasRecentlyCreated) {
                $temporaryPassword = null;
            }

            $user->update(['name' => $data['father_name']]);

            if (!$user->hasRole('parent')) {
                $user->assignRole('parent');
            }
        }

        $attributes = [
            'user_id' => $user?->id ?? $guardian?->user_id,
            'father_name' => $data['father_name'],
            'mother_name' => $this->emptyToNull($data['mother_name'] ?? null),
            'guardian_phone' => $phone,
            'guardian_cnic_no' => $cnic,
            'email' => $email,
            'address' => $this->emptyToNull($data['address'] ?? null),
        ];

        if ($guardian) {
            $guardian->update($attributes);
        } else {
            $guardian = Guardian::create($attributes + ['status' => 1]);
        }

        return ['guardian' => $guardian, 'temporary_password' => $temporaryPassword];
    }

    private function emptyToNull($value): mixed
    {
        return filled($value) ? $value : null;
    }
}
