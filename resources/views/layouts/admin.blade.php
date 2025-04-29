<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex min-h-screen text-gray-900 dark:text-gray-100">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-md dark:shadow-lg">
        <div class="p-4 text-xl font-bold text-center border-b dark:border-gray-700">
            Admin Panel
        </div>
        <nav class="mt-4">
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">👥 User Management</a>
            <a href="{{ route('admin.timesheets.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">🗓️ Timesheets</a>
            <hr class="border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">🔙 Kembali ke Dashboard</a>
        </nav>
    </aside>

    {{-- Content --}}
    <div class="flex-1 flex flex-col">
        {{-- Header --}}
        <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center border-b dark:border-gray-700">
            <h2 class="text-xl font-semibold">Admin Dashboard</h2>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-700 dark:text-gray-200">{{ Auth::user()->email }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
                </form>
            </div>

            
        </header>

        {{-- Main Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
