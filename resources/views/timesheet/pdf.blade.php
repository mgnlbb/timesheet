<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Timesheet Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 10px; font-size: 10px; }
        .tg { border-collapse: collapse; width: 100%; margin-bottom: 2px; }
        .tg th, .tg td { border: 1px solid #333; padding: 4px; } /* default */
        .tg-left { text-align: left; }
        .tg-center { text-align: center; }
        .tg-right { text-align: right; }
        .tg-bold { font-weight: bold; }
        .tg-no-border { border: none !important; }
        .no-bottom-border { border-bottom: none !important; }
        .no-top-border { border-top: none !important; }
        .no-top-bottom-border { border-top: none !important; border-bottom: none !important; }
        .signature-box { height: 60px; vertical-align: bottom; }
        .header { text-align: center; margin-bottom: 20px; }

        /* khusus header dan entry baris ts */
        .tg-header th { padding: 2px !important; }
        .tg-entry td { padding: 2px !important; }
    </style>
</head>
<body>
    <table class="tg">
        <tr>
            <th class="tg-left">Name</th>
            <td class="tg-center" colspan="4">{{ strtoupper($user->profile->full_name ?? $user->user_name) }}</td>
            <th class="tg-left">Role</th>
            <td class="tg-center">{{ strtoupper($user->profile->role ?? '-' )}}</td>
            <td class="tg-left no-bottom-border" colspan="2" rowspan="3">
                <img src="{{ base_path('public/images/idstar.png') }}" width="100" alt="Logo">
            </td>
        </tr>
        <tr>
            <th class="tg-left">Department</th>
            <td class="tg-center" colspan="4">{{ strtoupper($user->profile->department ?? '-') }}</td>
            <th class="tg-left">Location</th>
            <td class="tg-center">{{ strtoupper($user->profile->location ?? '-' )}}</td>
        </tr>
        <tr>
            <th class="tg-left">Project</th>
            <td class="tg-center" colspan="4">{{ strtoupper($user->profile->project ?? '-') }}</td>
            <th class="tg-left">Period</th>
            <td class="tg-center">
                {{ \Carbon\Carbon::parse($start_date)->format('d') }} - {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
            </td>
        </tr>

        {{-- Table Header --}}
        <tr class="tg-header">
            <th>Day</th>
            <th width="100">Date</th>
            <th>Start Time</th>
            <th>Lunch Break</th>
            <th>End Time</th>
            <th>Total Hours</th>
            <th colspan="3">Activity/Remarks</th>
        </tr>

        {{-- Timesheet Entries --}}
        @foreach($timesheets as $timesheet)
        <tr 
            @if(in_array($timesheet['entry']->activity ?? '', ['Holiday', 'Leave', 'Sick'])) 
                style="background-color: #cccccc; font-style: italic;" 
            @endif
            class="tg-entry"
        >
            <td class="tg-center">{{ $timesheet['day_name'] }}</td>
            <td class="tg-center" width="100">{{ \Carbon\Carbon::parse($timesheet['date'])->format('d-F-Y') }}</td>
            <td class="tg-center">
                {{ $timesheet['entry']?->start_time ? \Carbon\Carbon::parse($timesheet['entry']->start_time)->format('H:i') : '-' }}
            </td>
            <td class="tg-center">12:00</td>
            <td class="tg-center">
                {{ $timesheet['entry']?->end_time ? \Carbon\Carbon::parse($timesheet['entry']->end_time)->format('H:i') : '-' }}
            </td>
            <td class="tg-center">
                {{ $timesheet['entry']?->total_hours ?? '-' }}
            </td>
            <td colspan="3" class="tg-center">
                {{ $timesheet['entry']?->remarks ?? '-' }}
            </td>
        </tr>
        @endforeach

        {{-- Evaluation Section --}}
        @php
            $evals = [
                ['Objective of Work:', 'Supporting Competencies:', 'Discipline:', 'Total Working Days:', $totalWorkdays],
                ['(4) Very Satisfactory', '(4) Very Satisfactory', '(4) Very Satisfactory', 'Total Holidays:', $totalHolidays],
                ['(3) Satisfactory', '(3) Satisfactory', '(3) Satisfactory', 'Total Leaves:', $totalLeaves],
                ['(2) Not Satisfactory', '(2) Not Satisfactory', '(2) Not Satisfactory', 'Total Sicks:', $totalSicks],
                ['(1) Very Unsatisfactory', '(1) Very Unsatisfactory', '(1) Very Unsatisfactory', 'Total Absences:', $totalAbsences],
                ['Score:', 'Score:', 'Score:', '', '']
            ];
        @endphp

        @foreach($evals as $row)
        <tr>
            <td class="tg-left">{{ $row[0] }}</td>
            <td class="tg-left" colspan="5">{{ $row[1] }}</td>
            <td class="tg-left">{{ $row[2] }}</td>
            <td class="tg-left">{{ $row[3] }}</td>
            <td class="border border-gray-800 tg-right">{{ $row[4] }}</td>
        </tr>
        @endforeach

        {{-- Signature Section --}}
        <tr>
            <td colspan="3" class="tg-left tg-bold no-bottom-border">Submitted by:</td>
            <td colspan="4" class="tg-left tg-bold no-bottom-border">Acknowledged by Team Leader:</td>
            <td colspan="2" class="tg-left tg-bold no-bottom-border">Approved by:</td>
        </tr>
        <tr>
            <td colspan="3" class="no-top-bottom-border signature-box">
                @if($signaturePath)
                    <img src="{{ base_path('public/images/'. $signaturePath) }}" alt="Signature" width="100"><br>
                
                @else
                    <span style="color: red;">(No signature)</span><br>
                @endif
                {{ $user->name }}
            </td>
            <td colspan="4" class="no-top-bottom-border signature-box"></td>
            <td colspan="2" class="no-top-bottom-border signature-box"></td>
        </tr>
        <tr>
            <td colspan="3" class="tg-bold tg-left no-top-bottom-border"><u>{{ strtoupper($user->profile->full_name ?? $user->user_name)}}</u></td>
            <td colspan="4" class="tg-bold tg-left no-top-bottom-border"><u>{{ strtoupper($user->profile->acknowledger_name ?? '-' )}}</u></td>
            <td colspan="2" class="tg-bold tg-left no-top-bottom-border"><u>{{ strtoupper($user->profile->approver_name ?? '-') }}</u></td>
        </tr>
        <tr>
            <td colspan="3" class="tg-left no-top-border py-1">{{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</td>
            <td colspan="4" class="tg-left no-top-border py-1">{{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</td>
            <td colspan="2" class="tg-left no-top-border py-1">{{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</td>
        </tr>
    </table>
</body>
</html>
