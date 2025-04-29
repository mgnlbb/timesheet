@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mt-8 mb-8 ml-2">Dashboard</h1>
<div class="max-w-7xl mx-auto">

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-gray-700 dark:text-gray-300 text-sm mb-1">Total User</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-gray-700 dark:text-gray-300 text-sm mb-1">Total Timesheet</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTimesheets }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-gray-700 dark:text-gray-300 text-sm mb-1">Timesheet Bulan Ini</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $monthlyTimesheets }}</p>
        </div>
    </div>

    {{-- Grafik User Aktif --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">User Aktif per Bulan</h3>
        <canvas id="userActivityChart" class="w-full h-32"></canvas>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Aktivitas Terbaru</h3>
        <table class="min-w-full text-sm">
            <thead class="text-gray-600 dark:text-gray-300 border-b dark:border-gray-600">
                <tr>
                    <th class="text-left py-2">Tanggal</th>
                    <th class="text-left py-2">User</th>
                    <th class="text-left py-2">Aktivitas</th>
                    <th class="text-left py-2">Jam</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivities as $activity)
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-2">{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d M Y') }}</td>
                        <td class="py-2">{{ $activity->user->username }}</td>
                        <td class="py-2">{{ $activity->activity }}</td>
                        <td class="py-2">{{ $activity->start_time }} - {{ $activity->end_time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">Belum ada aktivitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($activeUsersChart['labels']) !!},
            datasets: [{
                label: 'User Aktif',
                data: {!! json_encode($activeUsersChart['data']) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                x: {
                    ticks: {
                        color: '#9CA3AF'
                    }
                }
            }
        }
    });
</script>
@endpush
