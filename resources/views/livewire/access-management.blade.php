<div>
    <div class="row">

        {{-- ===================== ROLE ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>{{ $roleEditId ? 'Edit Role' : 'Add Role' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('role_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('role_success') }}
                            </div>
                        </div>
                    @endif

                    @if (session()->has('role_error'))
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('role_error') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $roleEditId ? 'updateRole' : 'saveRole' }}">
                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" class="form-control @error('role_name') is-invalid @enderror"
                                wire:model.defer="role_name" placeholder="Enter role name">
                            @error('role_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($roleEditId)
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> Update Role
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="cancelRoleEdit">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Role
                            </button>
                        @endif
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editRole({{ $role->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this role?')) { @this.call('deleteRole', {{ $role->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No roles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== PERMISSION ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>{{ $permissionEditId ? 'Edit Permission' : 'Add Permission' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('permission_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('permission_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $permissionEditId ? 'updatePermission' : 'savePermission' }}">
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input type="text" class="form-control @error('permission_name') is-invalid @enderror"
                                wire:model.defer="permission_name" placeholder="Enter permission name">
                            @error('permission_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($permissionEditId)
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> Update Permission
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="cancelPermissionEdit">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Permission
                            </button>
                        @endif
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editPermission({{ $permission->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this permission?')) { @this.call('deletePermission', {{ $permission->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No permissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- ===================== ASSIGN PERMISSIONS TO ROLE ===================== --}}
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>Assign Permissions To Role</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('assignment_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('assignment_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="assignPermissionsToRole">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Select Role</label>
                                <select class="form-control @error('selected_role_id') is-invalid @enderror"
                                    wire:model="selected_role_id">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('selected_role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-8">
                                <label>Select Permissions</label>
                                <div class="border rounded p-3" style="max-height: 180px; overflow-y: auto;">
                                    <div class="row">
                                        @forelse ($permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="permission_{{ $permission->id }}"
                                                        value="{{ $permission->id }}"
                                                        wire:model="selected_permissions">
                                                    <label class="custom-control-label"
                                                        for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <span class="text-muted">No permissions found.</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                @error('selected_permissions.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link mr-1"></i> Assign Permissions
                        </button>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>Assigned Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rolePermissionAssignments as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @forelse ($role->permissions as $permission)
                                                <span class="badge badge-primary mr-1 mb-1">
                                                    {{ $permission->name }}
                                                    <a href="javascript:void(0)" class="text-white ml-1"
                                                        wire:click="removePermissionFromRole({{ $role->id }}, {{ $permission->id }})"
                                                        title="Remove">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </span>
                                            @empty
                                                <span class="text-muted">No permissions assigned</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No role-permission assignments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- ===================== USER ===================== --}}
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $userEditId ? 'Edit User' : 'Add User' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('user_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('user_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $userEditId ? 'updateUser' : 'saveUser' }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Name</label>
                                <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                    wire:model.defer="user_name" placeholder="Enter user name">
                                @error('user_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Email</label>
                                <input type="email" class="form-control @error('user_email') is-invalid @enderror"
                                    wire:model.defer="user_email" placeholder="Enter user email">
                                @error('user_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Password {{ $userEditId ? '(Optional)' : '' }}</label>
                                <input type="password"
                                    class="form-control @error('user_password') is-invalid @enderror"
                                    wire:model.defer="user_password" placeholder="Enter password">
                                @error('user_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>Role</label>
                                <select class="form-control @error('selected_user_role') is-invalid @enderror"
                                    wire:model.defer="selected_user_role">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('selected_user_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-1 d-flex align-items-end">
                                @if ($userEditId)
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save"></i>
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancelUserEdit">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @forelse ($user->roles as $role)
                                                <span class="badge badge-info mr-1">{{ $role->name }}</span>
                                            @empty
                                                <span class="text-muted">No role assigned</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editUser({{ $user->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this user?')) { @this.call('deleteUser', {{ $user->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
