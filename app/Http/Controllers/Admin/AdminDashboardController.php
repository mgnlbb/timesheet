<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Support\Carbon;



class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTimesheets = Timesheet::count();
        $monthlyTimesheets = Timesheet::whereMonth('date', Carbon::now()->month)
                                       ->whereYear('date', Carbon::now()->year)
                                       ->count();
    
        // User aktif per bulan (misalnya 6 bulan terakhir)
        $activeUsersChart = [
            'labels' => [],
            'data' => [],
        ];
    
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $label = $month->format('M Y');
            $count = Timesheet::whereMonth('date', $month->month)
                              ->whereYear('date', $month->year)
                              ->distinct('user_id')
                              ->count('user_id');
    
            $activeUsersChart['labels'][] = $label;
            $activeUsersChart['data'][] = $count;
        }
    
        // Aktivitas terbaru (misalnya 10 terbaru)
        $recentActivities = Timesheet::with('user')
                                ->orderByDesc('date')
                                ->orderByDesc('start_time')
                                ->limit(10)
                                ->get();
    
        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalTimesheets',
            'monthlyTimesheets',
            'activeUsersChart',
            'recentActivities'
        ));
    }
}
