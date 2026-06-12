<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('leaves.view'), 403);
        return view('leaves.index');
    }

    public function types()
    {
        abort_unless(Auth::user()?->can('leaves.manage'), 403);
        return view('leaves.types');
    }
}
