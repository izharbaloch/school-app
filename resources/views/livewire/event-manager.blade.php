<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Events & Calendar</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search events...">
                <select class="form-control form-control-sm" wire:model.live="filterType" style="width:auto">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @can('notices.create')
                <button class="btn btn-primary btn-sm" wire:click="openForm"><i class="fas fa-plus"></i> Add Event</button>
                @endcan
            </div>
        </div>

        @if($showForm)
        <div class="card-body border-bottom bg-light">
            <h6>{{ $eventId ? 'Edit' : 'Add' }} Event</h6>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" wire:model="type">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Audience</label>
                        <select class="form-control" wire:model="audience">
                            <option value="all">All</option>
                            <option value="students">Students</option>
                            <option value="teachers">Teachers</option>
                            <option value="parents">Parents</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Active</label>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="ev_status" wire:model="status">
                            <label class="custom-control-label" for="ev_status">Yes</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><div class="form-group"><label>Start Date</label><input type="date" class="form-control" wire:model="start_date"></div></div>
                <div class="col-md-2"><div class="form-group"><label>End Date</label><input type="date" class="form-control" wire:model="end_date"></div></div>
                <div class="col-md-2"><div class="form-group"><label>Start Time</label><input type="time" class="form-control" wire:model="start_time"></div></div>
                <div class="col-md-2"><div class="form-group"><label>End Time</label><input type="time" class="form-control" wire:model="end_time"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Venue</label><input type="text" class="form-control" wire:model="venue" placeholder="Location/venue"></div></div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" wire:model="description" rows="3"></textarea>
            </div>
            <button class="btn btn-success" wire:click="save">{{ $eventId ? 'Update' : 'Save' }}</button>
            <button class="btn btn-secondary ml-2" wire:click="cancel">Cancel</button>
        </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>#</th><th>Title</th><th>Type</th><th>Dates</th><th>Time</th><th>Venue</th><th>Audience</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @php
                        $typeBadge = ['general'=>'primary','sports'=>'success','exam'=>'danger','holiday'=>'warning','meeting'=>'info','cultural'=>'secondary'];
                        @endphp
                        @forelse($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $event->title }}</strong>@if($event->description)<br><small class="text-muted">{{ Str::limit($event->description, 60) }}</small>@endif</td>
                            <td><span class="badge badge-{{ $typeBadge[$event->type] ?? 'secondary' }}">{{ $types[$event->type] ?? $event->type }}</span></td>
                            <td>
                                {{ $event->start_date->format('d M Y') }}
                                @if(!$event->start_date->equalTo($event->end_date))
                                    – {{ $event->end_date->format('d M Y') }}
                                @endif
                            </td>
                            <td>
                                @if($event->start_time)
                                    {{ substr($event->start_time, 0, 5) }}@if($event->end_time) – {{ substr($event->end_time, 0, 5) }}@endif
                                @else -
                                @endif
                            </td>
                            <td>{{ $event->venue ?? '-' }}</td>
                            <td>{{ ucfirst($event->audience) }}</td>
                            <td><span class="badge badge-{{ $event->status ? 'success' : 'secondary' }}">{{ $event->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                @can('notices.create')
                                <button class="btn btn-xs btn-info" wire:click="edit({{ $event->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger ml-1" wire:click="delete({{ $event->id }})" onclick="return confirm('Delete event?')"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No events found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($events->hasPages()) <div class="card-footer">{{ $events->links() }}</div> @endif
    </div>
</div>
