<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <aside class="w-64 bg-gray-900 text-white h-screen fixed left-0 top-0">
        @include('partials.sidebar')
    </aside>
    <div class="flex-1 flex flex-col relative">
        <!-- Navbar -->
        <header class="bg-white shadow top-0">
            @include('partials.nav')
        </header>
         <!-- Page Content -->
         <main class="flex-1 p-6 ml-64">
            @yield('content')
        </main>
    </div>
</body>
</html>