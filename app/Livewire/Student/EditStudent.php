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
use Illuminate\Support\Facades\Storage;


class EditStudent extends Component
{
    use WithFileUploads;

    public Student $student;
    public $editId = null;

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
    public string $guardian_cnic_no = '';

    public string $address = '';
    public string $admission_date = '';

    public string $student_class_id = '';
    public string $section_id = '';

    public $status = 'active';
    public $is_failed = 0;

    public $student_photo = null;
    public $student_bform = null;
    public $student_cnic = null;
    public $father_cnic = null;
    public $mother_cnic = null;
    public $guardian_cnic = null;
    public $other_documents = [];

    public array $oldAttachments = [];

    public $classes = [];
    public $sections = [];

    public function mount(Student $student)
    {
        $this->student = $student->load('attachments');
        $this->editId = $student->id;

        $this->classes = StudentClass::where('status', 1)->latest()->get();

        $this->admission_no = (string) $student->admission_no;
        $this->roll_no = (string) ($student->roll_no ?? '');
        $this->first_name = $student->first_name;
        $this->last_name = (string) ($student->last_name ?? '');
        $this->gender = (string) ($student->gender ?? '');
        $this->date_of_birth = $this->formatDateForInput($student->date_of_birth);

        $this->phone = (string) ($student->phone ?? '');
        $this->email = (string) ($student->email ?? '');

        $this->father_name = $student->father_name;
        $this->mother_name = (string) ($student->mother_name ?? '');
        $this->guardian_phone = (string) ($student->guardian_phone ?? '');
        $this->guardian_cnic_no = (string) ($student->guardian_cnic_no ?? '');

        $this->address = (string) ($student->address ?? '');
        $this->admission_date = $this->formatDateForInput($student->admission_date);

        $this->student_class_id = (string) $student->student_class_id;
        $this->loadSectionsByClass($student->student_class_id);
        $this->section_id = (string) $student->section_id;

        $this->oldAttachments = $student->attachments
            ->groupBy('document_type')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'file_path' => $item->file_path,
                        'file_name' => $item->file_name,
                        'document_type' => $item->document_type,
                    ];
                })->toArray();
            })
            ->toArray();

        $this->status = $student->status;
    }

    public function updatedStudentClassId($value)
    {
        $this->loadSectionsByClass($value);
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
            'guardian_cnic_no' => 'nullable|string|max:255',

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

            'status' => 'required|in:active,inactive,pass_out,dropped,failed',
            'is_failed' => 'boolean',
        ];
    }

    protected $messages = [
        'first_name.required' => 'First name is required.',
        'father_name.required' => 'Father name is required.',
        'student_class_id.required' => 'Please select class.',
        'section_id.required' => 'Please select section.',
        'section_id.exists' => 'Selected section does not belong to the selected class.',
    ];

    public function updateStudent()
    {
        $validated = $this->validate();
        DB::transaction(function () use ($validated) {
            $student = Student::findOrFail($this->editId);

            $student->update([
                'first_name' => $validated['first_name'],
                'last_name' => $this->emptyToNull($validated['last_name'] ?? null),
                'gender' => $this->emptyToNull($validated['gender'] ?? null),
                'date_of_birth' => $this->emptyToNull($validated['date_of_birth'] ?? null),

                'phone' => $this->emptyToNull($validated['phone'] ?? null),
                'email' => $this->emptyToNull($validated['email'] ?? null),

                'father_name' => $validated['father_name'],
                'mother_name' => $this->emptyToNull($validated['mother_name'] ?? null),
                'guardian_phone' => $this->emptyToNull($validated['guardian_phone'] ?? null),
                'guardian_cnic_no' => $this->emptyToNull($validated['guardian_cnic_no'] ?? null),

                'address' => $this->emptyToNull($validated['address'] ?? null),
                'admission_date' => $this->emptyToNull($validated['admission_date'] ?? null),

                'student_class_id' => $validated['student_class_id'],
                'section_id' => $validated['section_id'],
                'status' =>  $validated['status'],
                'is_failed' => (bool) $validated['is_failed'],
            ]);

            $this->replaceSingleAttachment($student->id, 'student_photo', 'Student Photo', $this->student_photo);
            $this->replaceSingleAttachment($student->id, 'student_bform', 'Student B-Form', $this->student_bform);
            $this->replaceSingleAttachment($student->id, 'student_cnic', 'Student CNIC', $this->student_cnic);
            $this->replaceSingleAttachment($student->id, 'father_cnic', 'Father CNIC', $this->father_cnic);
            $this->replaceSingleAttachment($student->id, 'mother_cnic', 'Mother CNIC', $this->mother_cnic);
            $this->replaceSingleAttachment($student->id, 'guardian_cnic', 'Guardian CNIC', $this->guardian_cnic);

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
            ->with('success', 'Student updated successfully.');
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = StudentAttachment::findOrFail($attachmentId);

        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        $this->student = Student::with('attachments')->findOrFail($this->editId);

        $this->oldAttachments = $this->student->attachments
            ->groupBy('document_type')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'file_path' => $item->file_path,
                        'file_name' => $item->file_name,
                        'document_type' => $item->document_type,
                    ];
                })->toArray();
            })
            ->toArray();

        session()->flash('success', 'Attachment deleted successfully.');
    }

    private function replaceSingleAttachment($studentId, $documentType, $title, $uploadedFile)
    {
        if (!$uploadedFile) {
            return;
        }

        $oldAttachment = StudentAttachment::where('student_id', $studentId)
            ->where('document_type', $documentType)
            ->first();

        if ($oldAttachment) {
            if ($oldAttachment->file_path && Storage::disk('public')->exists($oldAttachment->file_path)) {
                Storage::disk('public')->delete($oldAttachment->file_path);
            }

            $oldAttachment->delete();
        }

        $this->saveAttachmentIfUploaded($studentId, $uploadedFile, $documentType, $title);
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

    private function formatDateForInput($value): string
    {
        if (empty($value)) {
            return '';
        }

        return date('Y-m-d', strtotime((string) $value));
    }
    public function render()
    {
        return view('livewire.student.edit-student');
    }
}
