<?php

namespace App\Livewire\Fees;

use App\Models\FeeType as FeeTypeModel;
use Livewire\Component;
use Livewire\WithPagination;

class FeeType extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name = '';
    public $is_monthly = '0';
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
            'name' => ['required', 'string', 'max:255'],
            'is_monthly' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'name' => 'fee type name',
            'is_monthly' => 'monthly',
            'status' => 'status',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        FeeTypeModel::create([
            'name' => $this->name,
            'is_monthly' =>  $this->is_monthly,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Fee type created successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit($id)
    {
        $feeType = FeeTypeModel::findOrFail($id);

        $this->editId = $feeType->id;
        $this->name = $feeType->name;
        $this->is_monthly =  $feeType->is_monthly;
        $this->status =  $feeType->status;
        $this->showForm = true;

        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        $feeType = FeeTypeModel::findOrFail($this->editId);

        $feeType->update([
            'name' => $this->name,
            'is_monthly' =>  $this->is_monthly,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Fee type updated successfully.');

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
        $feeType = FeeTypeModel::findOrFail($id);
        $feeType->delete();

        session()->flash('success', 'Fee type deleted successfully.');

        if ($this->editId == $id) {
            $this->cancel();
        }

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'is_monthly',
            'status',
            'editId',
        ]);

        $this->is_monthly = '0';
        $this->status = '1';

        $this->resetValidation();
    }

    public function render()
    {
        $feeTypes = FeeTypeModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.fees.fee-type', compact('feeTypes'));
    }
}
