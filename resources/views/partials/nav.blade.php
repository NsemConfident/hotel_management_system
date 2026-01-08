<div class="flex items-center justify-between px-6 py-4">
    <h1 class="text-lg font-semibold">Dashboard</h1>

    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            {{ auth()->user()->name ?? 'Admin' }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-red-600 hover:underline">
                Logout
            </button>
        </form>
    </div>
</div>
