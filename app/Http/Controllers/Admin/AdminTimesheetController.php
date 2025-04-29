<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;

class AdminTimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with('user')->orderBy('date', 'asc');

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan bulan (format: 2025-04)
        if ($request->filled('month')) {
            $query->whereMonth('date', Carbon::parse($request->month)->month)
                ->whereYear('date', Carbon::parse($request->month)->year);
        }

        $timesheets = $query->get();
        $users = User::orderBy('username')->get();

        return view('admin.timesheets.index', compact('timesheets', 'users'));
    }
}
