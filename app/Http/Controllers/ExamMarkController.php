<?php

namespace App\Http\Controllers;

class ExamMarkController extends Controller
{
    public function create()
    {
        return view('exam-marks.create');
    }
}
