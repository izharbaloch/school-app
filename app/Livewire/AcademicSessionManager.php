<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicSessionManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public bool $showForm = false;
    public ?int $sessionId = null;

    public string $name = '';
    public string $start_date = '';
    public string $end_date = '';
    public bool $is_active = false;
    public string $status = 'active';
    public string $remarks = '';

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'is_active'  => ['boolean'],
            'status'     => ['required', 'in:active,closed'],
            'remarks'    => ['nullable', 'string'],
        ];
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $session = AcademicSession::findOrFail($id);
        $this->sessionId  = $session->id;
        $this->name       = $session->name;
        $this->start_date = $session->start_date->format('Y-m-d');
        $this->end_date   = $session->end_date->format('Y-m-d');
        $this->is_active  = (bool) $session->is_active;
        $this->status     = $session->status;
        $this->remarks    = $session->remarks ?? '';
        $this->showForm   = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        // Only one session can be active
        if ($data['is_active']) {
            AcademicSession::where('id', '!=', $this->sessionId ?? 0)
                ->update(['is_active' => false]);
        }

        if ($this->sessionId) {
            AcademicSession::findOrFail($this->sessionId)->update($data);
            session()->flash('success', 'Academic session updated successfully.');
        } else {
            AcademicSession::create($data);
            session()->flash('success', 'Academic session created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function setActive(int $id): void
    {
        AcademicSession::query()->update(['is_active' => false]);
        AcademicSession::findOrFail($id)->update(['is_active' => true]);
        session()->flash('success', 'Academic session set as active.');
    }

    public function delete(int $id): void
    {
        AcademicSession::findOrFail($id)->delete();
        session()->flash('success', 'Academic session deleted.');
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset(['sessionId', 'name', 'start_date', 'end_date', 'remarks']);
        $this->is_active = false;
        $this->status    = 'active';
        $this->resetValidation();
    }

    public function render()
    {
        $sessions = AcademicSession::latest('id')->paginate(15);
        return view('livewire.academic-session-manager', compact('sessions'));
    }
}
