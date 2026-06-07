<?php

namespace App\Livewire;

use App\Models\Notice;
use Livewire\Component;
use Livewire\WithPagination;

class NoticeManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public bool $showForm = false;
    public ?int $noticeId = null;

    public string $title = '';
    public string $content = '';
    public string $audience = 'all';
    public string $publish_date = '';
    public string $expiry_date = '';
    public bool $is_pinned = false;
    public bool $status = true;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    protected function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'audience'     => ['required', 'in:all,students,teachers,parents,staff'],
            'publish_date' => ['required', 'date'],
            'expiry_date'  => ['nullable', 'date', 'after_or_equal:publish_date'],
            'is_pinned'    => ['boolean'],
            'status'       => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->publish_date = now()->format('Y-m-d');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $notice = Notice::findOrFail($id);
        $this->noticeId     = $notice->id;
        $this->title        = $notice->title;
        $this->content      = $notice->content;
        $this->audience     = $notice->audience;
        $this->publish_date = $notice->publish_date->format('Y-m-d');
        $this->expiry_date  = $notice->expiry_date ? $notice->expiry_date->format('Y-m-d') : '';
        $this->is_pinned    = (bool) $notice->is_pinned;
        $this->status       = (bool) $notice->status;
        $this->showForm     = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['expiry_date'] = $data['expiry_date'] ?: null;
        $data['created_by']  = auth()->id();

        if ($this->noticeId) {
            Notice::findOrFail($this->noticeId)->update($data);
            session()->flash('success', 'Notice updated successfully.');
        } else {
            Notice::create($data);
            session()->flash('success', 'Notice published successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function togglePin(int $id): void
    {
        $notice = Notice::findOrFail($id);
        $notice->update(['is_pinned' => !$notice->is_pinned]);
    }

    public function delete(int $id): void
    {
        Notice::findOrFail($id)->delete();
        session()->flash('success', 'Notice deleted.');
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset(['noticeId', 'title', 'content', 'expiry_date']);
        $this->audience     = 'all';
        $this->publish_date = now()->format('Y-m-d');
        $this->is_pinned    = false;
        $this->status       = true;
        $this->resetValidation();
    }

    public function render()
    {
        $notices = Notice::with('creator')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderByDesc('is_pinned')
            ->latest('id')
            ->paginate(15);

        $audiences = Notice::$audiences;

        return view('livewire.notice-manager', compact('notices', 'audiences'));
    }
}
