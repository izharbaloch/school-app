<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public bool $showForm = false;
    public ?int $eventId = null;

    public string $title = '';
    public string $description = '';
    public string $type = 'general';
    public string $start_date = '';
    public string $end_date = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $venue = '';
    public string $audience = 'all';
    public bool $status = true;

    public string $search = '';
    public string $filterType = '';

    protected $queryString = ['search' => ['except' => ''], 'filterType' => ['except' => '']];

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'in:general,sports,exam,holiday,meeting,cultural'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'start_time'  => ['nullable', 'date_format:H:i'],
            'end_time'    => ['nullable', 'date_format:H:i'],
            'venue'       => ['nullable', 'string', 'max:255'],
            'audience'    => ['required', 'in:all,students,teachers,parents'],
            'status'      => ['boolean'],
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openForm(): void
    {
        $this->resetForm();
        $this->start_date = now()->format('Y-m-d');
        $this->end_date   = now()->format('Y-m-d');
        $this->showForm   = true;
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->eventId     = $event->id;
        $this->title       = $event->title;
        $this->description = $event->description ?? '';
        $this->type        = $event->type;
        $this->start_date  = $event->start_date->format('Y-m-d');
        $this->end_date    = $event->end_date->format('Y-m-d');
        $this->start_time  = $event->start_time ? substr($event->start_time, 0, 5) : '';
        $this->end_time    = $event->end_time ? substr($event->end_time, 0, 5) : '';
        $this->venue       = $event->venue ?? '';
        $this->audience    = $event->audience;
        $this->status      = (bool) $event->status;
        $this->showForm    = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['start_time']  = $data['start_time'] ?: null;
        $data['end_time']    = $data['end_time'] ?: null;
        $data['description'] = $data['description'] ?: null;
        $data['venue']       = $data['venue'] ?: null;
        $data['created_by']  = auth()->id();

        if ($this->eventId) {
            Event::findOrFail($this->eventId)->update($data);
            session()->flash('success', 'Event updated.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        session()->flash('success', 'Event deleted.');
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function resetForm(): void
    {
        $this->reset(['eventId', 'title', 'description', 'start_date', 'end_date', 'start_time', 'end_time', 'venue']);
        $this->type     = 'general';
        $this->audience = 'all';
        $this->status   = true;
        $this->resetValidation();
    }

    public function render()
    {
        $events = Event::with('creator')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderByDesc('start_date')
            ->paginate(15);

        $types = Event::$types;

        return view('livewire.event-manager', compact('events', 'types'));
    }
}
