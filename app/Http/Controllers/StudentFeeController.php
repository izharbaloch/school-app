<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;

class StudentFeeController extends Controller
{
    public function index()
    {
        return view('student-fees.index');
    }

    public function create()
    {
        return view('student-fees.create');
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

    public function bulkCreate()
    {
        return view('student-fees.bulk-create');
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
