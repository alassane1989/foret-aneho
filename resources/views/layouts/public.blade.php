<!DOCTYPE html>
<html lang="fr" data-theme="foret">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Forêt Urbaine d\'Aného')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind + DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    
    <!-- Alpine.js pour le dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="min-h-screen bg-base-100 transition-colors duration-300">
    <!-- Navigation -->
    <div class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="navbar-start">
                <a href="{{ route('home') }}" class="btn btn-ghost normal-case text-xl p-0">
                    <img src="{{ asset('images/logo(2).png') }}" alt="Forêt d'Aného" class="h-16 w-auto">
                </a>
            </div>
            
            <!-- Menu desktop -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active text-primary' : '' }}">Accueil</a></li>
                    <li><a href="{{ route('arbres.index') }}" class="{{ request()->routeIs('arbres.*') ? 'active text-primary' : '' }}">Arbres</a></li>
                    <li><a href="{{ route('especes.index') }}" class="{{ request()->routeIs('especes.*') ? 'active text-primary' : '' }}">Espèces</a></li>
                    <li><a href="{{ route('actualites.index') }}" class="{{ request()->routeIs('actualites.*') ? 'active text-primary' : '' }}">Actualités</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active text-primary' : '' }}">Contact</a></li>
                </ul>
            </div>
            
            <div class="navbar-end flex items-center gap-2">
                <!-- Sélecteur de thème -->
                <x-theme-selector />
                
                <!-- Menu mobile -->
                <div class="dropdown dropdown-end lg:hidden">
                    <label tabindex="0" class="btn btn-ghost btn-circle">
                        <i class="fas fa-bars text-2xl" style="color: #036f15;"></i>
                    </label>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="{{ route('home') }}">Accueil</a></li>
                        <li><a href="{{ route('arbres.index') }}">Arbres</a></li>
                        <li><a href="{{ route('especes.index') }}">Espèces</a></li>
                        <li><a href="{{ route('actualites.index') }}">Actualités</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu Principal -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-base-100 border-t-4 border-primary mt-12">
        <div class="container mx-auto px-4 py-12">
            <!-- Votre footer existant -->
            <!-- ... -->
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>