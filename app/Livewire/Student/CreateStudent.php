<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Student;
use App\Models\Section;
use App\Models\StudentClass;
use Livewire\WithFileUploads;
use App\Models\StudentAttachment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CreateStudent extends Component
{
    use WithFileUploads;

    public string $admission_no = '';
    public string $roll_no = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $gender = '';
    public string $date_of_birth = '';

    public string $phone = '';
    public string $email = '';

    public string $father_name = '';
    public string $mother_name = '';
    public string $guardian_phone = '';

    public string $address = '';
    public string $admission_date = '';

    public string $student_class_id = '';
    public string $section_id = '';

    public $status = 1;

    public $student_photo = null;
    public $student_bform = null;
    public $student_cnic = null;
    public $father_cnic = null;
    public $mother_cnic = null;
    public $guardian_cnic = null;
    public $other_documents = [];

    public $classes = [];
    public $sections = [];

    public function mount()
    {
        $this->classes = StudentClass::where('status', 1)->latest()->get();
        $this->sections = collect();
        $this->admission_no = $this->generateAdmissionNo();
    }

    public function updatedStudentClassId($value)
    {
        $this->loadSectionsByClass($value);
        $this->roll_no = $value ? (string) $this->generateRollNo($value) : '';
    }

    public function loadSectionsByClass($classId)
    {
        if (empty($classId)) {
            $this->sections = collect();
            $this->section_id = '';
            return;
        }

        $studentClass = StudentClass::with([
            'sections' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }
        ])->find($classId);

        $this->sections = $studentClass ? $studentClass->sections : collect();

        if (!collect($this->sections)->pluck('id')->map(fn ($id) => (string) $id)->contains((string) $this->section_id)) {
            $this->section_id = '';
        }
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',

            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',

            'father_name' => 'required|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:255',

            'address' => 'nullable|string',
            'admission_date' => 'nullable|date',

            'student_class_id' => 'required|exists:student_classes,id',
            'section_id' => [
                'required',
                Rule::exists('class_section', 'section_id')->where(function ($query) {
                    $query->where('student_class_id', $this->student_class_id);
                }),
            ],

            'student_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'student_bform' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'student_cnic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'father_cnic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'mother_cnic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'guardian_cnic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'other_documents' => 'nullable|array',
            'other_documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:4096',

            'status' => 'required|boolean',
        ];
    }

    protected $messages = [
        'first_name.required' => 'First name is required.',
        'father_name.required' => 'Father name is required.',
        'student_class_id.required' => 'Please select class.',
        'section_id.required' => 'Please select section.',
        'section_id.exists' => 'Selected section does not belong to the selected class.',
    ];

    public function saveStudent()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $admissionNo = $this->generateAdmissionNo();
            $rollNo = $this->generateRollNo($validated['student_class_id']);

            $student = Student::create([
                'admission_no' => $admissionNo,
                'roll_no' => $rollNo,
                'first_name' => $validated['first_name'],
                'last_name' => $this->emptyToNull($validated['last_name'] ?? null),
                'gender' => $this->emptyToNull($validated['gender'] ?? null),
                'date_of_birth' => $this->emptyToNull($validated['date_of_birth'] ?? null),

                'phone' => $this->emptyToNull($validated['phone'] ?? null),
                'email' => $this->emptyToNull($validated['email'] ?? null),

                'father_name' => $validated['father_name'],
                'mother_name' => $this->emptyToNull($validated['mother_name'] ?? null),
                'guardian_phone' => $this->emptyToNull($validated['guardian_phone'] ?? null),

                'address' => $this->emptyToNull($validated['address'] ?? null),
                'admission_date' => $this->emptyToNull($validated['admission_date'] ?? null),

                'student_class_id' => $validated['student_class_id'],
                'section_id' => $validated['section_id'],
                'status' => (bool) $validated['status'],
            ]);

            $this->saveAttachmentIfUploaded($student->id, $this->student_photo, 'student_photo', 'Student Photo');
            $this->saveAttachmentIfUploaded($student->id, $this->student_bform, 'student_bform', 'Student B-Form');
            $this->saveAttachmentIfUploaded($student->id, $this->student_cnic, 'student_cnic', 'Student CNIC');
            $this->saveAttachmentIfUploaded($student->id, $this->father_cnic, 'father_cnic', 'Father CNIC');
            $this->saveAttachmentIfUploaded($student->id, $this->mother_cnic, 'mother_cnic', 'Mother CNIC');
            $this->saveAttachmentIfUploaded($student->id, $this->guardian_cnic, 'guardian_cnic', 'Guardian CNIC');

            if (!empty($this->other_documents)) {
                foreach ($this->other_documents as $index => $file) {
                    if ($file) {
                        $this->saveAttachmentIfUploaded(
                            $student->id,
                            $file,
                            'other',
                            'Other Document ' . ($index + 1)
                        );
                    }
                }
            }
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Student added successfully with attachments.');
    }

    private function generateAdmissionNo(): string
    {
        $year = date('Y');

        $lastStudent = Student::where('admission_no', 'like', 'ADM-%-' . $year)
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastStudent && !empty($lastStudent->admission_no)) {
            if (preg_match('/ADM-(\d+)-' . $year . '/', $lastStudent->admission_no, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            }
        }

        return 'ADM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }

    private function generateRollNo($classId): int
    {
        $lastStudent = Student::where('student_class_id', $classId)
            ->orderByRaw('CAST(roll_no AS UNSIGNED) DESC')
            ->first();

        return $lastStudent ? ((int) $lastStudent->roll_no + 1) : 1;
    }

    private function saveAttachmentIfUploaded($studentId, $uploadedFile, $documentType, $title = null)
    {
        if (!$uploadedFile) {
            return;
        }

        $folder = $documentType === 'student_photo'
            ? 'students/photos'
            : 'students/attachments';

        $path = $uploadedFile->store($folder, 'public');

        StudentAttachment::create([
            'student_id' => $studentId,
            'document_type' => $documentType,
            'title' => $title,
            'file_path' => $path,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_extension' => $uploadedFile->getClientOriginalExtension(),
            'file_size' => $uploadedFile->getSize(),
            'status' => 1,
        ]);
    }

    private function emptyToNull($value)
    {
        return $value === '' ? null : $value;
    }

    public function render()
    {
        return view('livewire.student.create-student');
    }
}
