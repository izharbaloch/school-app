<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentFee::with(['student.studentClass', 'student.section', 'feeType'])->latest();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $studentFees = $query->paginate(10)->withQueryString();
        $students = Student::orderBy('first_name')->get();

        return view('student-fees.index', compact('studentFees', 'students'));
    }

    public function create()
    {
        $students = Student::with(['studentClass'])->orderBy('first_name')->get();
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
        $studentFee->load(['student.studentClass', 'student.section', 'feeType', 'payments.receivedBy']);

        return view('student-fees.show', compact('studentFee'));
    }

    public function createFromStructure(Student $student)
    {
        $student->load('studentClass');

        $structures = FeeStructure::with('feeType')
            ->where('student_class_id', $student->student_class_id)
            ->where('status', true)
            ->get();

        return view('student-fees.create-from-structure', compact('student', 'structures'));
    }

    public function storeFromStructure(Request $request, Student $student)
    {
        $request->validate([
            'fees' => ['required', 'array', 'min:1'],
            'fees.*.fee_type_id' => ['required', 'exists:fee_types,id'],
            'fees.*.amount' => ['required', 'numeric', 'min:0'],
            'fees.*.month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'fees.*.year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'fees.*.due_date' => ['nullable', 'date'],
        ]);

        foreach ($request->fees as $fee) {
            StudentFee::create([
                'student_id' => $student->id,
                'fee_type_id' => $fee['fee_type_id'],
                'month' => $fee['month'] ?? null,
                'year' => $fee['year'] ?? null,
                'amount' => $fee['amount'],
                'discount' => 0,
                'fine' => 0,
                'paid_amount' => 0,
                'due_date' => $fee['due_date'] ?? null,
                'status' => StudentFee::UNPAID,
            ]);
        }

        return redirect()->route('student-fees.index')->with('success', 'Fees assigned from structure successfully.');
    }
}
