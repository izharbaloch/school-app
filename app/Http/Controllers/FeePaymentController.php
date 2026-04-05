<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;

class FeePaymentController extends Controller
{
    public function create(StudentFee $studentFee)
    {
        return view('fee-payments.create', compact('studentFee'));
    }
}
