<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Date Range Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Start Date</label>
                    <input type="date" wire:model.live="startDate" wire:change="generateReport" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">End Date</label>
                    <input type="date" wire:model.live="endDate" wire:change="generateReport" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                </div>
                <button wire:click="generateReport" type="button" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Generate Report
                </button>
            </div>
        </div>

        <!-- Overall Occupancy -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Rooms</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['overall']['total_rooms'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Occupied Days</h3>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($reportData['overall']['occupied_days'] ?? 0) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Room Days</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($reportData['overall']['total_room_days'] ?? 0) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Occupancy Rate</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($reportData['overall']['occupancy_rate'] ?? 0, 1) }}%</p>
            </div>
        </div>

        <!-- Occupancy by Room Type -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Occupancy by Room Type</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Room Type</th>
                            <th class="text-right p-2">Rooms</th>
                            <th class="text-right p-2">Total Room Days</th>
                            <th class="text-right p-2">Occupied Days</th>
                            <th class="text-right p-2">Occupancy Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['byRoomType'] ?? [] as $item)
                        <tr class="border-b">
                            <td class="p-2 font-medium">{{ $item['room_type'] }}</td>
                            <td class="text-right p-2">{{ $item['room_count'] }}</td>
                            <td class="text-right p-2">{{ number_format($item['total_room_days']) }}</td>
                            <td class="text-right p-2">{{ number_format($item['occupied_days']) }}</td>
                            <td class="text-right p-2 font-semibold">{{ number_format($item['occupancy_rate'], 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Room Utilization -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Most Utilized Rooms</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Room Number</th>
                            <th class="text-left p-2">Room Type</th>
                            <th class="text-right p-2">Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['roomUtilization'] ?? [] as $room)
                        <tr class="border-b">
                            <td class="p-2 font-medium">{{ $room['room_number'] }}</td>
                            <td class="p-2">{{ $room['room_type'] }}</td>
                            <td class="text-right p-2 font-semibold">{{ $room['bookings'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>

