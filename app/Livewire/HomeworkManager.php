<?php

namespace App\Livewire;

use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;

class HomeworkManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'homework';

    // Homework form
    public bool $showForm = false;
    public ?int $homeworkId = null;
    public string $title          = '';
    public string $description    = '';
    public string $student_class_id = '';
    public string $section_id     = '';
    public string $subject_id     = '';
    public string $teacher_id     = '';
    public string $assigned_date  = '';
    public string $due_date       = '';
    public bool   $hw_status      = true;

    // Review form
    public bool   $showReviewForm  = false;
    public ?int   $reviewSubmId    = null;
    public string $review_marks    = '';
    public string $review_remarks  = '';
    public string $review_status   = 'reviewed';

    public string $search     = '';
    public string $filterClass = '';

    protected $queryString = ['search' => ['except' => ''], 'filterClass' => ['except' => '']];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedStudentClassId(): void { $this->section_id = ''; $this->subject_id = ''; }

    public function getSectionsProperty()
    {
        if (!$this->student_class_id) return collect();
        return Section::whereHas('classes', fn($q) => $q->where('student_classes.id', $this->student_class_id))->get();
    }

    public function getSubjectsProperty()
    {
        if (!$this->student_class_id) return collect();
        return StudentClass::find($this->student_class_id)?->subjects ?? collect();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->assigned_date = now()->format('Y-m-d');
        $this->due_date      = now()->addDays(7)->format('Y-m-d');
        $this->showForm      = true;
    }

    public function edit(int $id): void
    {
        $hw = Homework::findOrFail($id);
        $this->homeworkId       = $hw->id;
        $this->title            = $hw->title;
        $this->description      = $hw->description ?? '';
        $this->student_class_id = (string) $hw->student_class_id;
        $this->section_id       = $hw->section_id ? (string) $hw->section_id : '';
        $this->subject_id       = (string) $hw->subject_id;
        $this->teacher_id       = $hw->teacher_id ? (string) $hw->teacher_id : '';
        $this->assigned_date    = $hw->assigned_date->format('Y-m-d');
        $this->due_date         = $hw->due_date->format('Y-m-d');
        $this->hw_status        = (bool) $hw->status;
        $this->showForm         = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'title'           => ['required', 'string', 'max:255'],
            'student_class_id'=> ['required', 'exists:student_classes,id'],
            'subject_id'      => ['required', 'exists:subjects,id'],
            'assigned_date'   => ['required', 'date'],
            'due_date'        => ['required', 'date', 'after_or_equal:assigned_date'],
        ]);

        $data = [
            'title'            => $this->title,
            'description'      => $this->description ?: null,
            'student_class_id' => $this->student_class_id,
            'section_id'       => $this->section_id ?: null,
            'subject_id'       => $this->subject_id,
            'teacher_id'       => $this->teacher_id ?: null,
            'assigned_date'    => $this->assigned_date,
            'due_date'         => $this->due_date,
            'status'           => $this->hw_status,
            'created_by'       => auth()->id(),
        ];

        if ($this->homeworkId) {
            Homework::findOrFail($this->homeworkId)->update($data);
            session()->flash('success', 'Homework updated successfully.');
        } else {
            Homework::create($data);
            session()->flash('success', 'Homework assigned successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Homework::findOrFail($id)->delete();
        session()->flash('success', 'Homework deleted.');
        $this->resetPage();
    }

    public function openReview(int $submId): void
    {
        $sub = HomeworkSubmission::findOrFail($submId);
        $this->reviewSubmId    = $sub->id;
        $this->review_marks    = $sub->marks ?? '';
        $this->review_remarks  = $sub->teacher_remarks ?? '';
        $this->review_status   = $sub->status === 'submitted' ? 'reviewed' : $sub->status;
        $this->showReviewForm  = true;
    }

    public function saveReview(): void
    {
        $this->validate([
            'review_status' => ['required', 'in:reviewed,late,missing'],
            'review_marks'  => ['nullable', 'integer', 'min:0'],
        ]);

        HomeworkSubmission::findOrFail($this->reviewSubmId)->update([
            'status'          => $this->review_status,
            'marks'           => $this->review_marks ?: null,
            'teacher_remarks' => $this->review_remarks ?: null,
        ]);

        session()->flash('success', 'Review saved.');
        $this->showReviewForm = false;
        $this->reset(['reviewSubmId', 'review_marks', 'review_remarks']);
    }

    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    public function resetForm(): void
    {
        $this->reset(['homeworkId', 'title', 'description', 'student_class_id', 'section_id', 'subject_id', 'teacher_id', 'assigned_date', 'due_date']);
        $this->hw_status = true;
        $this->resetValidation();
    }

    public function render()
    {
        $classes  = StudentClass::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        $homework = Homework::with(['studentClass', 'section', 'subject', 'teacher', 'submissions'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterClass, fn($q) => $q->where('student_class_id', $this->filterClass))
            ->allowedForUser(auth()->user())
            ->latest('id')
            ->paginate(15);

        $submissions = HomeworkSubmission::with(['homework.subject', 'homework.studentClass', 'student'])
            ->latest('id')
            ->paginate(15, ['*'], 'subPage');

        return view('livewire.homework-manager', compact('classes', 'teachers', 'homework', 'submissions'));
    }
}
