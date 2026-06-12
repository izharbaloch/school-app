<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ConductController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('conduct.view'), 403);
        return view('conduct.index');
    }
}
