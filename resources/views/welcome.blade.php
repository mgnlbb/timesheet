<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabSheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <style>
        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        .float {
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 0 15px 4px rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 1rem;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 to-indigo-300 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center text-gray-800 dark:text-white transition-all duration-500">
    <div class="bg-white dark:bg-gray-900 p-10 rounded-3xl shadow-2xl max-w-md w-full text-center fade-in">
        <div class="mb-5">
            <img src="{{ asset('images/Schedule Icon.jpeg') }}" alt="Timesheet Icon" class="mx-auto w-24 h-24  drop-shadow-lg">
        </div>

        <h1 class="text-4xl font-bold mb-3">LabSheet</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Sistem pencatatan Timesheet ✨</p>

        @auth
            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                🚀 Masuk Dashboard
            </a>
        @else
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-5 py-2 rounded float hover:bg-indigo-700 transition">
                    🔐 Login
                </a>
                <a href="{{ route('register') }}" class="bg-white dark:bg-gray-800 border float border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    ✍️ Register
                </a>
            </div>
        @endauth
    </div>
</body>
</html>
