<?php

namespace App\Livewire\Teachers;

use App\Models\Section;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $showForm = false;

    public $teacherId = null;
    public $employee_no = '';
    public $name = '';
    public $email = '';
    public $phone = '';
    public $cnic = '';
    public $designation = '';
    public $address = '';
    public $student_class_id = '';
    public $section_id = '';
    public $status = '1';

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules(): array
    {
        return [
            'employee_no' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cnic' => ['nullable', 'string', 'max:20'],
            'designation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'student_class_id' => ['nullable', 'exists:student_classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'status' => ['required', 'in:1,0'],
        ];
    }

    protected $messages = [
        'name.required' => 'Teacher name is required.',
        'email.email' => 'Please enter a valid email address.',
        'student_class_id.exists' => 'Selected class is invalid.',
        'section_id.exists' => 'Selected section is invalid.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function updatedStudentClassId(): void
    {
        $this->section_id = '';
    }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        return Section::whereHas('classes', function ($query) {
            $query->where('student_classes.id', $this->student_class_id);
        })->orderBy('name')->get();
    }

    public function save(): void
    {
        $validated = $this->validate();

        if (!empty($validated['section_id']) && empty($validated['student_class_id'])) {
            $this->addError('section_id', 'Please select class first.');
            return;
        }

        DB::transaction(function () use ($validated) {
            $user = null;

            if (!empty($validated['email'])) {
                $user = User::firstOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['name'],
                        'password' => bcrypt('password'),
                    ]
                );

                $user->update([
                    'name' => $validated['name'],
                ]);

                if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole')) {
                    if (!$user->hasRole('teacher')) {
                        $user->assignRole('teacher');
                    }
                }
            }

            Teacher::create([
                'user_id' => $user ? $user->id : null,
                'employee_no' => $this->emptyToNull($validated['employee_no'] ?? null),
                'name' => $validated['name'],
                'email' => $this->emptyToNull($validated['email'] ?? null),
                'phone' => $this->emptyToNull($validated['phone'] ?? null),
                'cnic' => $this->emptyToNull($validated['cnic'] ?? null),
                'designation' => $this->emptyToNull($validated['designation'] ?? null),
                'address' => $this->emptyToNull($validated['address'] ?? null),
                'student_class_id' => $this->emptyToNull($validated['student_class_id'] ?? null),
                'section_id' => $this->emptyToNull($validated['section_id'] ?? null),
                'status' => (int) $validated['status'],
            ]);
        });

        session()->flash('success', 'Teacher added successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $teacher = Teacher::findOrFail($id);

        $this->teacherId = $teacher->id;
        $this->employee_no = $teacher->employee_no;
        $this->name = $teacher->name;
        $this->email = $teacher->email;
        $this->phone = $teacher->phone;
        $this->cnic = $teacher->cnic;
        $this->designation = $teacher->designation;
        $this->address = $teacher->address;
        $this->student_class_id = $teacher->student_class_id ? (string) $teacher->student_class_id : '';
        $this->section_id = $teacher->section_id ? (string) $teacher->section_id : '';
        $this->status = $teacher->status ? '1' : '0';

        $this->showForm = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $validated = $this->validate();

        if (!empty($validated['section_id']) && empty($validated['student_class_id'])) {
            $this->addError('section_id', 'Please select class first.');
            return;
        }

        $teacher = Teacher::findOrFail($this->teacherId);

        DB::transaction(function () use ($validated, $teacher) {
            $user = null;

            if (!empty($validated['email'])) {
                if ($teacher->user_id) {
                    $user = User::find($teacher->user_id);

                    if ($user) {
                        $existingUser = User::where('email', $validated['email'])
                            ->where('id', '!=', $user->id)
                            ->first();

                        if (!$existingUser) {
                            $user->update([
                                'name' => $validated['name'],
                                'email' => $validated['email'],
                            ]);
                        } else {
                            $user = $existingUser;
                            $user->update([
                                'name' => $validated['name'],
                            ]);
                        }
                    }
                }

                if (!$user) {
                    $user = User::firstOrCreate(
                        ['email' => $validated['email']],
                        [
                            'name' => $validated['name'],
                            'password' => bcrypt('password'),
                        ]
                    );

                    $user->update([
                        'name' => $validated['name'],
                    ]);
                }

                if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole')) {
                    if (!$user->hasRole('teacher')) {
                        $user->assignRole('teacher');
                    }
                }
            }

            $teacher->update([
                'user_id' => $user ? $user->id : $teacher->user_id,
                'employee_no' => $this->emptyToNull($validated['employee_no'] ?? null),
                'name' => $validated['name'],
                'email' => $this->emptyToNull($validated['email'] ?? null),
                'phone' => $this->emptyToNull($validated['phone'] ?? null),
                'cnic' => $this->emptyToNull($validated['cnic'] ?? null),
                'designation' => $this->emptyToNull($validated['designation'] ?? null),
                'address' => $this->emptyToNull($validated['address'] ?? null),
                'student_class_id' => $this->emptyToNull($validated['student_class_id'] ?? null),
                'section_id' => $this->emptyToNull($validated['section_id'] ?? null),
                'status' => (int) $validated['status'],
            ]);
        });

        session()->flash('success', 'Teacher updated successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        session()->flash('success', 'Teacher deleted successfully.');

        if ($this->teacherId == $id) {
            $this->cancel();
        }

        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'teacherId',
            'employee_no',
            'name',
            'email',
            'phone',
            'cnic',
            'designation',
            'address',
            'student_class_id',
            'section_id',
        ]);

        $this->status = '1';
        $this->resetValidation();
    }

    private function emptyToNull($value): mixed
    {
        return filled($value) ? $value : null;
    }

    public function render()
    {
        $classes = StudentClass::orderBy('name')->get();

        $teachers = Teacher::query()
            ->with(['studentClass', 'section', 'user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_no', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('cnic', 'like', '%' . $this->search . '%')
                        ->orWhere('designation', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.teachers.teacher-index', compact('teachers', 'classes'));
    }
}
