<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function academicSetupView()
    {
        return view('admin.academic-setup');
    }
    public function accessManagementView()
    {
        return view('admin.access-management');
    }
}
