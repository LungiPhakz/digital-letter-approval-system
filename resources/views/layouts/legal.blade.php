<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Information' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* YOUR BRAND COLORS */
        .brand-gradient {
            background: linear-gradient(135deg, #ea669dff 0%, #764ba2 100%);
        }

        .brand-accent {
            background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%);
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .paragraph {
            color: #4b5563;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .badge {
            display: inline-block;
            background: #ede9fe;
            color: #5b21b6;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .top-bar {
            background: #111827;
        }

        .footer {
            background: #111827;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- 🔝 TOP NAV (MATCH FOOTER STYLE) -->
    <div class="top-bar text-white px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600">
            📜 CommunityLetters
        </div>

        <div class="flex gap-3">
            <a href="{{ url('/') }}"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm transition">
               ← Home
            </a>

            <a href="#footer"
               class="px-4 py-2 brand-gradient rounded-lg text-sm font-semibold">
               Contact
            </a>
        </div>
    </div>

    <!-- HEADER -->
    <div class="w-full py-16 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center">
        <h1 class="text-4xl font-bold">{{ $title }}</h1>
        <p class="opacity-90 mt-2">Community Letter Management System</p>
    </div>

    <!-- CONTENT -->
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="card">
            @yield('content')
        </div>
    </div>

    <!-- 🔻 FOOTER (SAME AS YOUR SITE STYLE) -->
    <footer id="footer" class="footer text-gray-300 py-10 text-center">
        <p class="text-sm">
            © {{ now()->year }} CommunityLetters. All rights reserved.
        </p>

        <div class="mt-4">
            <a href="{{ url('/') }}"
               class="text-purple-400 hover:text-white transition">
               Back to Home
            </a>
        </div>
    </footer>

</body>
</html>