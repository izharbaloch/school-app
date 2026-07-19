<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        $student->load([
            'studentClass:id,name',
            'section:id,name',
            'attachments:id,student_id,document_type,file_path',
            'profilePhoto:id,student_id,file_path',
        ]);

        $attachments = $student->attachments->groupBy('document_type');

        return view('students.show', compact('student', 'attachments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        return view('students.edit', compact('student'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        // Soft delete only — attachments and financial/academic history stay on
        // disk and in the database so the student can be restored if needed.
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
