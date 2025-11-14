<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CampusConnect') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow sticky top-0 z-40 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-lg font-semibold flex items-center space-x-2">
                            <span class="text-indigo-600 font-bold">{{ config('app.name', 'CampusConnect') }}</span>
                            <span class="text-sm text-gray-500">— Gestion campus</span>
                        </a>
                    </div>
                    <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-indigo-500">Salles</a>
                        <a href="{{ route('equipments.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-indigo-500">Matériels</a>
                        <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-indigo-500">Réservations</a>
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-indigo-500">Projets</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('calendar.index') }}" class="text-sm px-2 py-1 rounded hover:bg-indigo-50 transition">Calendrier</a>
                        <span class="mr-2 text-sm">Bonjour, <span class="font-medium">{{ auth()->user()->name }}</span></span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:underline">Se déconnecter</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm mr-4">Se connecter</a>
                        <a href="{{ route('register') }}" class="text-sm">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        <div class="animate-fade-in">
            {{-- Flash toast container (bottom-right) --}}
            <div id="toast-container" class="fixed z-50 bottom-6 right-6 space-y-2">
                @if(session('success'))
                    <div class="toast p-3 bg-green-50 text-green-800 rounded shadow-sm border border-green-100 animate-fade-in">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="toast p-3 bg-yellow-50 text-yellow-800 rounded shadow-sm border border-yellow-100 animate-fade-in">
                        {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast p-3 bg-red-50 text-red-800 rounded shadow-sm border border-red-100 animate-fade-in">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-800 rounded shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        // Auto-hide toasts after 4s
        (function(){
            const toasts = document.querySelectorAll('#toast-container .toast');
            toasts.forEach((t) => {
                setTimeout(() => {
                    t.style.transition = 'transform 320ms ease, opacity 320ms ease';
                    t.style.transform = 'translateY(10px)';
                    t.style.opacity = '0';
                    setTimeout(() => t.remove(), 350);
                }, 4000);
            });
        })();
    </script>
</body>
</html>

