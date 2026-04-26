<?php

namespace App\Livewire\Student;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class StudentIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $students = Student::with([
                'studentClass:id,name',
                'section:id,name',
                'profilePhoto:id,student_id,file_path',
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('roll_no', 'like', '%' . $this->search . '%')
                        ->orWhere('father_name', 'like', '%' . $this->search . '%');
                });
            })
            ->select('id', 'roll_no', 'first_name', 'last_name', 'father_name', 'student_class_id', 'section_id', 'status')
            ->orderBy('student_class_id')
            ->orderBy('section_id')
            ->paginate(25);

        return view('livewire.student.student-index', [
            'students' => $students,
        ]);
    }
}
