<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeePaymentController extends Controller
{
    public function create(StudentFee $studentFee)
    {
        $studentFee->load(['student.studentClass', 'student.section', 'feeType']);

        return view('fee-payments.create', compact('studentFee'));
    }

    public function store(Request $request, StudentFee $studentFee)
    {
        $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        if ($request->amount > $studentFee->remaining_amount) {
            return back()->withInput()->with('error', 'Payment amount cannot be greater than remaining amount.');
        }

        DB::transaction(function () use ($request, $studentFee) {
            FeePayment::create([
                'student_fee_id' => $studentFee->id,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_no' => $request->reference_no,
                'remarks' => $request->remarks,
                'received_by' => auth()->id(),
            ]);

            $newPaidAmount = $studentFee->paid_amount + $request->amount;
            $remaining = $studentFee->payable_amount - $newPaidAmount;

            $status = 'unpaid';

            if ($newPaidAmount > 0 && $remaining > 0) {
                $status = 'partial';
            } elseif ($remaining <= 0) {
                $status = 'paid';
            }

            $studentFee->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status,
            ]);
        });

        return redirect()->route('student-fees.show', $studentFee->id)->with('success', 'Fee payment added successfully.');
    }
}
