<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('reports.view'), 403);
        return view('reports.index');
    }

    public function students()
    {
        abort_unless(Auth::user()?->can('reports.view'), 403);
        return view('reports.students');
    }

    public function attendance()
    {
        abort_unless(Auth::user()?->can('reports.view'), 403);
        return view('reports.attendance');
    }

    public function fees()
    {
        abort_unless(Auth::user()?->can('reports.view'), 403);
        return view('reports.fees');
    }
}
