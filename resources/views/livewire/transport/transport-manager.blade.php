<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'vehicles' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','vehicles')" href="#">
                <i class="fas fa-bus mr-1"></i> Vehicles
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'routes' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','routes')" href="#">
                <i class="fas fa-route mr-1"></i> Routes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'students' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','students')" href="#">
                <i class="fas fa-users mr-1"></i> Student Allocations
            </a>
        </li>
    </ul>

    <!-- Vehicles -->
    @if($activeTab === 'vehicles')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Vehicles</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search...">
                <button class="btn btn-primary btn-sm" wire:click="openVehicleForm"><i class="fas fa-plus"></i> Add Vehicle</button>
            </div>
        </div>
        @if($showVehicleForm)
        <div class="card-body border-bottom bg-light">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Name</label><input type="text" class="form-control" wire:model="v_name" placeholder="Bus 1"></div></div>
                <div class="col-md-2"><div class="form-group"><label>Reg No.</label><input type="text" class="form-control" wire:model="v_registration_no"></div></div>
                <div class="col-md-2">
                    <div class="form-group"><label>Type</label>
                        <select class="form-control" wire:model="v_type">
                            <option value="bus">Bus</option><option value="van">Van</option><option value="car">Car</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1"><div class="form-group"><label>Capacity</label><input type="number" class="form-control" wire:model="v_capacity"></div></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-6"><div class="form-group"><label>Driver Name</label><input type="text" class="form-control" wire:model="v_driver_name"></div></div>
                        <div class="col-6"><div class="form-group"><label>Driver Phone</label><input type="text" class="form-control" wire:model="v_driver_phone"></div></div>
                    </div>
                </div>
            </div>
            <button class="btn btn-success" type="button" wire:click="saveVehicle" wire:loading.attr="disabled" wire:target="saveVehicle">
                <span wire:loading.remove wire:target="saveVehicle">{{ $vehicleId ? 'Update' : 'Save' }}</span>
                <span wire:loading wire:target="saveVehicle"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showVehicleForm', false)">Cancel</button>
        </div>
        @endif
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>Name</th><th>Reg No.</th><th>Type</th><th>Capacity</th><th>Driver</th><th>Phone</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($vehicles as $v)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->registration_no }}</td>
                        <td>{{ ucfirst($v->type) }}</td>
                        <td>{{ $v->capacity }}</td>
                        <td>{{ $v->driver_name ?? '-' }}</td>
                        <td>{{ $v->driver_phone ?? '-' }}</td>
                        <td><span class="badge badge-{{ $v->status ? 'success' : 'danger' }}">{{ $v->status ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" wire:click="editVehicle({{ $v->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger ml-1" wire:click="deleteVehicle({{ $v->id }})" wire:confirm="Delete this vehicle?" wire:loading.attr="disabled" wire:target="deleteVehicle" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No vehicles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vehicles->hasPages()) <div class="card-footer">{{ $vehicles->links() }}</div> @endif
    </div>
    @endif

    <!-- Routes -->
    @if($activeTab === 'routes')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Transport Routes</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search...">
                <button class="btn btn-primary btn-sm" wire:click="openRouteForm"><i class="fas fa-plus"></i> Add Route</button>
            </div>
        </div>
        @if($showRouteForm)
        <div class="card-body border-bottom bg-light">
            <div class="row">
                <div class="col-md-3"><div class="form-group"><label>Route Name</label><input type="text" class="form-control" wire:model="r_name"></div></div>
                <div class="col-md-2">
                    <div class="form-group"><label>Vehicle</label>
                        <select class="form-control" wire:model="r_vehicle_id">
                            <option value="">No Vehicle</option>
                            @foreach($allVehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2"><div class="form-group"><label>Start Point</label><input type="text" class="form-control" wire:model="r_start_point"></div></div>
                <div class="col-md-2"><div class="form-group"><label>End Point</label><input type="text" class="form-control" wire:model="r_end_point"></div></div>
                <div class="col-md-2"><div class="form-group"><label>Monthly Fee (Rs.)</label><input type="number" class="form-control" wire:model="r_monthly_fee" step="0.01"></div></div>
            </div>
            <button class="btn btn-success" type="button" wire:click="saveRoute" wire:loading.attr="disabled" wire:target="saveRoute">
                <span wire:loading.remove wire:target="saveRoute">{{ $routeId ? 'Update' : 'Save' }}</span>
                <span wire:loading wire:target="saveRoute"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showRouteForm', false)">Cancel</button>
        </div>
        @endif
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>Name</th><th>Vehicle</th><th>Start Point</th><th>End Point</th><th>Monthly Fee</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($routes as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->name }}</td>
                        <td>{{ $r->vehicle->name ?? '-' }}</td>
                        <td>{{ $r->start_point ?? '-' }}</td>
                        <td>{{ $r->end_point ?? '-' }}</td>
                        <td>Rs. {{ number_format($r->monthly_fee, 0) }}</td>
                        <td><span class="badge badge-{{ $r->status ? 'success' : 'danger' }}">{{ $r->status ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" wire:click="editRoute({{ $r->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger ml-1" wire:click="deleteRoute({{ $r->id }})" wire:confirm="Delete this route?" wire:loading.attr="disabled" wire:target="deleteRoute" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No routes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($routes->hasPages()) <div class="card-footer">{{ $routes->links() }}</div> @endif
    </div>
    @endif

    <!-- Student Allocations -->
    @if($activeTab === 'students')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Student Transport Allocations</h4>
            <button class="btn btn-primary btn-sm" wire:click="openStudentForm"><i class="fas fa-plus"></i> Assign Student</button>
        </div>
        @if($showStudentForm)
        <div class="card-body border-bottom bg-light">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group"><label>Student</label>
                        <select class="form-control" wire:model="st_student_id">
                            <option value="">Select Student</option>
                            @foreach($allStudents as $s)
                                <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group"><label>Route</label>
                        <select class="form-control" wire:model="st_route_id">
                            <option value="">Select Route</option>
                            @foreach($allRoutes as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2"><div class="form-group"><label>Pickup Point</label><input type="text" class="form-control" wire:model="st_pickup_point"></div></div>
                <div class="col-md-2"><div class="form-group"><label>Drop Point</label><input type="text" class="form-control" wire:model="st_drop_point"></div></div>
                <div class="col-md-2"><div class="form-group"><label>Start Date</label><input type="date" class="form-control" wire:model="st_start_date"></div></div>
            </div>
            <button class="btn btn-success" type="button" wire:click="saveStudentTransport" wire:loading.attr="disabled" wire:target="saveStudentTransport">
                <span wire:loading.remove wire:target="saveStudentTransport">Save</span>
                <span wire:loading wire:target="saveStudentTransport"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showStudentForm', false)">Cancel</button>
        </div>
        @endif
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>Student</th><th>Route</th><th>Pickup</th><th>Drop</th><th>Start Date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($studentTransports as $st)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $st->student->first_name ?? '' }} {{ $st->student->last_name ?? '' }}</td>
                        <td>{{ $st->route->name ?? '-' }}</td>
                        <td>{{ $st->pickup_point ?? '-' }}</td>
                        <td>{{ $st->drop_point ?? '-' }}</td>
                        <td>{{ $st->start_date->format('d M Y') }}</td>
                        <td><span class="badge badge-{{ $st->status ? 'success' : 'danger' }}">{{ $st->status ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" wire:click="removeStudentTransport({{ $st->id }})" wire:confirm="Remove this student's transport assignment?" wire:loading.attr="disabled" wire:target="removeStudentTransport" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No allocations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($studentTransports->hasPages()) <div class="card-footer">{{ $studentTransports->links() }}</div> @endif
    </div>
    @endif
</div>
