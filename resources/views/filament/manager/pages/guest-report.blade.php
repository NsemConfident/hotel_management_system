<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Loyalty Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Loyalty Tier Distribution</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Platinum</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['loyaltyDistribution']['platinum'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">10,000+ points</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900 dark:to-yellow-800 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Gold</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['loyaltyDistribution']['gold'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">5,000 - 9,999 points</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Silver</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['loyaltyDistribution']['silver'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">1,000 - 4,999 points</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900 dark:to-orange-800 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Bronze</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['loyaltyDistribution']['bronze'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Under 1,000 points</p>
                </div>
            </div>
        </div>

        <!-- Top Guests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Top Guests by Loyalty Points</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Guest</th>
                            <th class="text-left p-2">Email</th>
                            <th class="text-center p-2">Tier</th>
                            <th class="text-right p-2">Points</th>
                            <th class="text-right p-2">Bookings</th>
                            <th class="text-right p-2">Total Spent</th>
                            <th class="text-right p-2">Nights</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['topGuests'] ?? [] as $guest)
                        <tr class="border-b">
                            <td class="p-2 font-medium">{{ $guest['name'] }}</td>
                            <td class="p-2 text-sm text-gray-600 dark:text-gray-400">{{ $guest['email'] }}</td>
                            <td class="p-2 text-center">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($guest['tier'] === 'Platinum') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                    @elseif($guest['tier'] === 'Gold') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @elseif($guest['tier'] === 'Silver') bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                                    @else bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                                    @endif">
                                    {{ $guest['tier'] }}
                                </span>
                            </td>
                            <td class="text-right p-2 font-semibold">{{ number_format($guest['loyalty_points']) }}</td>
                            <td class="text-right p-2">{{ $guest['bookings'] }}</td>
                            <td class="text-right p-2 font-semibold">${{ number_format($guest['total_spent'], 2) }}</td>
                            <td class="text-right p-2">{{ $guest['nights'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- VIP Guests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">VIP Guests</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Guest</th>
                            <th class="text-left p-2">Email</th>
                            <th class="text-right p-2">Loyalty Points</th>
                            <th class="text-right p-2">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['vipGuests'] ?? [] as $guest)
                        <tr class="border-b">
                            <td class="p-2 font-medium">{{ $guest['name'] }}</td>
                            <td class="p-2 text-sm text-gray-600 dark:text-gray-400">{{ $guest['email'] }}</td>
                            <td class="text-right p-2 font-semibold">{{ number_format($guest['loyalty_points']) }}</td>
                            <td class="text-right p-2 font-semibold">${{ number_format($guest['total_spent'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>

