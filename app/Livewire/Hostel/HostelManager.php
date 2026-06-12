<?php

namespace App\Livewire\Hostel;

use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelRoom;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HostelManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'hostels';

    // ── Hostel form ──
    public bool   $showHostelForm = false;
    public ?int   $editHostelId   = null;
    public string $h_name         = '';
    public string $h_type         = 'mixed';
    public string $h_warden_name  = '';
    public string $h_warden_phone = '';
    public string $h_address      = '';
    public string $h_description  = '';
    public bool   $h_status       = true;

    // ── Room form ──
    public bool   $showRoomForm     = false;
    public ?int   $editRoomId       = null;
    public string $filter_hostel_r  = '';
    public string $r_hostel_id      = '';
    public string $r_room_number    = '';
    public string $r_floor          = '';
    public string $r_capacity       = '2';
    public string $r_room_type      = 'double';
    public string $r_status         = 'available';

    // ── Allocation form ──
    public bool   $showAllocForm    = false;
    public ?int   $editAllocId      = null;
    public string $filter_hostel_a  = '';
    public string $filter_alloc_status = 'active';
    public string $a_hostel_id      = '';
    public string $a_room_id        = '';
    public string $a_student_id     = '';
    public string $a_from_date      = '';
    public string $a_to_date        = '';
    public string $a_fee            = '';
    public string $a_status         = 'active';
    public string $a_notes          = '';

    protected $queryString = [
        'activeTab'          => ['except' => 'hostels'],
        'filter_hostel_r'    => ['except' => ''],
        'filter_hostel_a'    => ['except' => ''],
        'filter_alloc_status' => ['except' => 'active'],
    ];

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->cancelAll();
    }

    public function updatedAHostelId(): void
    {
        $this->a_room_id = '';
    }

    // ═══ HOSTELS ═══

    public function openHostelForm(): void
    {
        $this->cancelAll();
        $this->showHostelForm = true;
    }

    public function editHostel(int $id): void
    {
        $h = Hostel::findOrFail($id);
        $this->editHostelId  = $h->id;
        $this->h_name        = $h->name;
        $this->h_type        = $h->type;
        $this->h_warden_name = $h->warden_name ?? '';
        $this->h_warden_phone = $h->warden_phone ?? '';
        $this->h_address     = $h->address ?? '';
        $this->h_description = $h->description ?? '';
        $this->h_status      = $h->status;
        $this->showHostelForm = true;
        $this->resetValidation();
    }

    public function saveHostel(): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        $this->validate([
            'h_name'   => ['required', 'string', 'max:150'],
            'h_type'   => ['required', 'in:male,female,mixed'],
            'h_status' => ['boolean'],
        ]);
        $data = [
            'name'         => $this->h_name,
            'type'         => $this->h_type,
            'warden_name'  => $this->h_warden_name ?: null,
            'warden_phone' => $this->h_warden_phone ?: null,
            'address'      => $this->h_address ?: null,
            'description'  => $this->h_description ?: null,
            'status'       => $this->h_status,
        ];
        if ($this->editHostelId) {
            Hostel::findOrFail($this->editHostelId)->update($data);
            session()->flash('success', 'Hostel updated.');
        } else {
            Hostel::create($data);
            session()->flash('success', 'Hostel created.');
        }
        $this->cancelAll();
        $this->resetPage();
    }

    public function deleteHostel(int $id): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        Hostel::findOrFail($id)->delete();
        session()->flash('success', 'Hostel deleted.');
        $this->resetPage();
    }

    // ═══ ROOMS ═══

    public function openRoomForm(): void
    {
        $this->cancelAll();
        $this->r_hostel_id = $this->filter_hostel_r;
        $this->showRoomForm = true;
    }

    public function editRoom(int $id): void
    {
        $r = HostelRoom::findOrFail($id);
        $this->editRoomId   = $r->id;
        $this->r_hostel_id  = (string) $r->hostel_id;
        $this->r_room_number = $r->room_number;
        $this->r_floor      = (string) ($r->floor ?? '');
        $this->r_capacity   = (string) $r->capacity;
        $this->r_room_type  = $r->room_type;
        $this->r_status     = $r->status;
        $this->showRoomForm = true;
        $this->resetValidation();
    }

    public function saveRoom(): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        $this->validate([
            'r_hostel_id'   => ['required', 'exists:hostels,id'],
            'r_room_number' => ['required', 'string', 'max:20'],
            'r_capacity'    => ['required', 'integer', 'min:1', 'max:50'],
            'r_room_type'   => ['required', 'in:single,double,triple,dormitory'],
            'r_status'      => ['required', 'in:available,occupied,maintenance'],
        ]);
        $data = [
            'hostel_id'   => $this->r_hostel_id,
            'room_number' => $this->r_room_number,
            'floor'       => $this->r_floor !== '' ? $this->r_floor : null,
            'capacity'    => $this->r_capacity,
            'room_type'   => $this->r_room_type,
            'status'      => $this->r_status,
        ];
        if ($this->editRoomId) {
            HostelRoom::findOrFail($this->editRoomId)->update($data);
            session()->flash('success', 'Room updated.');
        } else {
            HostelRoom::create($data);
            session()->flash('success', 'Room added.');
        }
        $this->cancelAll();
        $this->resetPage();
    }

    public function deleteRoom(int $id): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        HostelRoom::findOrFail($id)->delete();
        session()->flash('success', 'Room deleted.');
        $this->resetPage();
    }

    // ═══ ALLOCATIONS ═══

    public function openAllocForm(): void
    {
        $this->cancelAll();
        $this->a_from_date = now()->format('Y-m-d');
        $this->a_status    = 'active';
        $this->showAllocForm = true;
    }

    public function editAlloc(int $id): void
    {
        $a = HostelAllocation::findOrFail($id);
        $this->editAllocId  = $a->id;
        $this->a_hostel_id  = (string) $a->room->hostel_id;
        $this->a_room_id    = (string) $a->hostel_room_id;
        $this->a_student_id = (string) $a->student_id;
        $this->a_from_date  = $a->from_date->format('Y-m-d');
        $this->a_to_date    = $a->to_date?->format('Y-m-d') ?? '';
        $this->a_fee        = (string) ($a->fee_per_month ?? '');
        $this->a_status     = $a->status;
        $this->a_notes      = $a->notes ?? '';
        $this->showAllocForm = true;
        $this->resetValidation();
    }

    public function saveAlloc(): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        $this->validate([
            'a_room_id'    => ['required', 'exists:hostel_rooms,id'],
            'a_student_id' => ['required', 'exists:students,id'],
            'a_from_date'  => ['required', 'date'],
            'a_to_date'    => ['nullable', 'date', 'after_or_equal:a_from_date'],
            'a_fee'        => ['nullable', 'numeric', 'min:0'],
            'a_status'     => ['required', 'in:active,past,terminated'],
        ]);

        $room = HostelRoom::findOrFail($this->a_room_id);

        // Capacity check for new active allocations
        if ($this->a_status === 'active' && !$this->editAllocId) {
            abort_if($room->available_slots <= 0, 422, 'Room is fully occupied.');
        }

        // Duplicate check (student already has active allocation in this room)
        if (!$this->editAllocId) {
            $exists = HostelAllocation::where('student_id', $this->a_student_id)
                ->where('status', 'active')->exists();
            abort_if($exists, 422, 'Student already has an active hostel allocation.');
        }

        $data = [
            'student_id'    => $this->a_student_id,
            'hostel_room_id' => $this->a_room_id,
            'from_date'     => $this->a_from_date,
            'to_date'       => $this->a_to_date ?: null,
            'fee_per_month' => $this->a_fee !== '' ? $this->a_fee : null,
            'status'        => $this->a_status,
            'notes'         => $this->a_notes ?: null,
            'created_by'    => Auth::id(),
        ];

        if ($this->editAllocId) {
            HostelAllocation::findOrFail($this->editAllocId)->update($data);
            session()->flash('success', 'Allocation updated.');
        } else {
            HostelAllocation::create($data);
            session()->flash('success', 'Student allocated to room.');
        }

        $room->updateOccupancyStatus();
        $this->cancelAll();
        $this->resetPage();
    }

    public function deleteAlloc(int $id): void
    {
        abort_unless(Auth::user()->can('hostel.manage'), 403);
        $alloc = HostelAllocation::findOrFail($id);
        $room  = $alloc->room;
        $alloc->delete();
        $room->updateOccupancyStatus();
        session()->flash('success', 'Allocation removed.');
        $this->resetPage();
    }

    public function cancelAll(): void
    {
        $this->showHostelForm = false;
        $this->showRoomForm   = false;
        $this->showAllocForm  = false;
        $this->editHostelId   = null;
        $this->editRoomId     = null;
        $this->editAllocId    = null;
        $this->reset([
            'h_name','h_type','h_warden_name','h_warden_phone','h_address','h_description',
            'r_room_number','r_floor','r_hostel_id',
            'a_hostel_id','a_room_id','a_student_id','a_from_date','a_to_date','a_fee','a_notes',
        ]);
        $this->h_status   = true;
        $this->r_capacity = '2';
        $this->r_room_type = 'double';
        $this->r_status    = 'available';
        $this->a_status    = 'active';
        $this->resetValidation();
    }

    public function getHostelsListProperty()
    {
        return Hostel::orderBy('name')->get(['id', 'name', 'type']);
    }

    public function getAvailableRoomsProperty()
    {
        if (!$this->a_hostel_id) return collect();
        return HostelRoom::where('hostel_id', $this->a_hostel_id)
            ->where('status', '!=', 'maintenance')
            ->withCount(['activeAllocations'])
            ->get();
    }

    public function getStudentsListProperty()
    {
        return Student::with('studentClass:id,name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_class_id']);
    }

    public function render()
    {
        $canManage = Auth::user()->can('hostel.manage');

        $hostels = $this->activeTab === 'hostels'
            ? Hostel::withCount('rooms')
                ->withCount(['rooms as occupied_count' => fn($q) => $q->where('status', 'occupied')])
                ->orderBy('name')->paginate(15)
            : null;

        $rooms = $this->activeTab === 'rooms'
            ? HostelRoom::with('hostel:id,name')
                ->withCount('activeAllocations')
                ->when($this->filter_hostel_r, fn($q) => $q->where('hostel_id', $this->filter_hostel_r))
                ->orderBy('hostel_id')->orderBy('room_number')->paginate(20)
            : null;

        $allocations = $this->activeTab === 'allocations'
            ? HostelAllocation::with([
                'student:id,first_name,last_name',
                'room:id,room_number,hostel_id',
                'room.hostel:id,name',
              ])
                ->when($this->filter_hostel_a, fn($q) =>
                    $q->whereHas('room', fn($rq) => $rq->where('hostel_id', $this->filter_hostel_a))
                )
                ->when($this->filter_alloc_status, fn($q) => $q->where('status', $this->filter_alloc_status))
                ->latest()->paginate(15)
            : null;

        return view('livewire.hostel.hostel-manager', [
            'hostels'        => $hostels,
            'rooms'          => $rooms,
            'allocations'    => $allocations,
            'hostelsList'    => $this->hostelsList,
            'availableRooms' => $this->availableRooms,
            'studentsList'   => $this->activeTab === 'allocations' || $this->showAllocForm ? $this->studentsList : collect(),
            'canManage'      => $canManage,
        ]);
    }
}
