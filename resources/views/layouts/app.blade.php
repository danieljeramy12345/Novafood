<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'NovaFood — Delicious Food, Delivered Fast')</title>
    <meta name="description" content="@yield('meta_description', 'NovaFood is your go-to restaurant food delivery management system in the Philippines. Order burgers, pizza, chicken, and rice meals online.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Brand Palette Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                        accent: {
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
        }
        /* Glassmorphism scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #030712;
        }
        ::-webkit-scrollbar-thumb {
            background: #4c1d95;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-orange-500 selection:text-white">
    <!-- Navbar Component -->
    <x-navbar />

    <!-- Main Content Area -->
    <main class="flex-grow">
        @hasSection('fullwidth')
            @yield('fullwidth')
        @else
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <x-flash-message />
                @yield('content')
            </div>
        @endif
    </main>

    <!-- Footer Component -->
    <x-footer />

    @stack('scripts')
</body>
</html>
