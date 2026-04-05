<?php

namespace App\Http\Controllers;

class ExamController extends Controller
{
    public function index()
    {
        return view('exams.index');
    }
}
