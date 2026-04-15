<?php

namespace App\Livewire;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GuardianIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $showForm = false;

    public $guardianId = null;
    public $father_name = '';
    public $mother_name = '';
    public $guardian_phone = '';
    public $guardian_cnic_no = '';
    public $email = '';
    public $address = '';
    public $status = 1;

    public $search = '';

    protected function rules(): array
    {
        return [
            'father_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'guardian_cnic_no' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }

    protected $messages = [
        'father_name.required' => 'Father name is required.',
        'email.email' => 'Please enter a valid email address.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;

        if (! $this->showForm) {
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'guardianId',
            'father_name',
            'mother_name',
            'guardian_phone',
            'guardian_cnic_no',
            'email',
            'address',
        ]);

        $this->status = 1;
        $this->resetValidation();
    }

    public function edit(int $id): void
    {
        $guardian = Guardian::findOrFail($id);

        $this->guardianId = $guardian->id;
        $this->father_name = $guardian->father_name;
        $this->mother_name = $guardian->mother_name;
        $this->guardian_phone = $guardian->guardian_phone;
        $this->guardian_cnic_no = $guardian->guardian_cnic_no;
        $this->email = $guardian->email;
        $this->address = $guardian->address;
        $this->status = (int) $guardian->status;
        $this->showForm = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $guardian = $this->findExistingGuardianForForm($validated, $this->guardianId);

            $user = null;

            if (!empty($validated['email'])) {
                $user = User::firstOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['father_name'],
                        'password' => bcrypt('password'),
                    ]
                );

                $user->update([
                    'name' => $validated['father_name'],
                ]);

                if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole')) {
                    if (!$user->hasRole('parent')) {
                        $user->assignRole('parent');
                    }
                }
            }

            if ($guardian) {
                $guardian->update([
                    'user_id' => $user ? $user->id : $guardian->user_id,
                    'father_name' => $validated['father_name'],
                    'mother_name' => $this->emptyToNull($validated['mother_name'] ?? null),
                    'guardian_phone' => $this->emptyToNull($validated['guardian_phone'] ?? null),
                    'guardian_cnic_no' => $this->emptyToNull($validated['guardian_cnic_no'] ?? null),
                    'email' => $this->emptyToNull($validated['email'] ?? null),
                    'address' => $this->emptyToNull($validated['address'] ?? null),
                    'status' => (bool) $validated['status'],
                ]);

                session()->flash('success', 'Guardian updated successfully.');
            } else {
                Guardian::create([
                    'user_id' => $user ? $user->id : null,
                    'father_name' => $validated['father_name'],
                    'mother_name' => $this->emptyToNull($validated['mother_name'] ?? null),
                    'guardian_phone' => $this->emptyToNull($validated['guardian_phone'] ?? null),
                    'guardian_cnic_no' => $this->emptyToNull($validated['guardian_cnic_no'] ?? null),
                    'email' => $this->emptyToNull($validated['email'] ?? null),
                    'address' => $this->emptyToNull($validated['address'] ?? null),
                    'status' => (bool) $validated['status'],
                ]);

                session()->flash('success', 'Guardian added successfully.');
            }
        });

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    private function findExistingGuardianForForm(array $validated, ?int $ignoreId = null): ?Guardian
    {
        if ($ignoreId) {
            return Guardian::find($ignoreId);
        }

        $cnic = $this->emptyToNull($validated['guardian_cnic_no'] ?? null);
        $phone = $this->emptyToNull($validated['guardian_phone'] ?? null);
        $email = $this->emptyToNull($validated['email'] ?? null);

        if ($cnic) {
            $guardian = Guardian::where('guardian_cnic_no', $cnic)->first();
            if ($guardian) {
                return $guardian;
            }
        }

        if ($phone) {
            $guardian = Guardian::where('guardian_phone', $phone)->first();
            if ($guardian) {
                return $guardian;
            }
        }

        if ($email) {
            $guardian = Guardian::where('email', $email)->first();
            if ($guardian) {
                return $guardian;
            }
        }

        return null;
    }

    private function emptyToNull($value): mixed
    {
        return filled($value) ? $value : null;
    }

    public function render()
    {
        $parents = Guardian::query()
            ->select('id', 'user_id', 'father_name', 'mother_name', 'guardian_phone', 'guardian_cnic_no', 'email', 'address', 'status')
            ->with('user:id,name,email')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('father_name', 'like', '%' . $this->search . '%')
                        ->orWhere('mother_name', 'like', '%' . $this->search . '%')
                        ->orWhere('guardian_phone', 'like', '%' . $this->search . '%')
                        ->orWhere('guardian_cnic_no', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('id')
            ->paginate(15);

        return view('livewire.guardian-index', compact('parents'));
    }
}
