<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com/3.4.17"></script>

    <style>
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #f5576c 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea, #f5576c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.12);
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
    </style>
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="px-8 py-3 bg-gradient-to-r bg-white text-white transition duration-300 px-6 py-5 flex justify-between items-center shadow-lg">
    <div class="flex items-center gap-3">
        <div class="text-2xl">📜</div>
        <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600">CommunityLetters</div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('home') }}"
           class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:shadow-xl transition duration-300 transform hover:scale-105">
            ← Back Home
        </a>
    </div>
</nav>

<!-- CONTENT -->
<main class="max-w-6xl mx-auto px-6 py-12">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="text-center py-10 text-gray-500 text-sm">
    © {{ now()->year }} Community Letter Management System
</footer>

</body>
</html>