<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->can('hostel.view'), 403);
        return view('hostel.index');
    }
}
