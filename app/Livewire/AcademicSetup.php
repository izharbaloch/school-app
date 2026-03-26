<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentClass;

class AcademicSetup extends Component
{
    // =========================
    // Class Properties
    // =========================
    public string $class_name = '';
    public string $class_numeric_name = '';
    public string $class_fee = '';
    public $class_status = 1;
    public $classes = [];
    public $classEditId = null;

    // =========================
    // Section Properties
    // =========================
    public string $section_name = '';
    public $section_status = 1;
    public $sections = [];
    public $sectionEditId = null;

    // =========================
    // Subject Properties
    // =========================
    public string $subject_name = '';
    public $subject_status = 1;
    public $subjects = [];
    public $subjectEditId = null;

    // =========================
    // Assignment Properties
    // =========================
    public $assign_class_id = '';
    public $assign_section_id = '';

    public $assign_subject_class_id = '';
    public $assign_subject_id = '';

    public $classSectionAssignments = [];
    public $classSubjectAssignments = [];

    public function mount()
    {
        $this->loadClasses();
        $this->loadSections();
        $this->loadSubjects();
        $this->loadAssignments();
    }

    // =========================
    // Load Data
    // =========================
    public function loadClasses()
    {
        $this->classes = StudentClass::latest()->get();
    }

    public function loadSections()
    {
        $this->sections = Section::latest()->get();
    }

    public function loadSubjects()
    {
        $this->subjects = Subject::latest()->get();
    }

    public function loadAssignments()
    {
        $this->classSectionAssignments = StudentClass::with(['sections'])
            ->latest()
            ->get();

        $this->classSubjectAssignments = StudentClass::with(['subjects'])
            ->latest()
            ->get();
    }

    // =========================
    // Class Methods
    // =========================
    public function saveClass()
    {
        $validated = $this->validate([
            'class_name' => 'required|string|max:255|unique:student_classes,name',
            'class_numeric_name' => 'nullable|integer|unique:student_classes,numeric_name',
            'class_fee' => 'nullable|integer|unique:student_classes,fee',
            'class_status' => 'required|boolean',
        ]);

        StudentClass::create([
            'name' => $validated['class_name'],
            'numeric_name' => $validated['class_numeric_name'] !== '' ? $validated['class_numeric_name'] : null,
            'fee' => $validated['class_fee'],
            'status' => (bool) $validated['class_status'],
        ]);

        $this->resetClassForm();
        $this->loadClasses();
        $this->loadAssignments();

        session()->flash('class_success', 'Class added successfully.');
    }

    public function editClass($id)
    {
        $class = StudentClass::findOrFail($id);

        $this->classEditId = $class->id;
        $this->class_name = $class->name;
        $this->class_numeric_name = (string) ($class->numeric_name ?? '');
        $this->class_fee = (string) ($class->fee ?? '');
        $this->class_status = $class->status;

        $this->resetValidation();
    }

    public function updateClass()
    {
        $validated = $this->validate([
            'class_name' => 'required|string|max:255|unique:student_classes,name,' . $this->classEditId,
            'class_numeric_name' => 'nullable|integer|unique:student_classes,numeric_name,' . $this->classEditId,
            'class_fee' => 'nullable|integer|unique:student_classes,fee,' . $this->classEditId,
            'class_status' => 'required|boolean',
        ]);

        $class = StudentClass::findOrFail($this->classEditId);

        $class->update([
            'name' => $validated['class_name'],
            'numeric_name' => $validated['class_numeric_name'] !== '' ? $validated['class_numeric_name'] : null,
            'fee' => $validated['class_fee'],
            'status' => (bool) $validated['class_status'],
        ]);

        $this->resetClassForm();
        $this->loadClasses();
        $this->loadAssignments();

        session()->flash('class_success', 'Class updated successfully.');
    }

    public function deleteClass($id)
    {
        StudentClass::findOrFail($id)->delete();

        if ($this->classEditId == $id) {
            $this->resetClassForm();
        }

        $this->loadClasses();
        $this->loadAssignments();

        session()->flash('class_success', 'Class deleted successfully.');
    }

    public function cancelClassEdit()
    {
        $this->resetClassForm();
        $this->resetValidation();
    }

    public function resetClassForm()
    {
        $this->classEditId = null;
        $this->class_name = '';
        $this->class_numeric_name = '';
        $this->class_fee = '';
        $this->class_status = 1;
    }

    // =========================
    // Section Methods
    // =========================
    public function saveSection()
    {
        $validated = $this->validate([
            'section_name' => 'required|string|max:255|unique:sections,name',
            'section_status' => 'required|boolean',
        ]);

        Section::create([
            'name' => $validated['section_name'],
            'status' => (bool) $validated['section_status'],
        ]);

        $this->resetSectionForm();
        $this->loadSections();
        $this->loadAssignments();

        session()->flash('section_success', 'Section added successfully.');
    }

    public function editSection($id)
    {
        $section = Section::findOrFail($id);

        $this->sectionEditId = $section->id;
        $this->section_name = $section->name;
        $this->section_status = $section->status;

        $this->resetValidation();
    }

