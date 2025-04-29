<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;



use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TimesheetController extends Controller
{

    private function calculateSummary($userId, $year, $month)
    {
        $start = Carbon::createFromDate($year, $month, 1);
        $end = $start->copy()->endOfMonth();
    
        $dates = collect();
        $summary = [
            'totalWorkdays' => 0,
            'totalHolidays' => 0,
            'totalLeaves' => 0,
            'totalSicks' => 0,
            'totalAbsences' => 0,
            'totalHours' => 0,
            'dates' => collect(),
        ];
    
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $existing = Timesheet::where('user_id', $userId)->whereDate('date', $date)->first();
    
            if (!$existing && in_array($date->englishDayOfWeek, ['Saturday', 'Sunday'])) {
                $existing = new Timesheet();
                $existing->user_id = $userId;
                $existing->date = $date->format('Y-m-d');
                $existing->start_time = null;
                $existing->end_time = null;
                $existing->activity = 'Holiday';
                $existing->remarks = 'Hari Libur';
            }
    
            $dates->push([
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('l'),
                'entry' => $existing,
            ]);
    
            // Rekap
            if (!$existing || !$existing->activity) {
                $summary['totalAbsences']++;
            } else {
                switch ($existing->activity) {
                    case 'Workday':
                        $summary['totalWorkdays']++;
    
                        if ($existing->start_time && $existing->end_time) {
                            try {
                                $startTime = Carbon::createFromFormat('H:i', $existing->start_time);
                                $endTime = Carbon::createFromFormat('H:i', $existing->end_time);
    
                                $diffInMinutes = $endTime->diffInMinutes($startTime);
    
                                // Kurangi 60 menit untuk lunch break (jika perlu)
                                $workMinutes = max(0, $diffInMinutes - 60);
    
                                $summary['totalHours'] += round($workMinutes / 60, 2);
                            } catch (\Exception $e) {
                                // Abaikan error parsing waktu
                            }
                        }
                        break;
    
                    case 'Holiday':
                        $summary['totalHolidays']++;
                        break;
                    case 'Leave':
                        $summary['totalLeaves']++;
                        break;
                    case 'Sick':
                        $summary['totalSicks']++;
                        break;
                }
            }
        }
    
        $summary['dates'] = $dates;
        return $summary;
    }
    


    public function showMonthly($year, $month)
    {
        $user = auth()->user();
        $summary = $this->calculateSummary($user->id, $year, $month);
    
        // Buat daftar tanggal dalam bulan
        $start = Carbon::createFromDate($year, $month, 1);
        $end = $start->copy()->endOfMonth();
    
        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $existing = Timesheet::where('user_id', $user->id)->whereDate('date', $date)->first();
    
            // Jika tidak ada data dan harinya Sabtu/Minggu, buat dummy object Timesheet
            if (!$existing && in_array($date->englishDayOfWeek, ['Saturday', 'Sunday'])) {
                $existing = new Timesheet();
                $existing->user_id = $user->id;
                $existing->date = $date->format('d-F-Y');
                $existing->start_time = null;
                $existing->end_time = null;
                $existing->activity = 'Holiday';
                $existing->remarks = 'Hari Libur';
            }
    
            $dates->push([
                'date' => $date->format('d-F-Y'),
                'day_name' => $date->translatedFormat('l'),
                'entry' => $existing,
            ]);
        }
    


    
        return view('timesheet.monthly', [
            'dates' => $dates,
            'month_name' => $start->translatedFormat('F Y'),
            'year' => $year,
            'month' => $month,
            'currentMonth' => str_pad($month, 2, '0', STR_PAD_LEFT), // <-- Tambah ini
            'currentYear' => $year, // <-- Bisa juga dipakai untuk export
            'totalWorkdays' => $summary['totalWorkdays'],
            'totalHolidays' => $summary['totalHolidays'],
            'totalLeaves' => $summary['totalLeaves'],
            'totalSicks' => $summary['totalSicks'],
            'totalAbsences' => $summary['totalAbsences'],
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'remarks' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);
    
        // Validasi tambahan khusus activity "Workday"
        if ($request->activity === 'Workday') {
            if (!$request->start_time || !$request->end_time) {
                return back()->withErrors([
                    'start_time' => 'Start Time wajib diisi untuk aktivitas Workday.',
                    'end_time' => 'End Time wajib diisi untuk aktivitas Workday.'
                ])->withInput();
            }
    
            if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                return back()->withErrors([
                    'end_time' => 'End Time harus lebih besar dari Start Time.'
                ])->withInput();
            }
        }
    
        $totalHours = null;
    
        if ($request->activity === 'Workday' && $request->start_time && $request->end_time) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
            $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
    
            $diffMinutes = $end->diffInMinutes($start) - 60; // kurangi 1 jam istirahat
            if ($diffMinutes < 0) $diffMinutes = 0;
    
            $jam = floor($diffMinutes / 60);
            $menit = $diffMinutes % 60;
    
            $totalHours = sprintf('%02d:%02d', $jam, $menit);
        }
    
        Timesheet::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $request->date],
            [
                'user_id' => auth()->id(),
                'start_time' => $request->start_time ? substr($request->start_time, 0, 5) : null,
                'end_time' => $request->end_time ? substr($request->end_time, 0, 5) : null,
                'activity' => $request->activity,
                'remarks' => $request->remarks,
                'total_hours' => $totalHours,
            ]
        );
    
        return redirect()->back()->with('success', 'Data timesheet disimpan.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }

    public function exportPdf($year, $month)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $summary = $this->calculateSummary($user->id, $year, $month);
    
        // Hitung rentang tanggal awal dan akhir bulan
        $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end_date = $start_date->copy()->endOfMonth();

        // Ambil data timesheet untuk user tersebut
        $dates = collect();
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $entry = Timesheet::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();

            // Jika tidak ada data dan harinya Sabtu/Minggu, isi dummy
            if (!$entry && in_array($date->englishDayOfWeek, ['Saturday', 'Sunday'])) {
                $entry = new Timesheet();
                $entry->date = $date->format('Y-m-d');
                $entry->start_time = null;
                $entry->end_time = null;
                $entry->total_hours = null;
                $entry->activity = 'Holiday';
                $entry->remarks = 'Hari Libur';
            }

            $dates->push([
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('l'),
                'entry' => $entry,
            ]);
        }
            
            

        // Jika tidak ada data
        if ($dates->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk bulan tersebut.');
        }

        // Generate nama bulan seperti "April 2025"
        $monthName = $start_date->isoFormat('MMMM YYYY');

        // Kirim ke view PDF
        $pdf = Pdf::loadView('timesheet.pdf', [
            'user' => $user,
            'profile' => $profile,
            'timesheets' => $dates, 
            'monthName' => $monthName,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'signaturePath' => $profile?->signature_path,
            'totalWorkdays' => $summary['totalWorkdays'],
            'totalHolidays' => $summary['totalHolidays'],
            'totalLeaves' => $summary['totalLeaves'],
            'totalSicks' => $summary['totalSicks'],
            'totalAbsences' => $summary['totalAbsences'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download("timesheet-{$monthName}.pdf");
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx'
        ]);
    
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
    
        $user = auth()->user();
        $successCount = 0;
    
        foreach ($rows as $index => $row) {
            if ($index === 0 || empty($row[5])) continue; // Skip header or missing Date
    
            try {
                $date = \Carbon\Carbon::parse($row[5])->format('Y-m-d');
                $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeekIso; // 6=Sabtu, 7=Minggu
    
                $startTime = $row[6] ? \Carbon\Carbon::parse($row[6])->format('H:i') : null;
                $endTime = $row[7] ? \Carbon\Carbon::parse($row[7])->format('H:i') : null;
                $remarks = $row[8] ?? null;
    
                // Tentukan activity dan remarks default
                if (empty($remarks)) {
                    if ($dayOfWeek >= 6) {
                        $activity = 'Holiday';
                        $remarks = 'Hari Libur';
                        $startTime = null;
                        $endTime = null;
                    } else {
                        $activity = null;
                        $remarks = null;
                    }
                } else {
                    $activity = 'Workday';
                }
    
                // Hitung total jam jika ada jam masuk dan keluar
                $totalHours = null;
                if ($startTime && $endTime) {
                    $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
                    $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);
                    $diff = $end->diffInMinutes($start) - 60;
                    $diff = max(0, $diff);
                    $jam = floor($diff / 60);
                    $menit = $diff % 60;
                    $totalHours = sprintf('%02d:%02d', $jam, $menit);
                }
    
                Timesheet::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'activity' => $activity,
                        'remarks' => $remarks,
                        'total_hours' => $totalHours,
                    ]
                );
    
                $successCount++;
            } catch (\Throwable $e) {
                Log::error("Import error row $index: " . $e->getMessage());
                continue;
            }
        }
    
        return back()->with('success', "$successCount data berhasil diimport.");
    }
    
}
