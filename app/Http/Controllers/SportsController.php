<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SportsController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('sports.view'), 403);
        return view('sports.index');
    }
}
