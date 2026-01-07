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

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">${{ number_format($reportData['summary']['total_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">${{ number_format($reportData['summary']['total_paid'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding</h3>
                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">${{ number_format($reportData['summary']['outstanding'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportData['summary']['total_bookings'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Booking</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">${{ number_format($reportData['summary']['average_booking'] ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Revenue by Room Type -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Revenue by Room Type</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Room Type</th>
                            <th class="text-right p-2">Bookings</th>
                            <th class="text-right p-2">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['byRoomType'] ?? [] as $item)
                        <tr class="border-b">
                            <td class="p-2">{{ $item['room_type'] }}</td>
                            <td class="text-right p-2">{{ $item['bookings_count'] }}</td>
                            <td class="text-right p-2 font-semibold">${{ number_format($item['revenue'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Payment Methods</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Method</th>
                            <th class="text-right p-2">Count</th>
                            <th class="text-right p-2">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['paymentMethods'] ?? [] as $item)
                        <tr class="border-b">
                            <td class="p-2 capitalize">{{ $item->method }}</td>
                            <td class="text-right p-2">{{ $item->count }}</td>
                            <td class="text-right p-2 font-semibold">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>

