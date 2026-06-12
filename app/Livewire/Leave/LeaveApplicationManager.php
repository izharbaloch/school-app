<?php

namespace App\Livewire\Leave;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveApplicationManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ── Filters ──
    public string $filter_status = '';
    public string $filter_type   = '';
    public string $filter_scope  = ''; // 'staff' | 'student'

    // ── Form ──
    public bool   $showForm       = false;
    public ?int   $editId         = null;
    public string $leave_type_id  = '';
    public string $from_date      = '';
    public string $to_date        = '';
    public string $reason         = '';
    public int    $total_days     = 0;

    protected $queryString = [
        'filter_status' => ['except' => ''],
        'filter_type'   => ['except' => ''],
        'filter_scope'  => ['except' => ''],
    ];

    public function rules(): array
    {
        return [
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'from_date'     => ['required', 'date'],
            'to_date'       => ['required', 'date', 'after_or_equal:from_date'],
            'reason'        => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    protected $messages = [
        'leave_type_id.required' => 'Please select a leave type.',
        'reason.min'             => 'Please provide a reason of at least 10 characters.',
        'to_date.after_or_equal' => 'End date must be on or after start date.',
    ];

    public function updatedFromDate(): void { $this->recalcDays(); }
    public function updatedToDate(): void   { $this->recalcDays(); }

    private function recalcDays(): void
    {
        if ($this->from_date && $this->to_date) {
            try {
                $from = Carbon::parse($this->from_date);
                $to   = Carbon::parse($this->to_date);
                if ($to->gte($from)) {
                    $this->total_days = LeaveApplication::workingDays($from, $to);
                    return;
                }
            } catch (\Exception $e) {}
        }
        $this->total_days = 0;
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        $isStudent = $user->hasRole('student');

        LeaveApplication::create([
            'applicant_type' => $isStudent ? 'student' : 'staff',
            'user_id'        => $isStudent ? null : $user->id,
            'student_id'     => $isStudent ? ($user->student?->id) : null,
            'leave_type_id'  => $this->leave_type_id,
            'from_date'      => $this->from_date,
            'to_date'        => $this->to_date,
            'total_days'     => $this->total_days ?: LeaveApplication::workingDays(
                Carbon::parse($this->from_date), Carbon::parse($this->to_date)
            ),
            'reason'         => $this->reason,
            'status'         => LeaveApplication::STATUS_PENDING,
            'created_by'     => $user->id,
        ]);

        session()->flash('success', 'Leave application submitted.');
        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $app = LeaveApplication::findOrFail($id);
        abort_unless($app->status === LeaveApplication::STATUS_PENDING, 403);

        $this->editId        = $app->id;
        $this->leave_type_id = (string) $app->leave_type_id;
        $this->from_date     = $app->from_date->format('Y-m-d');
        $this->to_date       = $app->to_date->format('Y-m-d');
        $this->reason        = $app->reason;
        $this->total_days    = $app->total_days;
        $this->showForm      = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();

        $app = LeaveApplication::findOrFail($this->editId);
        abort_unless($app->status === LeaveApplication::STATUS_PENDING, 403);

        $app->update([
            'leave_type_id' => $this->leave_type_id,
            'from_date'     => $this->from_date,
            'to_date'       => $this->to_date,
            'total_days'    => $this->total_days,
            'reason'        => $this->reason,
        ]);

        session()->flash('success', 'Application updated.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function approve(int $id): void
    {
        abort_unless(Auth::user()->can('leaves.approve'), 403);
        LeaveApplication::findOrFail($id)->update([
            'status'      => LeaveApplication::STATUS_APPROVED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);
        session()->flash('success', 'Leave approved.');
    }

    public function reject(int $id, string $reason = ''): void
    {
        abort_unless(Auth::user()->can('leaves.approve'), 403);
        LeaveApplication::findOrFail($id)->update([
            'status'           => LeaveApplication::STATUS_REJECTED,
            'rejection_reason' => $reason ?: 'Rejected by administrator.',
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);
        session()->flash('success', 'Leave rejected.');
    }

    public function withdraw(int $id): void
    {
        $app = LeaveApplication::findOrFail($id);
        abort_unless($app->status === LeaveApplication::STATUS_PENDING, 403);
        $app->delete();
        session()->flash('success', 'Application withdrawn.');
        $this->resetPage();
    }

    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    public function resetForm(): void
    {
        $this->reset(['editId', 'leave_type_id', 'from_date', 'to_date', 'reason']);
        $this->total_days = 0;
        $this->resetValidation();
    }

    public function getLeaveTypesProperty()
    {
        $user = Auth::user();
        $applicantType = $user->hasRole('student') ? 'student' : 'staff';
        return LeaveType::active()->forApplicant($applicantType)->orderBy('name')->get();
    }

    public function getAllLeaveTypesProperty()
    {
        return LeaveType::active()->orderBy('name')->get();
    }

    public function getIsApproverProperty(): bool
    {
        return Auth::user()->can('leaves.approve');
    }

    public function getCanApplyProperty(): bool
    {
        return Auth::user()->can('leaves.apply');
    }

    public function getStatusCountsProperty()
    {
        return LeaveApplication::allowedForUser(Auth::user())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function render()
    {
        $user = Auth::user();

        $query = LeaveApplication::with(['leaveType:id,name', 'user:id,name', 'student:id,first_name,last_name', 'reviewer:id,name'])
            ->allowedForUser($user)
            ->when($this->filter_status, fn($q) => $q->where('status', $this->filter_status))
            ->when($this->filter_type,   fn($q) => $q->where('leave_type_id', $this->filter_type))
            ->when($this->filter_scope === 'staff',   fn($q) => $q->where('applicant_type', 'staff'))
            ->when($this->filter_scope === 'student', fn($q) => $q->where('applicant_type', 'student'))
            ->latest()
            ->paginate(15);

        return view('livewire.leave.leave-application-manager', [
            'applications' => $query,
            'leaveTypes'   => $this->leaveTypes,
            'allTypes'     => $this->allLeaveTypes,
            'statusCounts' => $this->statusCounts,
            'isApprover'   => $this->isApprover,
            'canApply'     => $this->canApply,
        ]);
    }
}
