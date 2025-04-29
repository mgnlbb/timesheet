<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TimesheetsExport;
use Maatwebsite\Excel\Facades\Excel;

class TimesheetExportController extends Controller
{
    public function exportExcel(Request $request)
{
    $month = $request->input('month', now()->format('m')); // default bulan ini
    return Excel::download(new TimesheetsExport($month), 'timesheet_' . $month . '.xlsx');
}
}
