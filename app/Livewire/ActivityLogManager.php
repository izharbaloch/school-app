<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search      = '';
    public string $filterUser  = '';
    public string $filterAction= '';
    public string $filterDate  = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterUser'   => ['except' => ''],
        'filterAction' => ['except' => ''],
    ];

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingFilterUser(): void  { $this->resetPage(); }
    public function updatingFilterAction(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterUser', 'filterAction', 'filterDate']);
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, fn($q) => $q->where('description', 'like', "%{$this->search}%")
                ->orWhere('action', 'like', "%{$this->search}%"))
            ->when($this->filterUser, fn($q) => $q->where('user_id', $this->filterUser))
            ->when($this->filterAction, fn($q) => $q->where('action', $this->filterAction))
            ->when($this->filterDate, fn($q) => $q->whereDate('created_at', $this->filterDate))
            ->latest()
            ->paginate(25);

        $users   = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action')->sort()->values();

        return view('livewire.activity-log-manager', compact('logs', 'users', 'actions'));
    }
}