    public function updateSection()
    {
        $validated = $this->validate([
            'section_name' => 'required|string|max:255|unique:sections,name,' . $this->sectionEditId,
            'section_status' => 'required|boolean',
        ]);

        $section = Section::findOrFail($this->sectionEditId);

        $section->update([
            'name' => $validated['section_name'],
            'status' => (bool) $validated['section_status'],
        ]);

        $this->resetSectionForm();
        $this->loadSections();
        $this->loadAssignments();

        session()->flash('section_success', 'Section updated successfully.');
    }

    public function deleteSection($id)
    {
        Section::findOrFail($id)->delete();

        if ($this->sectionEditId == $id) {
            $this->resetSectionForm();
        }

        $this->loadSections();
        $this->loadAssignments();

        session()->flash('section_success', 'Section deleted successfully.');
    }

    public function cancelSectionEdit()
    {
        $this->resetSectionForm();
        $this->resetValidation();
    }

    public function resetSectionForm()
    {
        $this->sectionEditId = null;
        $this->section_name = '';
        $this->section_status = 1;
    }

    // =========================
    // Subject Methods
    // =========================
    public function saveSubject()
    {
        $validated = $this->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,name',
            'subject_status' => 'required|boolean',
        ]);

        Subject::create([
            'name' => $validated['subject_name'],
            'status' => (bool) $validated['subject_status'],
        ]);

        $this->resetSubjectForm();
        $this->loadSubjects();
        $this->loadAssignments();

        session()->flash('subject_success', 'Subject added successfully.');
    }

    public function editSubject($id)
    {
        $subject = Subject::findOrFail($id);

        $this->subjectEditId = $subject->id;
        $this->subject_name = $subject->name;
        $this->subject_status = $subject->status;

        $this->resetValidation();
    }

    public function updateSubject()
    {
        $validated = $this->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,name,' . $this->subjectEditId,
            'subject_status' => 'required|boolean',
        ]);

        $subject = Subject::findOrFail($this->subjectEditId);

        $subject->update([
            'name' => $validated['subject_name'],
            'status' => (bool) $validated['subject_status'],
        ]);

        $this->resetSubjectForm();
        $this->loadSubjects();
        $this->loadAssignments();

        session()->flash('subject_success', 'Subject updated successfully.');
    }

    public function deleteSubject($id)
    {
        Subject::findOrFail($id)->delete();

        if ($this->subjectEditId == $id) {
            $this->resetSubjectForm();
        }

        $this->loadSubjects();
        $this->loadAssignments();

        session()->flash('subject_success', 'Subject deleted successfully.');
    }

    public function cancelSubjectEdit()
    {
        $this->resetSubjectForm();
        $this->resetValidation();
    }

    public function resetSubjectForm()
    {
        $this->subjectEditId = null;
        $this->subject_name = '';
        $this->subject_status = 1;
    }

    // =========================
    // Class Section Assignment
    // =========================
    public function assignSectionToClass()
    {
        $validated = $this->validate([
            'assign_class_id' => 'required|exists:student_classes,id',
            'assign_section_id' => 'required|exists:sections,id',
        ], [
            'assign_class_id.required' => 'Please select a class.',
            'assign_section_id.required' => 'Please select a section.',
        ]);

        $class = StudentClass::findOrFail($validated['assign_class_id']);

        if ($class->sections()->where('sections.id', $validated['assign_section_id'])->exists()) {
            session()->flash('assignment_section_error', 'This section is already assigned to the selected class.');
            return;
        }

        $class->sections()->attach($validated['assign_section_id']);

        $this->assign_class_id = '';
        $this->assign_section_id = '';

        $this->loadAssignments();

        session()->flash('assignment_section_success', 'Section assigned to class successfully.');
    }

    public function removeSectionAssignment($classId, $sectionId)
    {
        $class = StudentClass::findOrFail($classId);
        $class->sections()->detach($sectionId);

        $this->loadAssignments();

        session()->flash('assignment_section_success', 'Section assignment removed successfully.');
    }

    // =========================
    // Class Subject Assignment
    // =========================
    public function assignSubjectToClass()
    {
        $validated = $this->validate([
            'assign_subject_class_id' => 'required|exists:student_classes,id',
            'assign_subject_id' => 'required|exists:subjects,id',
        ], [
            'assign_subject_class_id.required' => 'Please select a class.',
            'assign_subject_id.required' => 'Please select a subject.',
        ]);

        $class = StudentClass::findOrFail($validated['assign_subject_class_id']);

        if ($class->subjects()->where('subjects.id', $validated['assign_subject_id'])->exists()) {
            session()->flash('assignment_subject_error', 'This subject is already assigned to the selected class.');
            return;
        }

        $class->subjects()->attach($validated['assign_subject_id']);

        $this->assign_subject_class_id = '';
        $this->assign_subject_id = '';

        $this->loadAssignments();

        session()->flash('assignment_subject_success', 'Subject assigned to class successfully.');
    }

    public function removeSubjectAssignment($classId, $subjectId)
    {
        $class = StudentClass::findOrFail($classId);
        $class->subjects()->detach($subjectId);

        $this->loadAssignments();

        session()->flash('assignment_subject_success', 'Subject assignment removed successfully.');
    }

    public function render()
    {
        return view('livewire.academic-setup');
    }
}
