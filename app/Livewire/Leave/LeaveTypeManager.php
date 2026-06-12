<?php

namespace App\Livewire\Leave;

use App\Models\LeaveType;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveTypeManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $name            = '';
    public int    $max_days        = 0;
    public string $applicable_to   = 'both';
    public bool   $is_paid         = true;
    public string $description     = '';
    public bool   $status          = true;

    public ?int  $editId   = null;
    public bool  $showForm = false;

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'max_days'      => ['required', 'integer', 'min:0'],
            'applicable_to' => ['required', 'in:staff,student,both'],
            'is_paid'       => ['boolean'],
            'description'   => ['nullable', 'string'],
            'status'        => ['boolean'],
        ];
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        LeaveType::create([
            'name'              => $this->name,
            'max_days_per_year' => $this->max_days,
            'applicable_to'     => $this->applicable_to,
            'is_paid'           => $this->is_paid,
            'description'       => $this->description ?: null,
            'status'            => $this->status,
        ]);
        session()->flash('type_success', 'Leave type created.');
        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $t = LeaveType::findOrFail($id);
        $this->editId        = $t->id;
        $this->name          = $t->name;
        $this->max_days      = $t->max_days_per_year;
        $this->applicable_to = $t->applicable_to;
        $this->is_paid       = $t->is_paid;
        $this->description   = $t->description ?? '';
        $this->status        = $t->status;
        $this->showForm = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();
        LeaveType::findOrFail($this->editId)->update([
            'name'              => $this->name,
            'max_days_per_year' => $this->max_days,
            'applicable_to'     => $this->applicable_to,
            'is_paid'           => $this->is_paid,
            'description'       => $this->description ?: null,
            'status'            => $this->status,
        ]);
        session()->flash('type_success', 'Leave type updated.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        LeaveType::findOrFail($id)->delete();
        session()->flash('type_success', 'Leave type deleted.');
        if ($this->editId === $id) { $this->resetForm(); $this->showForm = false; }
        $this->resetPage();
    }

    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    public function resetForm(): void
    {
        $this->reset(['editId', 'name', 'description']);
        $this->max_days = 0;
        $this->applicable_to = 'both';
        $this->is_paid = true;
        $this->status  = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.leave.leave-type-manager', [
            'types' => LeaveType::latest()->paginate(15),
        ]);
    }
}
