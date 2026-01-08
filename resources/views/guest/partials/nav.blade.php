<div class="flex items-center justify-between px-6 py-4">
    <h1 class="text-lg font-semibold">@yield('title', 'Dashboard')</h1>

    <div class="flex items-center gap-4">
        @php
            $guest = \App\Models\Guest::find(session('guest_id'));
        @endphp
        @if($guest)
            <span class="text-sm text-gray-600">
                {{ $guest->full_name }}
            </span>
        @elseif(session('guest_name'))
            <span class="text-sm text-gray-600">
                {{ session('guest_name') }}
            </span>
        @endif

        <form method="POST" action="{{ route('guest.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
