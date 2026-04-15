<?php

namespace App\Livewire\Fees;

use App\Models\FeeType;
use App\Models\Student;
use App\Models\StudentFee;
use Livewire\Component;
use Livewire\WithPagination;

class StudentFeeIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $student_id = '';
    public $fee_type_id = '';
    public $month = '';
    public $year = '';
    public $status = '';

    protected $queryString = [
        'student_id' => ['except' => ''],
        'fee_type_id' => ['except' => ''],
        'month' => ['except' => ''],
        'year' => ['except' => ''],
        'status' => ['except' => ''],
    ];

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

    public function render()
    {
        $studentFees = StudentFee::select('id', 'student_id', 'fee_type_id', 'month', 'year', 'amount', 'paid_amount', 'status', 'created_at')
            ->with([
                'student:id,first_name,last_name,student_class_id,section_id',
                'student.studentClass:id,name',
                'student.section:id,name',
                'feeType:id,name',
            ])
            ->when($this->student_id, fn ($q) => $q->where('student_id', $this->student_id))
            ->when($this->fee_type_id, fn ($q) => $q->where('fee_type_id', $this->fee_type_id))
            ->when($this->month, fn ($q) => $q->where('month', $this->month))
            ->when($this->year, fn ($q) => $q->where('year', $this->year))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest('id')
            ->paginate(20);

        $students = Student::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        $feeTypes = FeeType::where('status', true)
            ->orderBy('name')
            ->get();

        return view('livewire.fees.student-fee-index', compact('studentFees', 'students', 'feeTypes'));
    }
}
