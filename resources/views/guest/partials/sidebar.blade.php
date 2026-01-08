<div class="p-6" style="pointer-events: auto;">
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-2">Guest Portal</h2>
        @php
            $guest = \App\Models\Guest::find(session('guest_id'));
        @endphp
        @if($guest)
            <p class="text-sm text-gray-400">{{ $guest->full_name }}</p>
        @endif
    </div>

    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('guest.dashboard') }}"
           class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer {{ request()->routeIs('guest.dashboard') ? 'bg-gray-800 dark:bg-gray-700' : '' }}"
           style="pointer-events: auto;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- My Bookings -->
        <a href="{{ route('guest.bookings.index') }}"
           class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer {{ request()->routeIs('guest.bookings.*') && !request()->routeIs('guest.bookings.create') ? 'bg-gray-800 dark:bg-gray-700' : '' }}"
           style="pointer-events: auto;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            My Bookings
        </a>

        <!-- New Booking -->
        <a href="{{ route('guest.bookings.create') }}"
           class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer {{ request()->routeIs('guest.bookings.create') ? 'bg-gray-800 dark:bg-gray-700' : '' }}"
           style="pointer-events: auto;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Booking
        </a>

        <!-- My Profile -->
        <a href="{{ route('guest.profile') }}"
           class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer {{ request()->routeIs('guest.profile') || request()->routeIs('guest.profile.*') ? 'bg-gray-800 dark:bg-gray-700' : '' }}"
           style="pointer-events: auto;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            My Profile
        </a>

        @if($guest)
        <!-- Loyalty Program Section -->
        <div class="pt-4 border-t border-gray-700 mt-4">
            <div class="px-4 py-3 mb-2">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Loyalty Program</p>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-300">Points</span>
                    <span class="text-lg font-bold text-white">{{ number_format($guest->loyalty_points) }}</span>
                </div>
                <div class="mt-2">
                    <span class="text-xs px-2 py-1 rounded-full 
                        @if($guest->loyalty_tier === 'Platinum') bg-gray-700 text-white
                        @elseif($guest->loyalty_tier === 'Gold') bg-yellow-600 text-white
                        @elseif($guest->loyalty_tier === 'Silver') bg-gray-600 text-white
                        @else bg-orange-600 text-white
                        @endif">
                        {{ $guest->loyalty_tier }} Tier
                    </span>
                    @if($guest->is_vip)
                        <span class="ml-2 text-xs px-2 py-1 rounded-full bg-yellow-600 text-white">
                            VIP
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Links Section -->
        <div class="pt-4 border-t border-gray-700">
            <p class="text-xs text-gray-400 uppercase tracking-wider px-4 py-2 mb-2">Quick Links</p>
            
            <a href="{{ route('guest.profile') }}#loyalty"
               class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer"
               style="pointer-events: auto;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Loyalty Details
            </a>
        </div>
    </nav>
</div>
