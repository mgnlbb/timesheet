<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $profile = $user->profile;
    
    if (!$profile) {
        return redirect()->route('profile.create');
    }
    
    $year = date('Y');
    // Buat daftar bulan (Januari - Desember 2025)
    $months = collect(range(1, 12))->map(function ($month) use ($year) {
        return [
            'month' => $month,
            'name' => Carbon::createFromDate($year, $month, 1)->isoFormat('MMMM YYYY'),
            'year' => $year, // ditambahkan agar bisa dipakai di blade
        ];
    });

    return view('dashboard', compact('profile', 'months', 'year'));
}
}
