<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Initialize dark mode from localStorage
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-full transition-colors duration-200">
    <aside class="w-64 bg-gray-900 dark:bg-gray-800 text-white h-screen fixed left-0 top-0 overflow-y-auto z-20 transition-colors duration-200">
        @include('guest.partials.sidebar')
    </aside>
    <div class="flex-1 flex flex-col relative ml-64">
        <!-- Navbar -->
        <header class="bg-white dark:bg-gray-800 shadow fixed top-0 right-0 left-64 z-10 transition-colors duration-200">
            @include('guest.partials.nav')
        </header>
         <!-- Page Content -->
          <main class="flex-1 p-6 mt-12">
             <!-- Flash Messages -->
             @if(session('success'))
                 <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                     <span class="block sm:inline">{{ session('success') }}</span>
                 </div>
             @endif

             @if(session('error'))
                 <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                     <span class="block sm:inline">{{ session('error') }}</span>
                 </div>
             @endif

             @if($errors->any())
                 <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                     <ul class="list-disc list-inside">
                         @foreach($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
             @endif

             @yield('content')
         </main>
    </div>
    
    <script>
        // Dark mode toggle functionality
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
        
        // Profile dropdown toggle functionality
        function toggleProfileDropdown() {
            const menu = document.getElementById('profile-menu');
            const icon = document.getElementById('dropdown-icon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden', 'opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                icon.style.transform = 'rotate(180deg)';
            } else {
                menu.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
                icon.style.transform = 'rotate(0deg)';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const menu = document.getElementById('profile-menu');
            const icon = document.getElementById('dropdown-icon');
            
            if (dropdown && !dropdown.contains(event.target) && !menu.classList.contains('hidden')) {
                menu.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
                icon.style.transform = 'rotate(0deg)';
            }
        });
        
        // Make functions globally available
        window.toggleDarkMode = toggleDarkMode;
        window.toggleProfileDropdown = toggleProfileDropdown;
    </script>
</body>
</html>