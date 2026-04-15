<?php

namespace App\Livewire\Fees;

use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentFee;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentFeeBulkGenerate extends Component
{
    public $student_class_id = '';
    public $section_id = '';
    public $fee_type_id = '';

    public $month = '';
    public $year = '';
    public $due_date = '';
    public $amount = '';
    public $discount = 0;
    public $fine = 0;
    public $remarks = '';

    public $selected_students = [];
    public $select_all = false;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function rules()
    {
        return [
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'due_date' => ['nullable', 'date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'selected_students' => ['required', 'array', 'min:1'],
            'selected_students.*' => ['exists:students,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function updatedStudentClassId()
    {
        $this->section_id = '';
        $this->selected_students = [];
        $this->select_all = false;
        $this->amount = '';
        $this->loadStructureAmount();
    }

    public function updatedSectionId()
    {
        $this->selected_students = [];
        $this->select_all = false;
    }

    public function updatedFeeTypeId()
    {
        $this->selected_students = [];
        $this->select_all = false;
        $this->loadStructureAmount();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected_students = $this->students->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selected_students = [];
        }
    }

    public function getClassesProperty()
    {
        return StudentClass::select('id', 'name')->orderBy('name')->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        return Section::select('id', 'name')
            ->whereHas('classes', function ($query) {
                $query->where('student_classes.id', $this->student_class_id);
            })->orderBy('name')->get();
    }

    public function getFeeTypesProperty()
    {
        return FeeType::select('id', 'name')->where('status', true)->orderBy('name')->get();
    }

    public function getStudentsProperty()
    {
        if (!$this->student_class_id) {
            return collect();
        }

        $query = Student::select('id', 'roll_no', 'first_name', 'last_name', 'student_class_id', 'section_id')
            ->with([
                'studentClass:id,name',
                'section:id,name',
            ])
            ->where('student_class_id', $this->student_class_id);

        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        return $query->orderBy('roll_no')->orderBy('first_name')->get();
    }

    public function loadStructureAmount()
    {
        if (!$this->student_class_id || !$this->fee_type_id) {
            return;
        }

        $structure = FeeStructure::select('amount')
            ->where('student_class_id', $this->student_class_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->first();

        $this->amount = $structure?->amount ?? '';
    }

    protected function generateSlipNo()
    {
        $lastId = StudentFee::max('id') + 1;
        return 'SLIP-' . now()->format('Y') . '-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
    }

    public function generate()
    {
        $this->validate();

        $students = Student::where('student_class_id', $this->student_class_id)
            ->whereIn('id', $this->selected_students)
            ->when($this->section_id, fn ($q) => $q->where('section_id', $this->section_id))
            ->get();

        if ($students->isEmpty()) {
            $this->addError('selected_students', 'No students found.');
            return;
        }

        $structure = FeeStructure::where('student_class_id', $this->student_class_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->first();

        $amount = filled($this->amount) ? $this->amount : ($structure?->amount ?? 0);

        if ($amount <= 0) {
            $this->addError('amount', 'Amount not found. Please set fee structure or enter amount manually.');
            return;
        }

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($students, $amount, &$createdCount, &$skippedCount) {
            foreach ($students as $student) {
                $exists = StudentFee::where('student_id', $student->id)
                    ->where('fee_type_id', $this->fee_type_id)
                    ->where('month', $this->month)
                    ->where('year', $this->year)
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                StudentFee::create([
                    'student_id' => $student->id,
                    'fee_type_id' => $this->fee_type_id,
                    'month' => $this->month,
                    'year' => $this->year,
                    'amount' => $amount,
                    'discount' => $this->discount ?? 0,
                    'fine' => $this->fine ?? 0,
                    'paid_amount' => 0,
                    'due_date' => $this->due_date ?: null,
                    'status' => StudentFee::UNPAID,
                    'remarks' => $this->remarks,
                    'slip_no' => $this->generateSlipNo(),
                ]);

                $createdCount++;
            }
        });

        session()->flash('success', "Fee generated successfully. Created: {$createdCount}, Skipped: {$skippedCount}");

        $this->selected_students = [];
        $this->select_all = false;
    }

    public function render()
    {
        return view('livewire.fees.student-fee-bulk-generate', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'feeTypes' => $this->feeTypes,
            'students' => $this->students,
        ]);
    }
}
