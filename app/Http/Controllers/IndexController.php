<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Status;

class IndexController extends Controller
{
    public function index()
    {
        $teamMembers = Employee::with(['status', 'department'])
            ->select(sprintf('%s.*', (new Employee)->getTable()))
            ->get();

        return view('index', compact('teamMembers'));
    }
}
