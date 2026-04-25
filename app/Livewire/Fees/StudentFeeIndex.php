<?php

namespace App\Livewire\Fees;

use App\Models\FeeType;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudentClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;

class StudentFeeIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $student_class_id = '';
    public $section_id = '';
    public $student_id = '';
    public $fee_type_id = '';
    public $month = '';
    public $year = '';
    public $status = '';

    protected $queryString = [
        'student_class_id' => ['except' => ''],
        'section_id' => ['except' => ''],
        'student_id' => ['except' => ''],
        'fee_type_id' => ['except' => ''],
        'month' => ['except' => ''],
        'year' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingStudentClassId()
    {
        $this->section_id = '';
        $this->student_id = '';
        $this->resetPage();
    }

    public function updatingSectionId()
    {
        $this->student_id = '';
        $this->resetPage();
    }

    public function updatingStudentId()
    {
        $this->resetPage();
    }

    public function updatingFeeTypeId()
    {
        $this->resetPage();
    }

    public function updatingMonth()
    {
        $this->resetPage();
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'student_class_id',
            'section_id',
            'student_id',
            'fee_type_id',
            'month',
            'year',
            'status',
        ]);

        $this->resetPage();
    }

    public function delete($id)
    {
        $fee = StudentFee::findOrFail($id);

        if ($fee->paid_amount > 0) {
            session()->flash('error', 'Paid or partially paid fee cannot be deleted.');
            return;
        }

        $fee->delete();

        session()->flash('success', 'Student fee deleted successfully.');
        $this->resetPage();
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        return Section::whereHas('classes', function ($query) {
            $query->where('student_classes.id', $this->student_class_id);
        })
            ->orderBy('name')
            ->get();
    }

    public function getReportStats()
    {
        $query = StudentFee::query();

        if ($this->student_class_id || $this->section_id) {
            $query->whereHas('student', function ($q) {
                if ($this->student_class_id) {
                    $q->where('student_class_id', $this->student_class_id);
                }
                if ($this->section_id) {
                    $q->where('section_id', $this->section_id);
                }
            });
        }

        if ($this->student_id) {
            $query->where('student_id', $this->student_id);
        }
        if ($this->fee_type_id) {
            $query->where('fee_type_id', $this->fee_type_id);
        }
        if ($this->month) {
            $query->where('month', $this->month);
        }
        if ($this->year) {
            $query->where('year', $this->year);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        $stats = $query->selectRaw('
            SUM(amount + COALESCE(fine, 0) - COALESCE(discount, 0)) as all_amount,
            SUM(paid_amount) as paid_amount
        ')->first();

        $allAmount = $stats->all_amount ?? 0;
        $paidAmount = $stats->paid_amount ?? 0;
        $pendingAmount = $allAmount - $paidAmount;

        return [
            'all_amount' => $allAmount,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
        ];
    }

    public function render()
    {
        $studentFees = StudentFee::select('id', 'student_id', 'fee_type_id', 'month', 'year', 'amount', 'paid_amount', 'status', 'created_at')
            ->with([
                'student:id,first_name,last_name,student_class_id,section_id',
                'student.studentClass:id,name',
                'student.section:id,name',
                'feeType:id,name',
            ])
            ->when($this->student_class_id || $this->section_id, function ($q) {
                $q->whereHas('student', function ($q2) {
                    if ($this->student_class_id) {
                        $q2->where('student_class_id', $this->student_class_id);
                    }
                    if ($this->section_id) {
                        $q2->where('section_id', $this->section_id);
                    }
                });
            })
            ->when($this->student_id, fn ($q) => $q->where('student_id', $this->student_id))
            ->when($this->fee_type_id, fn ($q) => $q->where('fee_type_id', $this->fee_type_id))
            ->when($this->month, fn ($q) => $q->where('month', $this->month))
            ->when($this->year, fn ($q) => $q->where('year', $this->year))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest('id')
            ->paginate(20);

        $students = Student::select('id', 'first_name', 'last_name')
            ->when($this->student_class_id, fn ($q) => $q->where('student_class_id', $this->student_class_id))
            ->when($this->section_id, fn ($q) => $q->where('section_id', $this->section_id))
            ->orderBy('first_name')
            ->get();

        $feeTypes = FeeType::where('status', true)
            ->orderBy('name')
            ->get();

        return view('livewire.fees.student-fee-index', [
            'studentFees' => $studentFees,
            'students' => $students,
            'feeTypes' => $feeTypes,
            'classes' => $this->classes,
            'sections' => $this->sections,
            'reportStats' => $this->getReportStats(),
        ]);
    }
}
