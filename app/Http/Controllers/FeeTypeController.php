<?php

namespace App\Http\Controllers;

use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $feeTypes = FeeType::latest()->paginate(10);
        return view('fee-types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('fee-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_monthly' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        FeeType::create([
            'name' => $request->name,
            'is_monthly' => $request->boolean('is_monthly'),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('fee-types.index')->with('success', 'Fee type created successfully.');
    }

    public function edit(FeeType $feeType)
    {
        return view('fee-types.edit', compact('feeType'));
    }

    public function update(Request $request, FeeType $feeType)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_monthly' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        $feeType->update([
            'name' => $request->name,
            'is_monthly' => $request->boolean('is_monthly'),
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('fee-types.index')->with('success', 'Fee type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        $feeType->delete();

        return redirect()->route('fee-types.index')->with('success', 'Fee type deleted successfully.');
    }
}
