<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with([
            'studentClass',
            'section',
            'takenBy',
            'attendanceStudents',
        ])->latest('attendance_date');

        if ($request->filled('attendance_date')) {
            $query->whereDate('attendance_date', $request->attendance_date);
        }

        if ($request->filled('student_class_id')) {
            $query->where('student_class_id', $request->student_class_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $attendances = $query->paginate(10)->withQueryString();

        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('attendances.index', compact('attendances', 'classes', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        $selectedClassId = $request->student_class_id;
        $selectedSectionId = $request->section_id;
        $attendanceDate = $request->attendance_date ?? now()->toDateString();

        $students = collect();

        if ($selectedClassId && $selectedSectionId) {
            $students = Student::where('student_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->orderBy('roll_no')
                ->orderBy('first_name')
                ->get();
        }

        return view('attendances.create', compact(
            'classes',
            'sections',
            'students',
            'selectedClassId',
            'selectedSectionId',
            'attendanceDate'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendance_date'   => ['required', 'date'],
            'student_class_id'  => ['required', 'exists:student_classes,id'],
            'section_id'        => ['required', 'exists:sections,id'],
            'remarks'           => ['nullable', 'string'],
            'students'          => ['required', 'array', 'min:1'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.status' => ['required', 'in:present,absent,leave,late'],
            'students.*.remarks' => ['nullable', 'string'],
        ]);

        $alreadyExists = Attendance::whereDate('attendance_date', $request->attendance_date)
            ->where('student_class_id', $request->student_class_id)
            ->where('section_id', $request->section_id)
            ->exists();

        if ($alreadyExists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Attendance already exists for this class, section and date.');
        }

        DB::transaction(function () use ($request) {
            $attendance = Attendance::create([
                'attendance_date'  => $request->attendance_date,
                'student_class_id' => $request->student_class_id,
                'section_id'       => $request->section_id,
                'taken_by'         => auth()->id(),
                'remarks'          => $request->remarks,
            ]);

            foreach ($request->students as $studentData) {
                AttendanceStudent::create([
                    'attendance_id' => $attendance->id,
                    'student_id'    => $studentData['student_id'],
                    'status'        => $studentData['status'],
                    'remarks'       => $studentData['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance marked successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load([
            'studentClass',
            'section',
            'takenBy',
            'attendanceStudents.student',
        ]);

        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load([
            'attendanceStudents.student',
            'studentClass',
            'section',
        ]);

        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('attendances.edit', compact('attendance', 'classes', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'attendance_date'   => ['required', 'date'],
            'student_class_id'  => ['required', 'exists:student_classes,id'],
            'section_id'        => ['required', 'exists:sections,id'],
            'remarks'           => ['nullable', 'string'],
            'students'          => ['required', 'array', 'min:1'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.status' => ['required', 'in:present,absent,leave,late'],
            'students.*.remarks' => ['nullable', 'string'],
        ]);

        $alreadyExists = Attendance::whereDate('attendance_date', $request->attendance_date)
            ->where('student_class_id', $request->student_class_id)
            ->where('section_id', $request->section_id)
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($alreadyExists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Attendance already exists for this class, section and date.');
        }

        DB::transaction(function () use ($request, $attendance) {
            $attendance->update([
                'attendance_date'  => $request->attendance_date,
                'student_class_id' => $request->student_class_id,
                'section_id'       => $request->section_id,
                'remarks'          => $request->remarks,
            ]);

            $attendance->attendanceStudents()->delete();

            foreach ($request->students as $studentData) {
                AttendanceStudent::create([
                    'attendance_id' => $attendance->id,
                    'student_id'    => $studentData['student_id'],
                    'status'        => $studentData['status'],
                    'remarks'       => $studentData['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}
