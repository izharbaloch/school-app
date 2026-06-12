<?php

namespace App\Livewire\Medical;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentMedicalRecord;
use App\Models\StudentVaccination;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MedicalRecordManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ── Filters ──
    public string $filter_class      = '';
    public string $filter_blood_group = '';

    // ── Selected student / record view ──
    public ?int $selectedStudentId = null;

    // ── Medical record form ──
    public bool   $showRecordForm  = false;
    public string $blood_group     = 'unknown';
    public string $height_cm       = '';
    public string $weight_kg       = '';
    public string $vision_left     = '';
    public string $vision_right    = '';
    public string $allergies       = '';
    public string $chronic_conditions = '';
    public string $disabilities    = '';
    public string $emergency_contact_name  = '';
    public string $emergency_contact_phone = '';
    public string $doctor_name     = '';
    public string $doctor_phone    = '';
    public string $notes           = '';

    // ── Vaccination form ──
    public bool   $showVaccinationForm = false;
    public ?int   $editVaccineId       = null;
    public string $vaccine_name        = '';
    public string $date_administered   = '';
    public string $next_due_date       = '';
    public string $administered_by     = '';
    public string $vaccine_notes       = '';

    protected $queryString = [
        'filter_class'       => ['except' => ''],
        'filter_blood_group' => ['except' => ''],
    ];

    public function recordRules(): array
    {
        return [
            'blood_group'             => ['required', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-,unknown'],
            'height_cm'               => ['nullable', 'numeric', 'min:30', 'max:300'],
            'weight_kg'               => ['nullable', 'numeric', 'min:1', 'max:500'],
            'vision_left'             => ['nullable', 'string', 'max:20'],
            'vision_right'            => ['nullable', 'string', 'max:20'],
            'allergies'               => ['nullable', 'string'],
            'chronic_conditions'      => ['nullable', 'string'],
            'disabilities'            => ['nullable', 'string'],
            'emergency_contact_name'  => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'doctor_name'             => ['nullable', 'string', 'max:150'],
            'doctor_phone'            => ['nullable', 'string', 'max:30'],
            'notes'                   => ['nullable', 'string'],
        ];
    }

    public function vaccinationRules(): array
    {
        return [
            'vaccine_name'      => ['required', 'string', 'max:150'],
            'date_administered' => ['required', 'date'],
            'next_due_date'     => ['nullable', 'date', 'after:date_administered'],
            'administered_by'   => ['nullable', 'string', 'max:150'],
            'vaccine_notes'     => ['nullable', 'string'],
        ];
    }

    public function selectStudent(int $studentId): void
    {
        $this->selectedStudentId = $studentId;
        $this->showRecordForm    = false;
        $this->showVaccinationForm = false;
        $this->resetValidation();
    }

    public function deselectStudent(): void
    {
        $this->selectedStudentId = null;
        $this->showRecordForm    = false;
        $this->showVaccinationForm = false;
        $this->resetValidation();
    }

    public function openRecordForm(): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        $record = StudentMedicalRecord::where('student_id', $this->selectedStudentId)->first();

        $this->blood_group             = $record?->blood_group ?? 'unknown';
        $this->height_cm               = (string) ($record?->height_cm ?? '');
        $this->weight_kg               = (string) ($record?->weight_kg ?? '');
        $this->vision_left             = $record?->vision_left ?? '';
        $this->vision_right            = $record?->vision_right ?? '';
        $this->allergies               = $record?->allergies ?? '';
        $this->chronic_conditions      = $record?->chronic_conditions ?? '';
        $this->disabilities            = $record?->disabilities ?? '';
        $this->emergency_contact_name  = $record?->emergency_contact_name ?? '';
        $this->emergency_contact_phone = $record?->emergency_contact_phone ?? '';
        $this->doctor_name             = $record?->doctor_name ?? '';
        $this->doctor_phone            = $record?->doctor_phone ?? '';
        $this->notes                   = $record?->notes ?? '';

        $this->showRecordForm = true;
        $this->showVaccinationForm = false;
        $this->resetValidation();
    }

    public function saveRecord(): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        $validated = $this->validate($this->recordRules());

        StudentMedicalRecord::updateOrCreate(
            ['student_id' => $this->selectedStudentId],
            array_merge($validated, [
                'height_cm'  => $this->height_cm  !== '' ? $this->height_cm  : null,
                'weight_kg'  => $this->weight_kg  !== '' ? $this->weight_kg  : null,
                'updated_by' => Auth::id(),
            ])
        );

        session()->flash('success', 'Medical record saved.');
        $this->showRecordForm = false;
        $this->resetValidation();
    }

    public function openVaccinationForm(): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        $this->resetVaccinationForm();
        $this->date_administered   = now()->format('Y-m-d');
        $this->showVaccinationForm = true;
        $this->showRecordForm      = false;
    }

    public function editVaccination(int $id): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        $v = StudentVaccination::where('student_id', $this->selectedStudentId)->findOrFail($id);

        $this->editVaccineId    = $v->id;
        $this->vaccine_name     = $v->vaccine_name;
        $this->date_administered = $v->date_administered->format('Y-m-d');
        $this->next_due_date    = $v->next_due_date?->format('Y-m-d') ?? '';
        $this->administered_by  = $v->administered_by ?? '';
        $this->vaccine_notes    = $v->notes ?? '';

        $this->showVaccinationForm = true;
        $this->showRecordForm      = false;
        $this->resetValidation();
    }

    public function saveVaccination(): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        $this->validate($this->vaccinationRules());

        $data = [
            'student_id'       => $this->selectedStudentId,
            'vaccine_name'     => $this->vaccine_name,
            'date_administered' => $this->date_administered,
            'next_due_date'    => $this->next_due_date ?: null,
            'administered_by'  => $this->administered_by ?: null,
            'notes'            => $this->vaccine_notes ?: null,
        ];

        if ($this->editVaccineId) {
            StudentVaccination::where('student_id', $this->selectedStudentId)
                ->findOrFail($this->editVaccineId)
                ->update($data);
            session()->flash('success', 'Vaccination updated.');
        } else {
            StudentVaccination::create($data);
            session()->flash('success', 'Vaccination recorded.');
        }

        $this->resetVaccinationForm();
        $this->showVaccinationForm = false;
    }

    public function deleteVaccination(int $id): void
    {
        abort_unless(Auth::user()->can('medical.edit'), 403);
        StudentVaccination::where('student_id', $this->selectedStudentId)->findOrFail($id)->delete();
        session()->flash('success', 'Vaccination deleted.');
    }

    public function cancelForm(): void
    {
        $this->showRecordForm      = false;
        $this->showVaccinationForm = false;
        $this->resetVaccinationForm();
        $this->resetValidation();
    }

    private function resetVaccinationForm(): void
    {
        $this->reset(['editVaccineId', 'vaccine_name', 'date_administered', 'next_due_date', 'administered_by', 'vaccine_notes']);
        $this->resetValidation();
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get(['id', 'name']);
    }

    public function getSelectedStudentProperty(): ?Student
    {
        if (!$this->selectedStudentId) return null;
        return Student::allowedForUser(Auth::user())
            ->with(['studentClass:id,name', 'section:id,name', 'medicalRecord', 'vaccinations'])
            ->find($this->selectedStudentId);
    }

    public function render()
    {
        $user = Auth::user();

        $students = Student::allowedForUser($user)
            ->with([
                'studentClass:id,name',
                'section:id,name',
                'medicalRecord:student_id,blood_group,allergies,updated_at',
            ])
            ->when($this->filter_class, fn($q) => $q->where('student_class_id', $this->filter_class))
            ->when($this->filter_blood_group, fn($q) =>
                $q->whereHas('medicalRecord', fn($mq) =>
                    $mq->where('blood_group', $this->filter_blood_group)
                )
            )
            ->orderBy('first_name')
            ->paginate(20);

        return view('livewire.medical.medical-record-manager', [
            'students'        => $students,
            'classes'         => $this->classes,
            'selectedStudent' => $this->selectedStudent,
            'canEdit'         => $user->can('medical.edit'),
            'canView'         => $user->can('medical.view'),
        ]);
    }
}
