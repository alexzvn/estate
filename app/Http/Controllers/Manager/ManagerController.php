<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        return view('dashboard.app');
    }
}
