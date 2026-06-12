<?php

namespace App\Livewire\Conduct;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentIncident;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ConductManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ── Filters ──
    public string $filter_class    = '';
    public string $filter_type     = '';
    public string $filter_severity = '';
    public string $filter_status   = '';
    public string $filter_student  = '';

    // ── Form ──
    public bool    $showForm       = false;
    public ?int    $editId         = null;
    public string  $student_id     = '';
    public string  $title          = '';
    public string  $incident_type  = 'other';
    public string  $severity       = 'minor';
    public string  $incident_date  = '';
    public string  $description    = '';
    public string  $action_taken   = '';
    public string  $suspension_from = '';
    public string  $suspension_to   = '';
    public string  $status         = 'open';
    public string  $resolution_notes = '';

    protected $queryString = [
        'filter_class'    => ['except' => ''],
        'filter_type'     => ['except' => ''],
        'filter_severity' => ['except' => ''],
        'filter_status'   => ['except' => ''],
    ];

    public function rules(): array
    {
        $suspensionRules = $this->incident_type === 'suspension'
            ? ['required', 'date']
            : ['nullable', 'date'];

        return [
            'student_id'      => ['required', 'exists:students,id'],
            'title'           => ['required', 'string', 'max:200'],
            'incident_type'   => ['required', 'in:warning,detention,suspension,expulsion,misconduct,other'],
            'severity'        => ['required', 'in:minor,moderate,severe'],
            'incident_date'   => ['required', 'date'],
            'description'     => ['required', 'string', 'min:10'],
            'action_taken'    => ['nullable', 'string'],
            'suspension_from' => $suspensionRules,
            'suspension_to'   => $this->incident_type === 'suspension'
                ? ['required', 'date', 'after_or_equal:suspension_from']
                : ['nullable', 'date'],
            'status'          => ['required', 'in:open,resolved,closed'],
            'resolution_notes' => ['nullable', 'string'],
        ];
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->incident_date = now()->format('Y-m-d');
        $this->showForm = true;
    }

    public function save(): void
    {
        abort_unless(Auth::user()->can('conduct.create'), 403);
        $this->validate();

        StudentIncident::create([
            'student_id'      => $this->student_id,
            'title'           => $this->title,
            'incident_type'   => $this->incident_type,
            'severity'        => $this->severity,
            'incident_date'   => $this->incident_date,
            'description'     => $this->description,
            'action_taken'    => $this->action_taken ?: null,
            'suspension_from' => $this->incident_type === 'suspension' ? $this->suspension_from : null,
            'suspension_to'   => $this->incident_type === 'suspension' ? $this->suspension_to : null,
            'status'          => $this->status,
            'resolution_notes' => $this->resolution_notes ?: null,
            'reported_by'     => Auth::id(),
        ]);

        session()->flash('success', 'Incident recorded.');
        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        abort_unless(Auth::user()->can('conduct.edit'), 403);
        $inc = StudentIncident::allowedForUser(Auth::user())->findOrFail($id);

        $this->editId          = $inc->id;
        $this->student_id      = (string) $inc->student_id;
        $this->title           = $inc->title;
        $this->incident_type   = $inc->incident_type;
        $this->severity        = $inc->severity;
        $this->incident_date   = $inc->incident_date->format('Y-m-d');
        $this->description     = $inc->description;
        $this->action_taken    = $inc->action_taken ?? '';
        $this->suspension_from = $inc->suspension_from?->format('Y-m-d') ?? '';
        $this->suspension_to   = $inc->suspension_to?->format('Y-m-d') ?? '';
        $this->status          = $inc->status;
        $this->resolution_notes = $inc->resolution_notes ?? '';
        $this->showForm = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        abort_unless(Auth::user()->can('conduct.edit'), 403);
        $this->validate();

        StudentIncident::allowedForUser(Auth::user())->findOrFail($this->editId)->update([
            'student_id'      => $this->student_id,
            'title'           => $this->title,
            'incident_type'   => $this->incident_type,
            'severity'        => $this->severity,
            'incident_date'   => $this->incident_date,
            'description'     => $this->description,
            'action_taken'    => $this->action_taken ?: null,
            'suspension_from' => $this->incident_type === 'suspension' ? $this->suspension_from : null,
            'suspension_to'   => $this->incident_type === 'suspension' ? $this->suspension_to : null,
            'status'          => $this->status,
            'resolution_notes' => $this->resolution_notes ?: null,
        ]);

        session()->flash('success', 'Incident updated.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        abort_unless(Auth::user()->can('conduct.delete'), 403);
        StudentIncident::allowedForUser(Auth::user())->findOrFail($id)->delete();
        session()->flash('success', 'Incident deleted.');
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
            'editId', 'student_id', 'title', 'action_taken',
            'description', 'suspension_from', 'suspension_to', 'resolution_notes',
        ]);
        $this->incident_type = 'other';
        $this->severity      = 'minor';
        $this->status        = 'open';
        $this->incident_date = '';
        $this->resetValidation();
    }

    public function getStudentsProperty()
    {
        return Student::allowedForUser(Auth::user())
            ->with('studentClass:id,name', 'section:id,name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_class_id', 'section_id']);
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get(['id', 'name']);
    }

    public function getStatusCountsProperty()
    {
        return StudentIncident::allowedForUser(Auth::user())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function render()
    {
        $user = Auth::user();

        $incidents = StudentIncident::with([
                'student:id,first_name,last_name,student_class_id,section_id',
                'student.studentClass:id,name',
                'student.section:id,name',
                'reporter:id,name',
            ])
            ->allowedForUser($user)
            ->when($this->filter_type,     fn($q) => $q->where('incident_type', $this->filter_type))
            ->when($this->filter_severity, fn($q) => $q->where('severity', $this->filter_severity))
            ->when($this->filter_status,   fn($q) => $q->where('status', $this->filter_status))
            ->when($this->filter_class,    fn($q) => $q->whereHas('student', fn($sq) =>
                $sq->where('student_class_id', $this->filter_class)
            ))
            ->when($this->filter_student,  fn($q) => $q->where('student_id', $this->filter_student))
            ->latest('incident_date')
            ->paginate(15);

        return view('livewire.conduct.conduct-manager', [
            'incidents'    => $incidents,
            'students'     => $this->students,
            'classes'      => $this->classes,
            'statusCounts' => $this->statusCounts,
            'canCreate'    => $user->can('conduct.create'),
            'canEdit'      => $user->can('conduct.edit'),
            'canDelete'    => $user->can('conduct.delete'),
        ]);
    }
}
