@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold p-8">📝 Semua Timesheet User</h1>
<div class="max-w-6xl mx-auto dark:bg-gray-800 rounded-2xl shadow p-6">

    <div class="overflow-x-auto">

        {{-- Filter Form --}}
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Pilih User</label>
                <select name="user_id"
                    class="border rounded px-3 py-2 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">-- Semua User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Pilih Bulan</label>
                <input type="month" name="month"
                    class="border rounded px-3 py-2 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    value="{{ request('month') }}">
            </div>
        
            <div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 mt-1">
                    Filter
                </button>
            </div>
        </form>
        
        <div class="overflow-x-auto border shadow-md sm:rounded-lg max-h-[400px] w-full">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Nama User</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Project</th>
                        <th class="px-4 py-2 text-left">Activity</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timesheets as $index => $ts)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $ts->user->username ?? '-' }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($ts->date)->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $ts->project_name }}</td>
                        <td class="px-4 py-2">{{ $ts->activity }}</td>
                        <td class="px-4 py-2">{{ $ts->start_time }} - {{ $ts->end_time }}</td>
                        <td class="px-4 py-2">{{ $ts->remarks }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center dark:text-gray-500 py-4">Belum ada data timesheet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
