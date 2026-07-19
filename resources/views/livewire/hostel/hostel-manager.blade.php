<div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Tab Nav --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'hostels' ? 'active' : '' }}"
           wire:click.prevent="switchTab('hostels')" href="#">
            <i class="fas fa-building"></i> Hostels
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'rooms' ? 'active' : '' }}"
           wire:click.prevent="switchTab('rooms')" href="#">
            <i class="fas fa-door-open"></i> Rooms
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'allocations' ? 'active' : '' }}"
           wire:click.prevent="switchTab('allocations')" href="#">
            <i class="fas fa-user-check"></i> Allocations
        </a>
    </li>
</ul>

{{-- ═══════════════ HOSTELS TAB ═══════════════ --}}
@if ($activeTab === 'hostels')

@if ($showHostelForm)
<div class="card border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editHostelId ? 'Edit Hostel' : 'Add Hostel' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="h_name"
                           class="form-control @error('h_name') is-invalid @enderror" placeholder="Hostel name">
                    @error('h_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Type</label>
                    <select wire:model.defer="h_type" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="mixed">Mixed</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Warden Name</label>
                    <input type="text" wire:model.defer="h_warden_name" class="form-control" placeholder="Warden name">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Warden Phone</label>
                    <input type="text" wire:model.defer="h_warden_phone" class="form-control" placeholder="Phone">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Active</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" wire:model.defer="h_status" class="custom-control-input" id="hStatus">
                        <label class="custom-control-label" for="hStatus"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" wire:model.defer="h_address" class="form-control" placeholder="Hostel address">
        </div>
        <div class="d-flex gap-2">
            <button wire:click="saveHostel" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="saveHostel">
                <span wire:loading.remove wire:target="saveHostel">Save</span>
                <span wire:loading wire:target="saveHostel"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button wire:click="cancelAll" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@elseif ($canManage)
<div class="mb-3">
    <button wire:click="openHostelForm" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Hostel
    </button>
</div>
@endif

<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-light">
            <tr><th>Name</th><th>Type</th><th>Warden</th><th class="text-center">Rooms</th><th class="text-center">Occupied</th><th>Status</th>@if($canManage)<th></th>@endif</tr>
        </thead>
        <tbody>
            @forelse ($hostels as $h)
            <tr>
                <td class="font-weight-medium">{{ $h->name }}</td>
                <td><span class="badge badge-info">{{ ucfirst($h->type) }}</span></td>
                <td>{{ $h->warden_name ?? '—' }}{{ $h->warden_phone ? ' · '.$h->warden_phone : '' }}</td>
                <td class="text-center">{{ $h->rooms_count }}</td>
                <td class="text-center">{{ $h->occupied_count }}</td>
                <td>
                    @if ($h->status) <span class="badge badge-success">Active</span>
                    @else <span class="badge badge-danger">Inactive</span> @endif
                </td>
                @if ($canManage)
                <td class="text-nowrap">
                    <button wire:click="editHostel({{ $h->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                    <button wire:click="deleteHostel({{ $h->id }})" wire:confirm="Delete '{{ $h->name }}'? All rooms and allocations will be removed."
                            class="btn btn-sm btn-outline-danger" title="Delete"
                            wire:loading.attr="disabled" wire:target="deleteHostel({{ $h->id }})"><i class="fas fa-trash"></i></button>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 7 : 6 }}" class="text-center text-muted py-4">No hostels yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $hostels->links() }}</div>

{{-- ═══════════════ ROOMS TAB ═══════════════ --}}
@elseif ($activeTab === 'rooms')

<div class="row mb-3 align-items-end">
    <div class="col-md-3">
        <select wire:model="filter_hostel_r" class="form-control form-control-sm">
            <option value="">-- All Hostels --</option>
            @foreach ($hostelsList as $hl)
                <option value="{{ $hl->id }}">{{ $hl->name }}</option>
            @endforeach
        </select>
    </div>
    @if ($canManage)
    <div class="col-md-auto ml-auto">
        <button wire:click="openRoomForm" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Room
        </button>
    </div>
    @endif
</div>

@if ($showRoomForm)
<div class="card border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editRoomId ? 'Edit Room' : 'Add Room' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Hostel <span class="text-danger">*</span></label>
                    <select wire:model.defer="r_hostel_id" class="form-control @error('r_hostel_id') is-invalid @enderror">
                        <option value="">-- Select Hostel --</option>
                        @foreach ($hostelsList as $hl)
                            <option value="{{ $hl->id }}">{{ $hl->name }}</option>
                        @endforeach
                    </select>
                    @error('r_hostel_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Room No. <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="r_room_number"
                           class="form-control @error('r_room_number') is-invalid @enderror" placeholder="e.g. 101">
                    @error('r_room_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Floor</label>
                    <input type="number" wire:model.defer="r_floor" class="form-control" placeholder="1">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Capacity <span class="text-danger">*</span></label>
                    <input type="number" wire:model.defer="r_capacity" min="1" max="50"
                           class="form-control @error('r_capacity') is-invalid @enderror">
                    @error('r_capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Type</label>
                    <select wire:model.defer="r_room_type" class="form-control">
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="triple">Triple</option>
                        <option value="dormitory">Dormitory</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model.defer="r_status" class="form-control">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="saveRoom" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="saveRoom">
                <span wire:loading.remove wire:target="saveRoom">Save</span>
                <span wire:loading wire:target="saveRoom"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button wire:click="cancelAll" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-light">
            <tr><th>Hostel</th><th>Room</th><th>Floor</th><th>Type</th><th class="text-center">Capacity</th><th class="text-center">Occupied</th><th>Status</th>@if($canManage)<th></th>@endif</tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
            <tr>
                <td>{{ $room->hostel->name ?? '—' }}</td>
                <td class="font-weight-medium">{{ $room->room_number }}</td>
                <td>{{ $room->floor ?? '—' }}</td>
                <td>{{ ucfirst($room->room_type) }}</td>
                <td class="text-center">{{ $room->capacity }}</td>
                <td class="text-center">
                    <span class="{{ $room->active_allocations_count >= $room->capacity ? 'text-danger font-weight-bold' : '' }}">
                        {{ $room->active_allocations_count }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $room->status === 'available' ? 'success' : ($room->status === 'maintenance' ? 'warning' : 'danger') }}">
                        {{ ucfirst($room->status) }}
                    </span>
                </td>
                @if ($canManage)
                <td class="text-nowrap">
                    <button wire:click="editRoom({{ $room->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                    <button wire:click="deleteRoom({{ $room->id }})" wire:confirm="Delete room {{ $room->room_number }}?"
                            class="btn btn-sm btn-outline-danger" title="Delete"
                            wire:loading.attr="disabled" wire:target="deleteRoom({{ $room->id }})"><i class="fas fa-trash"></i></button>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 8 : 7 }}" class="text-center text-muted py-4">No rooms found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $rooms->links() }}</div>

