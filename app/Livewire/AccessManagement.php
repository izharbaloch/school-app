<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    // =========================
    // Role Properties
    // =========================
    public string $role_name = '';
    public $roles = [];
    public $roleEditId = null;

    // =========================
    // Permission Properties
    // =========================
    public string $permission_name = '';
    public $permissions = [];
    public $permissionEditId = null;

    // =========================
    // Role Permission Assignment
    // =========================
    public $selected_role_id = '';
    public $selected_permissions = [];
    public $rolePermissionAssignments = [];

    // =========================
    // User Properties
    // =========================
    public string $user_name = '';
    public string $user_email = '';
    public string $user_password = '';
    public $selected_user_role = '';
    public $userEditId = null;

    public function mount()
    {
        $this->loadRoles();
        $this->loadPermissions();
        $this->loadRolePermissionAssignments();
    }

    // =========================
    // Load Data
    // =========================
    public function loadRoles()
    {
        $this->roles = Role::latest()->get();
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::latest()->get();
    }

    #[Computed]
    public function users()
    {
        return User::with('roles')->latest()->paginate(10);
    }

    public function loadRolePermissionAssignments()
    {
        $this->rolePermissionAssignments = Role::with('permissions')->latest()->get();
    }

    // =========================
    // Role Methods
    // =========================
    public function saveRole()
    {
        $validated = $this->validate([
            'role_name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => $validated['role_name'],
            'guard_name' => 'web',
        ]);

        $this->resetRoleForm();
        $this->loadRoles();
        $this->loadRolePermissionAssignments();

        session()->flash('role_success', 'Role added successfully.');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);

        $this->roleEditId = $role->id;
        $this->role_name = $role->name;

        $this->resetValidation();
    }

    public function updateRole()
    {
        $validated = $this->validate([
            'role_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->roleEditId),
            ],
        ]);

        $role = Role::findOrFail($this->roleEditId);
        $role->update([
            'name' => $validated['role_name'],
        ]);

        $this->resetRoleForm();
        $this->loadRoles();
        $this->loadRolePermissionAssignments();

        session()->flash('role_success', 'Role updated successfully.');
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            session()->flash('role_error', 'This role is assigned to users, so it cannot be deleted.');
            return;
        }

        $role->delete();

        if ($this->roleEditId == $id) {
            $this->resetRoleForm();
        }

        $this->loadRoles();
        $this->loadRolePermissionAssignments();

        session()->flash('role_success', 'Role deleted successfully.');
    }

    public function cancelRoleEdit()
    {
        $this->resetRoleForm();
        $this->resetValidation();
    }

    public function resetRoleForm()
    {
        $this->roleEditId = null;
        $this->role_name = '';
    }

    // =========================
    // Permission Methods
    // =========================
    public function savePermission()
    {
        $validated = $this->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $validated['permission_name'],
            'guard_name' => 'web',
        ]);

        $this->resetPermissionForm();
        $this->loadPermissions();
        $this->loadRolePermissionAssignments();

        session()->flash('permission_success', 'Permission added successfully.');
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);

        $this->permissionEditId = $permission->id;
        $this->permission_name = $permission->name;

        $this->resetValidation();
    }

    public function updatePermission()
    {
        $validated = $this->validate([
            'permission_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($this->permissionEditId),
            ],
        ]);

        $permission = Permission::findOrFail($this->permissionEditId);
        $permission->update([
            'name' => $validated['permission_name'],
        ]);

        $this->resetPermissionForm();
        $this->loadPermissions();
        $this->loadRolePermissionAssignments();

        session()->flash('permission_success', 'Permission updated successfully.');
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        if ($this->permissionEditId == $id) {
            $this->resetPermissionForm();
        }

        $this->loadPermissions();
        $this->loadRolePermissionAssignments();

        session()->flash('permission_success', 'Permission deleted successfully.');
    }

    public function cancelPermissionEdit()
    {
        $this->resetPermissionForm();
        $this->resetValidation();
    }

    public function resetPermissionForm()
    {
        $this->permissionEditId = null;
        $this->permission_name = '';
    }

    // =========================
    // Assign Permissions To Role
    // =========================
    public function updatedSelectedRoleId($value)
    {
        if ($value) {
            $role = Role::findById($value);
            $this->selected_permissions = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected_permissions = [];
        }
    }

    public function assignPermissionsToRole()
    {
        $validated = $this->validate([
            'selected_role_id' => 'required|exists:roles,id',
            'selected_permissions' => 'nullable|array',
            'selected_permissions.*' => 'exists:permissions,id',
        ], [
            'selected_role_id.required' => 'Please select a role.',
        ]);

        $role = Role::findById($validated['selected_role_id']);
        $permissionNames = Permission::whereIn('id', $validated['selected_permissions'] ?? [])->pluck('name')->toArray();

        $role->syncPermissions($permissionNames);

        $this->loadRolePermissionAssignments();

        session()->flash('assignment_success', 'Permissions assigned to role successfully.');
    }

    public function removePermissionFromRole($roleId, $permissionId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($permissionId);

        $role->revokePermissionTo($permission->name);

        if ((string) $this->selected_role_id === (string) $roleId) {
            $this->selected_permissions = $role->fresh()->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }

        $this->loadRolePermissionAssignments();

        session()->flash('assignment_success', 'Permission removed from role successfully.');
    }

    // =========================
    // User Methods
    // =========================
    public function saveUser()
    {
        $validated = $this->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,email',
            'user_password' => 'required|string|min:6',
            'selected_user_role' => 'required|exists:roles,id',
        ], [
            'selected_user_role.required' => 'Please select a role.',
        ]);

        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['user_password']),
        ]);

        $role = Role::findById($validated['selected_user_role']);
        $user->assignRole($role->name);

        $this->resetUserForm();

        session()->flash('user_success', 'User added successfully.');
    }

    public function editUser($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $this->userEditId = $user->id;
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->user_password = '';
        $this->selected_user_role = optional($user->roles->first())->id ?? '';

        $this->resetValidation();
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userEditId),
            ],
            'user_password' => 'nullable|string|min:6',
            'selected_user_role' => 'required|exists:roles,id',
        ], [
            'selected_user_role.required' => 'Please select a role.',
        ]);

        $user = User::findOrFail($this->userEditId);

        $data = [
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
        ];

        if (!empty($validated['user_password'])) {
            $data['password'] = Hash::make($validated['user_password']);
        }

        $user->update($data);

        $role = Role::findById($validated['selected_user_role']);
        $user->syncRoles([$role->name]);

        $this->resetUserForm();

        session()->flash('user_success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();

        if ($this->userEditId == $id) {
            $this->resetUserForm();
        }

        session()->flash('user_success', 'User deleted successfully.');
    }

    public function cancelUserEdit()
    {
        $this->resetUserForm();
        $this->resetValidation();
    }

    public function resetUserForm()
    {
        $this->userEditId = null;
        $this->user_name = '';
        $this->user_email = '';
        $this->user_password = '';
        $this->selected_user_role = '';
    }

    public function render()
    {
        return view('livewire.access-management', [
            'users' => $this->users,
        ]);
    }
}
