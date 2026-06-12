<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('medical.view'), 403);
        return view('medical.index');
    }
}
