<?php

namespace App\Livewire\Sports;

use App\Models\SportsActivity;
use App\Models\Student;
use App\Models\StudentActivityEnrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SportsManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ── Activity form ──
    public bool   $showActivityForm = false;
    public ?int   $editActivityId   = null;
    public string $name             = '';
    public string $category         = 'sport';
    public string $description      = '';
    public string $coach_name       = '';
    public string $venue            = '';
    public string $schedule         = '';
    public string $max_members      = '0';
    public bool   $act_status       = true;

    // ── Enrollment ──
    public ?int   $selectedActivityId = null;
    public bool   $showEnrollForm     = false;
    public ?int   $editEnrollId       = null;
    public string $enroll_student_id  = '';
    public string $enroll_role        = 'member';
    public string $enroll_joined_date = '';
    public string $enroll_notes       = '';
    public bool   $enroll_status      = true;
    public string $filter_category    = '';

    public function activityRules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'in:sport,club,art,other'],
            'coach_name'  => ['nullable', 'string', 'max:150'],
            'venue'       => ['nullable', 'string', 'max:200'],
            'schedule'    => ['nullable', 'string', 'max:200'],
            'max_members' => ['required', 'integer', 'min:0'],
            'act_status'  => ['boolean'],
        ];
    }

    public function openActivityForm(): void
    {
        $this->resetActivityForm();
        $this->showActivityForm = true;
        $this->selectedActivityId = null;
    }

    public function editActivity(int $id): void
    {
        $a = SportsActivity::findOrFail($id);
        $this->editActivityId = $a->id;
        $this->name           = $a->name;
        $this->category       = $a->category;
        $this->description    = $a->description ?? '';
        $this->coach_name     = $a->coach_name ?? '';
        $this->venue          = $a->venue ?? '';
        $this->schedule       = $a->schedule ?? '';
        $this->max_members    = (string) $a->max_members;
        $this->act_status     = $a->status;
        $this->showActivityForm = true;
        $this->selectedActivityId = null;
        $this->resetValidation();
    }

    public function saveActivity(): void
    {
        abort_unless(Auth::user()->can('sports.manage'), 403);
        $this->validate($this->activityRules());

        $data = [
            'name'        => $this->name,
            'category'    => $this->category,
            'description' => $this->description ?: null,
            'coach_name'  => $this->coach_name ?: null,
            'venue'       => $this->venue ?: null,
            'schedule'    => $this->schedule ?: null,
            'max_members' => $this->max_members,
            'status'      => $this->act_status,
        ];

        if ($this->editActivityId) {
            SportsActivity::findOrFail($this->editActivityId)->update($data);
            session()->flash('success', 'Activity updated.');
        } else {
            SportsActivity::create($data);
            session()->flash('success', 'Activity created.');
        }
        $this->resetActivityForm();
        $this->showActivityForm = false;
    }

    public function deleteActivity(int $id): void
    {
        abort_unless(Auth::user()->can('sports.manage'), 403);
        SportsActivity::findOrFail($id)->delete();
        session()->flash('success', 'Activity deleted.');
        $this->selectedActivityId = null;
    }

    public function selectActivity(int $id): void
    {
        $this->selectedActivityId = $id;
        $this->showActivityForm   = false;
        $this->showEnrollForm     = false;
        $this->resetEnrollForm();
    }

    public function deselectActivity(): void
    {
        $this->selectedActivityId = null;
        $this->showEnrollForm     = false;
        $this->resetEnrollForm();
    }

    public function openEnrollForm(): void
    {
        $this->resetEnrollForm();
        $this->enroll_joined_date = now()->format('Y-m-d');
        $this->showEnrollForm     = true;
    }

    public function editEnroll(int $id): void
    {
        $e = StudentActivityEnrollment::findOrFail($id);
        $this->editEnrollId       = $e->id;
        $this->enroll_student_id  = (string) $e->student_id;
        $this->enroll_role        = $e->role;
        $this->enroll_joined_date = $e->joined_date->format('Y-m-d');
        $this->enroll_notes       = $e->notes ?? '';
        $this->enroll_status      = $e->status;
        $this->showEnrollForm     = true;
        $this->resetValidation();
    }

    public function saveEnroll(): void
    {
        abort_unless(Auth::user()->can('sports.manage'), 403);
        $this->validate([
            'enroll_student_id'  => ['required', 'exists:students,id'],
            'enroll_role'        => ['required', 'in:member,captain,coordinator'],
            'enroll_joined_date' => ['required', 'date'],
        ]);

        $activity = SportsActivity::findOrFail($this->selectedActivityId);

        // Capacity check
        if (!$this->editEnrollId && $activity->is_full) {
            session()->flash('error', 'This activity has reached its maximum members.');
            return;
        }

        // Duplicate check
        if (!$this->editEnrollId) {
            $exists = StudentActivityEnrollment::where('student_id', $this->enroll_student_id)
                ->where('sports_activity_id', $this->selectedActivityId)->exists();
            if ($exists) {
                session()->flash('error', 'Student is already enrolled in this activity.');
                return;
            }
        }

        $data = [
            'student_id'         => $this->enroll_student_id,
            'sports_activity_id' => $this->selectedActivityId,
            'role'               => $this->enroll_role,
            'joined_date'        => $this->enroll_joined_date,
            'notes'              => $this->enroll_notes ?: null,
            'status'             => $this->enroll_status,
        ];

        if ($this->editEnrollId) {
            StudentActivityEnrollment::findOrFail($this->editEnrollId)->update($data);
            session()->flash('success', 'Enrollment updated.');
        } else {
            StudentActivityEnrollment::create($data);
            session()->flash('success', 'Student enrolled.');
        }
        $this->resetEnrollForm();
        $this->showEnrollForm = false;
    }

    public function deleteEnroll(int $id): void
    {
        abort_unless(Auth::user()->can('sports.manage'), 403);
        StudentActivityEnrollment::findOrFail($id)->delete();
        session()->flash('success', 'Enrollment removed.');
    }

    public function cancelForms(): void
    {
        $this->showActivityForm = false;
        $this->showEnrollForm   = false;
        $this->resetActivityForm();
        $this->resetEnrollForm();
    }

    private function resetActivityForm(): void
    {
        $this->reset(['editActivityId', 'name', 'description', 'coach_name', 'venue', 'schedule']);
        $this->category    = 'sport';
        $this->max_members = '0';
        $this->act_status  = true;
        $this->resetValidation();
    }

    private function resetEnrollForm(): void
    {
        $this->reset(['editEnrollId', 'enroll_student_id', 'enroll_joined_date', 'enroll_notes']);
        $this->enroll_role   = 'member';
        $this->enroll_status = true;
        $this->resetValidation();
    }

    public function getStudentsListProperty()
    {
        return Student::with('studentClass:id,name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_class_id']);
    }

    public function getSelectedActivityProperty(): ?SportsActivity
    {
        if (!$this->selectedActivityId) return null;
        return SportsActivity::withCount(['enrollments', 'activeEnrollments'])
            ->find($this->selectedActivityId);
    }

    public function render()
    {
        $canManage = Auth::user()->can('sports.manage');

        $activities = SportsActivity::withCount(['enrollments', 'activeEnrollments'])
            ->when($this->filter_category, fn($q) => $q->where('category', $this->filter_category))
            ->orderBy('category')->orderBy('name')
            ->paginate(15);

        $enrollments = $this->selectedActivityId
            ? StudentActivityEnrollment::with('student:id,first_name,last_name,student_class_id', 'student.studentClass:id,name')
                ->where('sports_activity_id', $this->selectedActivityId)
                ->latest()->get()
            : collect();

        return view('livewire.sports.sports-manager', [
            'activities'       => $activities,
            'enrollments'      => $enrollments,
            'studentsList'     => $this->selectedActivity ? $this->studentsList : collect(),
            'selectedActivity' => $this->selectedActivity,
            'canManage'        => $canManage,
        ]);
    }
}
