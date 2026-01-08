<div class="flex items-center justify-between px-6 py-4">
    <h1 class="text-lg font-semibold dark:text-white">@yield('title', 'Dashboard')</h1>

    <div class="flex items-center gap-4">
        @php
            $guest = \App\Models\Guest::find(session('guest_id'));
        @endphp
        @if($guest)
            <span class="text-sm text-gray-600 dark:text-gray-300">
                {{ $guest->full_name }}
            </span>
        @elseif(session('guest_name'))
            <span class="text-sm text-gray-600 dark:text-gray-300">
                {{ session('guest_name') }}
            </span>
        @endif

        <!-- Dark Mode Toggle -->
        <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Toggle Dark Mode">
            <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <form method="POST" action="{{ route('guest.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
