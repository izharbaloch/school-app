<?php

namespace App\Livewire\Transport;

use App\Models\Student;
use App\Models\StudentTransport;
use App\Models\TransportRoute;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

class TransportManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'vehicles';

    // Vehicle form
    public bool $showVehicleForm = false;
    public ?int $vehicleId = null;
    public string $v_name = '';
    public string $v_registration_no = '';
    public string $v_type = 'bus';
    public string $v_capacity = '40';
    public string $v_driver_name = '';
    public string $v_driver_phone = '';
    public string $v_driver_cnic = '';
    public bool $v_status = true;

    // Route form
    public bool $showRouteForm = false;
    public ?int $routeId = null;
    public string $r_name = '';
    public string $r_vehicle_id = '';
    public string $r_start_point = '';
    public string $r_end_point = '';
    public string $r_monthly_fee = '';
    public bool $r_status = true;

    // Student transport
    public bool $showStudentForm = false;
    public string $st_student_id = '';
    public string $st_route_id = '';
    public string $st_pickup_point = '';
    public string $st_drop_point = '';
    public string $st_start_date = '';
    public bool $st_status = true;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedActiveTab(): void { $this->resetPage(); $this->search = ''; }

    // ─── Vehicle CRUD ─────────────────────────────────────────────
    public function openVehicleForm(): void { $this->resetVehicleForm(); $this->showVehicleForm = true; }

    public function editVehicle(int $id): void
    {
        $v = Vehicle::findOrFail($id);
        $this->vehicleId        = $v->id;
        $this->v_name           = $v->name;
        $this->v_registration_no= $v->registration_no;
        $this->v_type           = $v->type;
        $this->v_capacity       = (string) $v->capacity;
        $this->v_driver_name    = $v->driver_name ?? '';
        $this->v_driver_phone   = $v->driver_phone ?? '';
        $this->v_driver_cnic    = $v->driver_cnic ?? '';
        $this->v_status         = (bool) $v->status;
        $this->showVehicleForm  = true;
        $this->resetValidation();
    }

    public function saveVehicle(): void
    {
        $this->validate([
            'v_name'            => ['required', 'string', 'max:100'],
            'v_registration_no' => ['required', 'string', 'max:50'],
            'v_type'            => ['required', 'in:bus,van,car'],
            'v_capacity'        => ['required', 'integer', 'min:1'],
        ]);

        $data = [
            'name'            => $this->v_name,
            'registration_no' => $this->v_registration_no,
            'type'            => $this->v_type,
            'capacity'        => (int) $this->v_capacity,
            'driver_name'     => $this->v_driver_name ?: null,
            'driver_phone'    => $this->v_driver_phone ?: null,
            'driver_cnic'     => $this->v_driver_cnic ?: null,
            'status'          => $this->v_status,
        ];

        if ($this->vehicleId) {
            Vehicle::findOrFail($this->vehicleId)->update($data);
            session()->flash('success', 'Vehicle updated.');
        } else {
            Vehicle::create($data);
            session()->flash('success', 'Vehicle added.');
        }

        $this->resetVehicleForm();
        $this->showVehicleForm = false;
    }

    public function deleteVehicle(int $id): void
    {
        Vehicle::findOrFail($id)->delete();
        session()->flash('success', 'Vehicle deleted.');
    }

    public function resetVehicleForm(): void
    {
        $this->reset(['vehicleId', 'v_name', 'v_registration_no', 'v_driver_name', 'v_driver_phone', 'v_driver_cnic']);
        $this->v_type     = 'bus';
        $this->v_capacity = '40';
        $this->v_status   = true;
        $this->resetValidation();
    }

    // ─── Route CRUD ───────────────────────────────────────────────
    public function openRouteForm(): void { $this->resetRouteForm(); $this->showRouteForm = true; }

    public function editRoute(int $id): void
    {
        $r = TransportRoute::findOrFail($id);
        $this->routeId      = $r->id;
        $this->r_name       = $r->name;
        $this->r_vehicle_id = $r->vehicle_id ? (string) $r->vehicle_id : '';
        $this->r_start_point= $r->start_point ?? '';
        $this->r_end_point  = $r->end_point ?? '';
        $this->r_monthly_fee= $r->monthly_fee ?? '';
        $this->r_status     = (bool) $r->status;
        $this->showRouteForm = true;
        $this->resetValidation();
    }

    public function saveRoute(): void
    {
        $this->validate([
            'r_name'       => ['required', 'string', 'max:255'],
            'r_vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'r_monthly_fee'=> ['nullable', 'numeric', 'min:0'],
        ]);

        $data = [
            'name'        => $this->r_name,
            'vehicle_id'  => $this->r_vehicle_id ?: null,
            'start_point' => $this->r_start_point ?: null,
            'end_point'   => $this->r_end_point ?: null,
            'monthly_fee' => $this->r_monthly_fee ?: 0,
            'status'      => $this->r_status,
        ];

        if ($this->routeId) {
            TransportRoute::findOrFail($this->routeId)->update($data);
            session()->flash('success', 'Route updated.');
        } else {
            TransportRoute::create($data);
            session()->flash('success', 'Route added.');
        }

        $this->resetRouteForm();
        $this->showRouteForm = false;
    }

    public function deleteRoute(int $id): void
    {
        TransportRoute::findOrFail($id)->delete();
        session()->flash('success', 'Route deleted.');
    }

    public function resetRouteForm(): void
    {
        $this->reset(['routeId', 'r_name', 'r_vehicle_id', 'r_start_point', 'r_end_point', 'r_monthly_fee']);
        $this->r_status = true;
        $this->resetValidation();
    }

    // ─── Student Transport ────────────────────────────────────────
    public function openStudentForm(): void
    {
        $this->reset(['st_student_id', 'st_route_id', 'st_pickup_point', 'st_drop_point']);
        $this->st_start_date = now()->format('Y-m-d');
        $this->st_status     = true;
        $this->showStudentForm = true;
    }

    public function saveStudentTransport(): void
    {
        $this->validate([
            'st_student_id' => ['required', 'exists:students,id'],
            'st_route_id'   => ['required', 'exists:transport_routes,id'],
            'st_start_date' => ['required', 'date'],
        ]);

        StudentTransport::updateOrCreate(
            ['student_id' => $this->st_student_id, 'route_id' => $this->st_route_id],
            [
                'pickup_point' => $this->st_pickup_point ?: null,
                'drop_point'   => $this->st_drop_point ?: null,
                'start_date'   => $this->st_start_date,
                'status'       => $this->st_status,
            ]
        );

        session()->flash('success', 'Student transport assigned.');
        $this->showStudentForm = false;
    }

    public function removeStudentTransport(int $id): void
    {
        StudentTransport::findOrFail($id)->delete();
        session()->flash('success', 'Transport assignment removed.');
    }

    public function render()
    {
        $vehicles = Vehicle::when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('registration_no', 'like', "%{$this->search}%"))
            ->latest('id')->paginate(15, ['*'], 'vehiclePage');

        $routes = TransportRoute::with('vehicle')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest('id')->paginate(15, ['*'], 'routePage');

        $studentTransports = StudentTransport::with(['student', 'route'])
            ->latest('id')->paginate(15, ['*'], 'stPage');

        $allVehicles = Vehicle::where('status', true)->orderBy('name')->get();
        $allRoutes   = TransportRoute::where('status', true)->orderBy('name')->get();
        $allStudents = Student::select('id', 'first_name', 'last_name', 'admission_no')->orderBy('first_name')->get();

        return view('livewire.transport.transport-manager', compact('vehicles', 'routes', 'studentTransports', 'allVehicles', 'allRoutes', 'allStudents'));
    }
}
