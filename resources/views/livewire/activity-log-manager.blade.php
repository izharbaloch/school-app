<div>
    {{-- Filter Row --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control form-control-sm" placeholder="Search action or description...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterUser" class="form-control form-control-sm">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterAction" class="form-control form-control-sm">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input wire:model.live="filterDate" type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <button wire:click="clearFilters" class="btn btn-secondary btn-sm w-100"><i class="fas fa-times"></i> Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Model</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td>
                                <small>{{ $log->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td>
                                @php
                                    $colors = ['create'=>'success','update'=>'warning','delete'=>'danger','login'=>'info','logout'=>'secondary','view'=>'light'];
                                    $color = $colors[$log->action] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ ucfirst($log->action) }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>
                                @if($log->model_type)
                                    <small class="text-muted">{{ class_basename($log->model_type) }}
                                    @if($log->model_id) #{{ $log->model_id }} @endif
                                    </small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No activity logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="p-3">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</div>
