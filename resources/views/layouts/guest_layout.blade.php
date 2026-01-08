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
    <aside class="w-64 bg-gray-900 text-white h-screen fixed left-0 top-0 overflow-y-auto">
        @include('guest.partials.sidebar')
    </aside>
    <div class="flex-1 flex flex-col relative">
        <!-- Navbar -->
        <header class="bg-white shadow top-0 fixed right-0 left-64 z-10">
            @include('guest.partials.nav')
        </header>
         <!-- Page Content -->
          <main class="flex-1 p-6 ml-64 mt-12">
             <!-- Flash Messages -->
             @if(session('success'))
                 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                     <span class="block sm:inline">{{ session('success') }}</span>
                 </div>
             @endif

             @if(session('error'))
                 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                     <span class="block sm:inline">{{ session('error') }}</span>
                 </div>
             @endif

             @if($errors->any())
                 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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
</body>
</html>