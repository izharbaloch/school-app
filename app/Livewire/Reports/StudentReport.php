<?php

namespace App\Livewire\Reports;

use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentReport extends Component
{
    public string $filter_class   = '';
    public string $filter_section = '';
    public string $filter_gender  = '';
    public string $filter_status  = 'active';

    public function updatedFilterClass(): void
    {
        $this->filter_section = '';
    }

    public function getSectionsProperty()
    {
        if (!$this->filter_class) return collect();
        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->filter_class))
            ->orderBy('name')->get(['id', 'name']);
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        $user = Auth::user();

        $students = Student::allowedForUser($user)
            ->with(['studentClass:id,name', 'section:id,name', 'guardian:id,father_name,guardian_phone'])
            ->when($this->filter_class,   fn($q) => $q->where('student_class_id', $this->filter_class))
            ->when($this->filter_section, fn($q) => $q->where('section_id', $this->filter_section))
            ->when($this->filter_gender,  fn($q) => $q->where('gender', $this->filter_gender))
            ->when($this->filter_status === 'active',   fn($q) => $q->where('status', true))
            ->when($this->filter_status === 'inactive', fn($q) => $q->where('status', false))
            ->orderBy('student_class_id')
            ->orderBy('first_name')
            ->get();

        return view('livewire.reports.student-report', [
            'students' => $students,
            'classes'  => $this->classes,
            'sections' => $this->sections,
            'total'    => $students->count(),
            'maleCount'   => $students->where('gender', 'male')->count(),
            'femaleCount' => $students->where('gender', 'female')->count(),
        ]);
    }
}