{{-- ═══════════════ ALLOCATIONS TAB ═══════════════ --}}
@elseif ($activeTab === 'allocations')

<div class="row mb-3 align-items-end">
    <div class="col-md-3">
        <select wire:model="filter_hostel_a" class="form-control form-control-sm">
            <option value="">-- All Hostels --</option>
            @foreach ($hostelsList as $hl)
                <option value="{{ $hl->id }}">{{ $hl->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select wire:model="filter_alloc_status" class="form-control form-control-sm">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="past">Past</option>
            <option value="terminated">Terminated</option>
        </select>
    </div>
    @if ($canManage)
    <div class="col-md-auto ml-auto">
        <button wire:click="openAllocForm" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Allocate Student
        </button>
    </div>
    @endif
</div>

@if ($showAllocForm)
<div class="card border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editAllocId ? 'Edit Allocation' : 'Allocate Student to Room' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Student <span class="text-danger">*</span></label>
                    <select wire:model.defer="a_student_id" class="form-control @error('a_student_id') is-invalid @enderror">
                        <option value="">-- Select Student --</option>
                        @foreach ($studentsList as $s)
                            <option value="{{ $s->id }}">{{ $s->full_name }} {{ $s->studentClass ? '('.$s->studentClass->name.')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('a_student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Hostel</label>
                    <select wire:model="a_hostel_id" class="form-control">
                        <option value="">-- Select Hostel --</option>
                        @foreach ($hostelsList as $hl)
                            <option value="{{ $hl->id }}">{{ $hl->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Room <span class="text-danger">*</span></label>
                    <select wire:model.defer="a_room_id" class="form-control @error('a_room_id') is-invalid @enderror">
                        <option value="">-- Select Room --</option>
                        @foreach ($availableRooms as $room)
                            <option value="{{ $room->id }}" {{ $room->active_allocations_count >= $room->capacity ? 'disabled' : '' }}>
                                {{ $room->room_number }} ({{ $room->active_allocations_count }}/{{ $room->capacity }})
                                {{ $room->active_allocations_count >= $room->capacity ? '[FULL]' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('a_room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>From Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="a_from_date"
                           class="form-control @error('a_from_date') is-invalid @enderror">
                    @error('a_from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>To Date</label>
                    <input type="date" wire:model.defer="a_to_date" class="form-control">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Fee/Month</label>
                    <input type="number" wire:model.defer="a_fee" class="form-control" placeholder="0.00">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model.defer="a_status" class="form-control">
                        <option value="active">Active</option>
                        <option value="past">Past</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" wire:model.defer="a_notes" class="form-control" placeholder="Optional notes">
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="saveAlloc" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="saveAlloc">
                <span wire:loading.remove wire:target="saveAlloc">Save</span>
                <span wire:loading wire:target="saveAlloc"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button wire:click="cancelAll" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-light">
            <tr><th>Student</th><th>Hostel</th><th>Room</th><th>From</th><th>To</th><th>Fee/mo</th><th>Status</th>@if($canManage)<th></th>@endif</tr>
        </thead>
        <tbody>
            @forelse ($allocations as $alloc)
            <tr>
                <td class="font-weight-medium">{{ $alloc->student->full_name }}</td>
                <td>{{ $alloc->room->hostel->name ?? '—' }}</td>
                <td>{{ $alloc->room->room_number ?? '—' }}</td>
                <td class="text-nowrap">{{ $alloc->from_date->format('d M Y') }}</td>
                <td class="text-nowrap">{{ $alloc->to_date ? $alloc->to_date->format('d M Y') : '—' }}</td>
                <td>{{ $alloc->fee_per_month ? number_format($alloc->fee_per_month, 0) : '—' }}</td>
                <td><span class="badge badge-{{ $alloc->status_badge }}">{{ ucfirst($alloc->status) }}</span></td>
                @if ($canManage)
                <td class="text-nowrap">
                    <button wire:click="editAlloc({{ $alloc->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                    <button wire:click="deleteAlloc({{ $alloc->id }})" wire:confirm="Remove this allocation?"
                            class="btn btn-sm btn-outline-danger" title="Delete"
                            wire:loading.attr="disabled" wire:target="deleteAlloc({{ $alloc->id }})"><i class="fas fa-trash"></i></button>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="{{ $canManage ? 8 : 7 }}" class="text-center text-muted py-4">No allocations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $allocations->links() }}</div>
@endif

</div>
