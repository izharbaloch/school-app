<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;

class ExamType extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name = '';
    public $academic_year = '';
    public $start_date = '';
    public $end_date = '';
    public $remarks = '';
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
            'academic_year' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'remarks' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'name' => 'exam name',
            'academic_year' => 'academic year',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'remarks' => 'remarks',
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

        Exam::create([
            'name' => $this->name,
            'academic_year' => $this->academic_year,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'remarks' => $this->remarks,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Exam created successfully.');

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);

        $this->editId = $exam->id;
        $this->name = $exam->name;
        $this->academic_year = $exam->academic_year;
        $this->start_date = $exam->start_date ? $exam->start_date->format('Y-m-d') : '';
        $this->end_date = $exam->end_date ? $exam->end_date->format('Y-m-d') : '';
        $this->remarks = $exam->remarks;
        $this->status =  $exam->status;
        $this->showForm = true;

        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        $exam = Exam::findOrFail($this->editId);

        $exam->update([
            'name' => $this->name,
            'academic_year' => $this->academic_year,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'remarks' => $this->remarks,
            'status' =>  $this->status,
        ]);

        session()->flash('success', 'Exam updated successfully.');

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
        $exam = Exam::findOrFail($id);
        $exam->delete();

        session()->flash('success', 'Exam deleted successfully.');

        if ($this->editId == $id) {
            $this->cancel();
        }

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'start_date',
            'end_date',
            'remarks',
            'editId',
        ]);

        $this->status = '1';
        $this->start_date = '';
        $this->end_date = '';
        $this->remarks = '';

        $this->resetValidation();
    }

    public function render()
    {
        $exams = Exam::query()
            ->select('id', 'name', 'start_date', 'end_date', 'remarks', 'status')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
            ->latest('id')
            ->paginate(15);

        return view('livewire.exams.exam-type', compact('exams'));
    }
}
