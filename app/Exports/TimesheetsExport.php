<?php

namespace App\Exports;

use App\Models\Timesheet;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class TimesheetsExport implements FromCollection
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return Timesheet::where('user_id', Auth::id())
            ->whereMonth('date', $this->month)
            ->get();
    }
}
