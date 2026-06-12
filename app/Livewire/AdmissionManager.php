<?php

namespace App\Livewire;

use App\Models\Admission;
use App\Models\Section;
use App\Models\StudentClass;
use Livewire\Component;
use Livewire\WithPagination;

class AdmissionManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ── Filters ──
    public string $search        = '';
    public string $filter_status = '';
    public string $filter_class  = '';

    // ── Form mode ──
    public bool   $showForm = false;
    public ?int   $editId   = null;

    // ── Form fields ──
    public string $first_name       = '';
    public string $last_name        = '';
    public string $gender           = '';
    public string $date_of_birth    = '';
    public string $father_name      = '';
    public string $mother_name      = '';
    public string $guardian_phone   = '';
    public string $guardian_email   = '';
    public string $guardian_cnic_no = '';
    public string $address          = '';
    public string $applied_class_id  = '';
    public string $applied_section_id = '';
    public string $academic_year    = '';
    public string $previous_school  = '';
    public string $remarks          = '';

    public array  $sections = [];

    protected $queryString = [
        'search'        => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_class'  => ['except' => ''],
    ];

    public function rules(): array
    {
        return [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['nullable', 'string', 'max:255'],
            'gender'            => ['nullable', 'in:male,female,other'],
            'date_of_birth'     => ['nullable', 'date'],
            'father_name'       => ['required', 'string', 'max:255'],
            'mother_name'       => ['nullable', 'string', 'max:255'],
            'guardian_phone'    => ['nullable', 'string', 'max:30'],
            'guardian_email'    => ['nullable', 'email', 'max:255'],
            'guardian_cnic_no'  => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string'],
            'applied_class_id'  => ['required', 'exists:student_classes,id'],
            'applied_section_id' => ['nullable', 'exists:sections,id'],
            'academic_year'     => ['required', 'string', 'max:20'],
            'previous_school'   => ['nullable', 'string', 'max:255'],
            'remarks'           => ['nullable', 'string'],
        ];
    }

    protected $messages = [
        'first_name.required'       => 'First name is required.',
        'father_name.required'      => 'Father name is required.',
        'applied_class_id.required' => 'Please select the applied class.',
        'academic_year.required'    => 'Academic year is required.',
    ];

    public function updatedAppliedClassId(string $value): void
    {
        $this->applied_section_id = '';
        $this->loadSections($value);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    private function loadSections(string $classId): void
    {
        if (!$classId) {
            $this->sections = [];
            return;
        }

        $class = StudentClass::with([
            'sections' => fn($q) => $q->where('status', 1)->orderBy('name'),
        ])->find($classId);

        $this->sections = $class ? $class->sections->toArray() : [];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->academic_year = date('Y') . '-' . (date('Y') + 1);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        Admission::create([
            'application_no'    => Admission::generateApplicationNo(),
            'first_name'        => $this->first_name,
            'last_name'         => $this->n($this->last_name),
            'gender'            => $this->n($this->gender),
            'date_of_birth'     => $this->n($this->date_of_birth),
            'father_name'       => $this->father_name,
            'mother_name'       => $this->n($this->mother_name),
            'guardian_phone'    => $this->n($this->guardian_phone),
            'guardian_email'    => $this->n($this->guardian_email),
            'guardian_cnic_no'  => $this->n($this->guardian_cnic_no),
            'address'           => $this->n($this->address),
            'applied_class_id'  => $this->applied_class_id,
            'applied_section_id' => $this->n($this->applied_section_id),
            'academic_year'     => $this->academic_year,
            'previous_school'   => $this->n($this->previous_school),
            'remarks'           => $this->n($this->remarks),
            'status'            => Admission::STATUS_PENDING,
            'created_by'        => auth()->id(),
        ]);

        session()->flash('success', 'Application submitted successfully.');
        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $a = Admission::findOrFail($id);

        $this->editId            = $a->id;
        $this->first_name        = $a->first_name;
        $this->last_name         = $a->last_name ?? '';
        $this->gender            = $a->gender ?? '';
        $this->date_of_birth     = $a->date_of_birth?->format('Y-m-d') ?? '';
        $this->father_name       = $a->father_name;
        $this->mother_name       = $a->mother_name ?? '';
        $this->guardian_phone    = $a->guardian_phone ?? '';
        $this->guardian_email    = $a->guardian_email ?? '';
        $this->guardian_cnic_no  = $a->guardian_cnic_no ?? '';
        $this->address           = $a->address ?? '';
        $this->applied_class_id  = (string) $a->applied_class_id;
        $this->applied_section_id = (string) ($a->applied_section_id ?? '');
        $this->academic_year     = $a->academic_year;
        $this->previous_school   = $a->previous_school ?? '';
        $this->remarks           = $a->remarks ?? '';

        $this->loadSections($this->applied_class_id);
        $this->showForm = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();

        $a = Admission::findOrFail($this->editId);
        $a->update([
            'first_name'        => $this->first_name,
            'last_name'         => $this->n($this->last_name),
            'gender'            => $this->n($this->gender),
            'date_of_birth'     => $this->n($this->date_of_birth),
            'father_name'       => $this->father_name,
            'mother_name'       => $this->n($this->mother_name),
            'guardian_phone'    => $this->n($this->guardian_phone),
            'guardian_email'    => $this->n($this->guardian_email),
            'guardian_cnic_no'  => $this->n($this->guardian_cnic_no),
            'address'           => $this->n($this->address),
            'applied_class_id'  => $this->applied_class_id,
            'applied_section_id' => $this->n($this->applied_section_id),
            'academic_year'     => $this->academic_year,
            'previous_school'   => $this->n($this->previous_school),
            'remarks'           => $this->n($this->remarks),
        ]);

        session()->flash('success', 'Application updated.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Admission::findOrFail($id)->delete();
        session()->flash('success', 'Application deleted.');

        if ($this->editId === $id) {
            $this->resetForm();
            $this->showForm = false;
        }
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset([
            'editId', 'first_name', 'last_name', 'gender', 'date_of_birth',
            'father_name', 'mother_name', 'guardian_phone', 'guardian_email',
            'guardian_cnic_no', 'address', 'applied_class_id', 'applied_section_id',
            'academic_year', 'previous_school', 'remarks',
        ]);
        $this->sections = [];
        $this->resetValidation();
    }

    private function n(string $value): ?string
    {
        return $value === '' ? null : $value;
    }

    public function getClassesProperty()
    {
        return StudentClass::where('status', 1)->orderBy('numeric_name')->get(['id', 'name']);
    }

    public function render()
    {
        $admissions = Admission::with(['appliedClass:id,name', 'appliedSection:id,name'])
            ->allowedForUser(auth()->user())
            ->when($this->search, fn($q) =>
                $q->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('father_name', 'like', '%' . $this->search . '%')
                      ->orWhere('application_no', 'like', '%' . $this->search . '%')
                      ->orWhere('guardian_phone', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->filter_status, fn($q) => $q->where('status', $this->filter_status))
            ->when($this->filter_class, fn($q) => $q->where('applied_class_id', $this->filter_class))
            ->latest()
            ->paginate(15);

        $statusCounts = Admission::allowedForUser(auth()->user())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('livewire.admission-manager', [
            'admissions'   => $admissions,
            'classes'      => $this->classes,
            'statusCounts' => $statusCounts,
        ]);
    }
}
