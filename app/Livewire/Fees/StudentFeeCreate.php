<?php

namespace App\Livewire\Fees;

use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentFee;
use Livewire\Component;

class StudentFeeCreate extends Component
{
    public $student_class_id = '';
    public $section_id = '';
    public $student_id = '';
    public $fee_type_id = '';

    public $month = '';
    public $year = '';
    public $amount = '';
    public $due_date = '';
    public $discount = 0;
    public $fine = 0;
    public $remarks = '';

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function rules()
    {
        return [
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'student_id' => ['required', 'exists:students,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'student_class_id' => 'class',
            'section_id' => 'section',
            'student_id' => 'student',
            'fee_type_id' => 'fee type',
        ];
    }

    public function updatedStudentClassId()
    {
        $this->section_id = '';
        $this->student_id = '';
        $this->setSuggestedAmount();
    }

    public function updatedSectionId()
    {
        $this->student_id = '';
    }

    public function updatedFeeTypeId()
    {
        $this->setSuggestedAmount();
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

    public function getStudentsProperty()
    {
        if (!$this->student_class_id || !$this->section_id) {
            return collect();
        }

        return Student::with(['studentClass', 'section'])
            ->where('student_class_id', $this->student_class_id)
            ->where('section_id', $this->section_id)
            ->orderBy('roll_no')
            ->orderBy('first_name')
            ->get();
    }

    public function getFeeTypesProperty()
    {
        return FeeType::where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function setSuggestedAmount()
    {
        if (!$this->student_class_id || !$this->fee_type_id) {
            return;
        }

        $structure = FeeStructure::where('student_class_id', $this->student_class_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->first();

        if ($structure && (!$this->amount || $this->amount == 0)) {
            $this->amount = $structure->amount;
        }
    }

    public function save()
    {
        $this->validate();

        $studentExists = Student::where('id', $this->student_id)
            ->where('student_class_id', $this->student_class_id)
            ->where('section_id', $this->section_id)
            ->exists();

        if (!$studentExists) {
            $this->addError('student_id', 'Selected student does not belong to selected class and section.');
            return;
        }

        $exists = StudentFee::where('student_id', $this->student_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->exists();

        if ($exists) {
            $this->addError('student_id', 'This fee already exists for the selected student.');
            return;
        }

        StudentFee::create([
            'student_id' => $this->student_id,
            'fee_type_id' => $this->fee_type_id,
            'month' => $this->month,
            'year' => $this->year,
            'amount' => $this->amount,
            'discount' => $this->discount ?? 0,
            'fine' => $this->fine ?? 0,
            'paid_amount' => 0,
            'due_date' => $this->due_date ?: null,
            'status' => StudentFee::UNPAID,
            'remarks' => $this->remarks,
        ]);

        session()->flash('success', 'Student fee assigned successfully.');

        $this->reset([
            'student_class_id',
            'section_id',
            'student_id',
            'fee_type_id',
            'amount',
            'due_date',
            'discount',
            'fine',
            'remarks',
        ]);

        $this->month = now()->month;
        $this->year = now()->year;
        $this->discount = 0;
        $this->fine = 0;
    }

    public function render()
    {
        return view('livewire.fees.student-fee-create', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'students' => $this->students,
            'feeTypes' => $this->feeTypes,
        ]);
    }
}
