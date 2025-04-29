<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-2xl shadow-lg space-y-6 fade-in">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                {{-- <div class="w-14 h-14 rounded-full bg-blue-700 text-white flex items-center justify-center text-xl font-bold shadow-inner">
                    {{ strtoupper(substr($profile->full_name, 0, 1)) }}
                </div> --}}

                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-white ">
                    {{ strtoupper(substr($profile->full_name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Selamat datang, {{ $profile->full_name }} 👋</h2>
                    <p class="text-sm text-gray-500">Department: {{ $profile->department }}</p>
                </div>
            </div>
        </div>

        {{-- Timesheet Bulan --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">📅 Daftar Bulan Timesheet</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($months as $month)
                    @php
                        $isCurrentMonth = now()->month == $month['month'] && now()->year == $month['year'];
                        $bgColor = $isCurrentMonth ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-400' : 'bg-white hover:bg-gray-50';
                        $icon = match($month['month']) {
                            1 => '❄️', 2 => '💖', 3 => '🌸',
                            4 => '🌦️', 5 => '🌼', 6 => '☀️',
                            7 => '🏖️', 8 => '🌻', 9 => '🍂',
                            10 => '🎃', 11 => '🍁', 12 => '🎄',
                            default => '📅'
                        };
                    @endphp

                    <div class="p-4 border rounded-xl shadow-sm flex items-center justify-between transition-all {{ $bgColor }}">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">{{ $icon }}</div>
                            <span class="font-medium text-gray-800">{{ $month['name'] }}</span>
                        </div>
                        <a href="{{ url('/timesheet/' . $month['year'] . '/' . $month['month']) }}"
                            class="text-blue-600 text-sm font-medium hover:underline">
                            Lihat / Isi →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
