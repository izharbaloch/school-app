<?php

namespace App\Http\Controllers;

class HomeworkController extends Controller
{
    public function index()
    {
        return view('homework.index');
    }
}
