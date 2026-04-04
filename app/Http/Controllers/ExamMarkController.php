<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamMarkController extends Controller
{
    public function create(Request $request)
    {
        $exams = Exam::where('status', true)->orderBy('name')->get();
        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        $selectedExamId = $request->exam_id;
        $selectedClassId = $request->student_class_id;
        $selectedSectionId = $request->section_id;
        $selectedSubjectId = $request->subject_id;

        $students = collect();
        $subjects = collect();

        if ($selectedClassId) {
            $class = StudentClass::with('subjects')->find($selectedClassId);
            $subjects = $class ? $class->subjects : collect();

            $studentsQuery = Student::with(['studentClass', 'section'])
                ->where('student_class_id', $selectedClassId);

            if ($selectedSectionId) {
                $studentsQuery->where('section_id', $selectedSectionId);
            }

            $students = $studentsQuery
                ->orderBy('roll_no')
                ->orderBy('first_name')
                ->get();
        }

        $subject = null;
        $existingResults = collect();

        if ($selectedExamId && $selectedSubjectId && $students->count()) {
            $subject = Subject::find($selectedSubjectId);

            $existingResults = ExamResult::where('exam_id', $selectedExamId)
                ->where('subject_id', $selectedSubjectId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('exam-marks.create', compact(
            'exams',
            'classes',
            'sections',
            'subjects',
            'students',
            'selectedExamId',
            'selectedClassId',
            'selectedSectionId',
            'selectedSubjectId',
            'subject',
            'existingResults'
        ));
    }

    public function getSubjects($classId)
    {
        $class = StudentClass::with('subjects')->find($classId);
        if (!$class) {
            return response()->json(['subjects' => []]);
        }

        return response()->json(['subjects' => $class->subjects]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'students' => ['required', 'array', 'min:1'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.obtained_marks' => ['required', 'numeric', 'min:0'],
            'students.*.remarks' => ['nullable', 'string'],
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        DB::transaction(function () use ($request, $subject) {
            foreach ($request->students as $studentData) {
                if ($studentData['obtained_marks'] > $subject->total_marks) {
                    continue;
                }

                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $request->exam_id,
                        'student_id' => $studentData['student_id'],
                        'subject_id' => $request->subject_id,
                    ],
                    [
                        'obtained_marks' => $studentData['obtained_marks'],
                        'total_marks' => $subject->total_marks,
                        'passing_marks' => $subject->passing_marks,
                        'remarks' => $studentData['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('exam-marks.create', [
            'exam_id' => $request->exam_id,
            'student_class_id' => $request->student_class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
        ])->with('success', 'Marks saved successfully.');
    }
}
