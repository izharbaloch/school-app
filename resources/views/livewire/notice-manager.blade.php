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
            <h4>Notice Board</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search notices...">
                @can('notices.create')
                <button class="btn btn-primary btn-sm" wire:click="openForm"><i class="fas fa-plus"></i> New Notice</button>
                @endcan
            </div>
        </div>

        @if($showForm)
        <div class="card-body border-bottom bg-light">
            <h6 class="mb-3">{{ $noticeId ? 'Edit' : 'Create' }} Notice</h6>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" placeholder="Notice title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Audience</label>
                        <select class="form-control" wire:model="audience">
                            @foreach($audiences as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Content <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" wire:model="content" rows="5" placeholder="Notice content..."></textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Publish Date</label>
                        <input type="date" class="form-control" wire:model="publish_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" class="form-control" wire:model="expiry_date">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end pb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_pinned" wire:model="is_pinned">
                        <label class="custom-control-label" for="is_pinned">Pin this notice</label>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end pb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="notice_status" wire:model="status">
                        <label class="custom-control-label" for="notice_status">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <button class="btn btn-success" wire:click="save" wire:loading.attr="disabled">
                    <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm"></span></span>
                    {{ $noticeId ? 'Update' : 'Publish' }}
                </button>
                <button class="btn btn-secondary ml-2" wire:click="cancel">Cancel</button>
            </div>
        </div>
        @endif

        <div class="card-body p-0">
            @forelse($notices as $notice)
            <div class="p-3 border-bottom {{ $notice->is_pinned ? 'bg-warning bg-opacity-10' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1" style="gap:8px">
                            @if($notice->is_pinned)
                                <span class="badge badge-warning"><i class="fas fa-thumbtack"></i> Pinned</span>
                            @endif
                            <span class="badge badge-{{ $notice->audience === 'all' ? 'primary' : 'info' }}">
                                {{ $audiences[$notice->audience] ?? $notice->audience }}
                            </span>
                            <span class="badge badge-{{ $notice->status ? 'success' : 'danger' }}">
                                {{ $notice->status ? 'Active' : 'Inactive' }}
                            </span>
                            <small class="text-muted">Published: {{ $notice->publish_date->format('d M Y') }}
                                @if($notice->expiry_date) — Expires: {{ $notice->expiry_date->format('d M Y') }} @endif
                            </small>
                        </div>
                        <h6 class="mb-1">{{ $notice->title }}</h6>
                        <p class="text-muted mb-1" style="white-space:pre-line">{{ Str::limit($notice->content, 200) }}</p>
                        <small class="text-muted">By: {{ $notice->creator->name ?? 'System' }}</small>
                    </div>
                    <div class="ml-3 d-flex" style="gap:4px">
                        @can('notices.edit')
                        <button class="btn btn-xs btn-{{ $notice->is_pinned ? 'warning' : 'outline-warning' }}"
                            wire:click="togglePin({{ $notice->id }})" title="{{ $notice->is_pinned ? 'Unpin' : 'Pin' }}">
                            <i class="fas fa-thumbtack"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $notice->id }})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @endcan
                        @can('notices.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $notice->id }})"
                            wire:confirm="Delete this notice?" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">No notices found.</div>
            @endforelse
        </div>
        @if($notices->hasPages())
        <div class="card-footer">{{ $notices->links() }}</div>
        @endif
    </div>
</div>
