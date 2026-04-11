<?php

namespace App\Livewire\Fees;

use App\Models\FeeStructure as FeeStructureModel;
use App\Models\FeeType;
use App\Models\StudentClass;
use Livewire\Component;
use Livewire\WithPagination;

class FeeStructure extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $student_class_id = '';
    public $fee_type_id = '';
    public $amount = '';
    public $status = '1';

    public $editId = null;
    public $showForm = false;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function rules()
    {
        return [
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'student_class_id' => 'class',
            'fee_type_id' => 'fee type',
            'amount' => 'amount',
            'status' => 'status',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get();
    }

    public function getFeeTypesProperty()
    {
        return FeeType::where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $exists = FeeStructureModel::where('student_class_id', $this->student_class_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->exists();

        if ($exists) {
            $this->addError('fee_type_id', 'Fee structure already exists for this class and fee type.');
            return;
        }

        FeeStructureModel::create([
            'student_class_id' => $this->student_class_id,
            'fee_type_id' => $this->fee_type_id,
            'amount' => $this->amount,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Fee structure created successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit($id)
    {
        $feeStructure = FeeStructureModel::findOrFail($id);

        $this->editId = $feeStructure->id;
        $this->student_class_id = $feeStructure->student_class_id;
        $this->fee_type_id = $feeStructure->fee_type_id;
        $this->amount = $feeStructure->amount;
        $this->status =  $feeStructure->status;
        $this->showForm = true;

        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        $feeStructure = FeeStructureModel::findOrFail($this->editId);

        $exists = FeeStructureModel::where('student_class_id', $this->student_class_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->where('id', '!=', $this->editId)
            ->exists();

        if ($exists) {
            $this->addError('fee_type_id', 'Fee structure already exists for this class and fee type.');
            return;
        }

        $feeStructure->update([
            'student_class_id' => $this->student_class_id,
            'fee_type_id' => $this->fee_type_id,
            'amount' => $this->amount,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Fee structure updated successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        $feeStructure = FeeStructureModel::findOrFail($id);
        $feeStructure->delete();

        session()->flash('success', 'Fee structure deleted successfully.');

        if ($this->editId == $id) {
            $this->cancel();
        }

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'student_class_id',
            'fee_type_id',
            'amount',
            'status',
            'editId',
        ]);

        $this->status = '1';

        $this->resetValidation();
    }

    public function render()
    {
        $feeStructures = FeeStructureModel::with(['studentClass:id,name', 'feeType:id,name'])
            ->when($this->search, function ($query) {
                $query->whereHas('studentClass', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('feeType', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('amount', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.fees.fee-structure', [
            'feeStructures' => $feeStructures,
            'classes' => $this->classes,
            'feeTypes' => $this->feeTypes,
        ]);
    }
}
