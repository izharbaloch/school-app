<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentFee::with([
            'student.studentClass',
            'student.section',
            'feeType',
        ])->latest();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fee_type_id')) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $studentFees = $query->paginate(15)->withQueryString();
        $students = Student::orderBy('first_name')->get();
        $feeTypes = FeeType::where('status', true)->orderBy('name')->get();

        return view('student-fees.index', compact('studentFees', 'students', 'feeTypes'));
    }

    public function create()
    {
        $students = Student::with(['studentClass', 'section'])->orderBy('first_name')->get();
        $feeTypes = FeeType::where('status', true)->orderBy('name')->get();

        return view('student-fees.create', compact('students', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        $exists = StudentFee::where('student_id', $request->student_id)
            ->where('fee_type_id', $request->fee_type_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'This fee already exists for the selected student.');
        }

        StudentFee::create([
            'student_id' => $request->student_id,
            'fee_type_id' => $request->fee_type_id,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
            'discount' => $request->discount ?? 0,
            'fine' => $request->fine ?? 0,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => StudentFee::UNPAID,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('student-fees.index')->with('success', 'Student fee assigned successfully.');
    }

    public function show(StudentFee $studentFee)
    {
        $studentFee->load([
            'student.studentClass',
            'student.section',
            'feeType',
            'payments.receivedBy',
        ]);

        return view('student-fees.show', compact('studentFee'));
    }

    public function bulkCreate(Request $request)
    {
        $classes = StudentClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $feeTypes = FeeType::where('status', true)->orderBy('name')->get();

        $selectedClassId = $request->student_class_id;
        $selectedSectionId = $request->section_id;
        $selectedFeeTypeId = $request->fee_type_id;

        $students = collect();
        $structureAmount = null;

        if ($selectedClassId) {
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

        if ($selectedClassId && $selectedFeeTypeId) {
            $structure = FeeStructure::where('student_class_id', $selectedClassId)
                ->where('fee_type_id', $selectedFeeTypeId)
                ->first();

            $structureAmount = $structure?->amount;
        }

        return view('student-fees.bulk-create', compact(
            'classes',
            'sections',
            'feeTypes',
            'students',
            'selectedClassId',
            'selectedSectionId',
            'selectedFeeTypeId',
            'structureAmount'
        ));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'due_date' => ['nullable', 'date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'selected_students' => ['required', 'array', 'min:1'],
            'selected_students.*' => ['exists:students,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $studentsQuery = Student::where('student_class_id', $request->student_class_id)
            ->whereIn('id', $request->selected_students);

        if ($request->filled('section_id')) {
            $studentsQuery->where('section_id', $request->section_id);
        }

        $students = $studentsQuery->get();

        if ($students->isEmpty()) {
            return back()->withInput()->with('error', 'No students found.');
        }

        $structure = FeeStructure::where('student_class_id', $request->student_class_id)
            ->where('fee_type_id', $request->fee_type_id)
            ->first();

        $amount = $request->filled('amount') ? $request->amount : ($structure?->amount ?? 0);

        if ($amount <= 0) {
            return back()->withInput()->with('error', 'Amount not found. Please set fee structure or enter amount manually.');
        }

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($request, $students, $amount, &$createdCount, &$skippedCount) {
            foreach ($students as $student) {
                $exists = StudentFee::where('student_id', $student->id)
                    ->where('fee_type_id', $request->fee_type_id)
                    ->where('month', $request->month)
                    ->where('year', $request->year)
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                StudentFee::create([
                    'student_id' => $student->id,
                    'fee_type_id' => $request->fee_type_id,
                    'month' => $request->month,
                    'year' => $request->year,
                    'amount' => $amount,
                    'discount' => $request->discount ?? 0,
                    'fine' => $request->fine ?? 0,
                    'paid_amount' => 0,
                    'due_date' => $request->due_date,
                    'status' => StudentFee::UNPAID,
                    'remarks' => $request->remarks,
                ]);

                $createdCount++;
            }
        });

        return redirect()->route('student-fees.index')
            ->with('success', "Fee generated successfully. Created: {$createdCount}, Skipped: {$skippedCount}");
    }

    public function printSlip(StudentFee $studentFee)
    {
        $studentFee->load([
            'student.studentClass',
            'student.section',
            'feeType',
        ]);

        return view('student-fees.print-slip', compact('studentFee'));
    }
}
