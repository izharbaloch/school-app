<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with(['studentClass', 'feeType'])
            ->latest()
            ->paginate(10);

        return view('fee-structures.index', compact('feeStructures'));
    }

    public function create()
    {
        $classes = StudentClass::orderBy('name')->get();
        $feeTypes = FeeType::where('status', true)->orderBy('name')->get();

        return view('fee-structures.create', compact('classes', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $exists = FeeStructure::where('student_class_id', $request->student_class_id)
            ->where('fee_type_id', $request->fee_type_id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Fee structure already exists for this class and fee type.');
        }

        FeeStructure::create([
            'student_class_id' => $request->student_class_id,
            'fee_type_id' => $request->fee_type_id,
            'amount' => $request->amount,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $classes = StudentClass::orderBy('name')->get();
        $feeTypes = FeeType::where('status', true)->orderBy('name')->get();

        return view('fee-structures.edit', compact('feeStructure', 'classes', 'feeTypes'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'student_class_id' => ['required', 'exists:student_classes,id'],
            'fee_type_id' => ['required', 'exists:fee_types,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $exists = FeeStructure::where('student_class_id', $request->student_class_id)
            ->where('fee_type_id', $request->fee_type_id)
            ->where('id', '!=', $feeStructure->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Fee structure already exists for this class and fee type.');
        }

        $feeStructure->update([
            'student_class_id' => $request->student_class_id,
            'fee_type_id' => $request->fee_type_id,
            'amount' => $request->amount,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure deleted successfully.');
    }
}
