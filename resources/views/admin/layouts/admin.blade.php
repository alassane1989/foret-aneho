<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - Mairie d'Aného</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 90px;
            --primary-color: #2E7D32;
            --secondary-color: #1B5E20;
            --bg-light: #f8f9fa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        
        .logo-mairie {
            max-width: 60px;
            height: auto;
            margin-bottom: 5px;
            background: white;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .logo-mairie img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .logo-mairie i {
            font-size: 30px;
            color: var(--primary-color);
        }
        
        .logo-container span {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
        
        .sidebar.collapsed .logo-container .logo-mairie {
            max-width: 40px;
            padding: 4px;
        }
        
        .sidebar.collapsed .logo-container span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header {
            padding: 10px 0;
        }
        
        .sidebar-menu {
            padding: 10px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 5px;
        }
        
        /* Section avec menu déroulant */
        .menu-section {
            margin: 5px 0;
        }
        
        .menu-section-header {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            margin: 2px 10px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .menu-section-header:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .menu-section-header .arrow-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .menu-section-header.open .arrow-icon {
            transform: rotate(90deg);
        }
        
        .sidebar.collapsed .menu-section-header span {
            display: none;
        }
        
        .sidebar.collapsed .menu-section-header {
            justify-content: center;
            padding: 12px 0;
        }
        
        .sidebar.collapsed .arrow-icon {
            display: none;
        }
        
        /* Sous-menus */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 0 10px;
        }
        
        .submenu.open {
            max-height: 300px;
            transition: max-height 0.5s ease-in;
        }
        
        .sidebar.collapsed .submenu {
            display: none;
        }
        
        .menu-item {
            padding: 10px 20px 10px 20px;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            white-space: nowrap;
            font-size: 0.9rem;
            border-radius: 8px;
            margin: 2px 5px;
            position: relative;
        }
        
        .menu-item:before {
            content: "•";
            margin-right: 10px;
            opacity: 0.7;
            font-size: 1.2rem;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(3px);
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 500;
        }
        
        /* Menu simple (non-déroulant) */
        .menu-item-simple {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s;
            margin: 2px 10px;
            border-radius: 10px;
            white-space: nowrap;
            font-weight: 500;
        }
        
        .menu-item-simple:before {
            content: "•";
            margin-right: 15px;
            opacity: 0.7;
            font-size: 1.3rem;
        }
        
        .menu-item-simple:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .menu-item-simple.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar.collapsed .menu-item-simple span {
            display: none;
        }
        
        .sidebar.collapsed .menu-item-simple {
            justify-content: center;
            padding: 12px 0;
        }
        
        .sidebar.collapsed .menu-item-simple:before {
            margin-right: 0;
        }
        
        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 10px;
        }
        
        /* Badge pour les notifications */
        .badge {
            font-size: 0.7rem;
            padding: 3px 6px;
            border-radius: 10px;
            margin-left: auto;
        }
        
        .badge.bg-danger {
            background: #e74c3c !important;
        }
        
        .badge.bg-info {
            background: #3498db !important;
        }
        
        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            background: var(--bg-light);
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Header */
        .top-navbar {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px 0 25px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .toggle-sidebar {
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .toggle-sidebar:hover {
            background: var(--bg-light);
        }
        
        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-left: 10px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: 50px;
            background: var(--bg-light);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-info:hover {
            background: #e9ecef;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .user-details {
            line-height: 1.2;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.7rem;
            color: #6c757d;
        }
        
        /* Footer */
        .admin-footer {
            padding: 15px 20px;
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .user-details {
                display: none;
            }
            
            .user-info {
                padding: 5px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar.collapsed {
                left: -70px;
            }
            
            .sidebar.collapsed.show {
                left: 0;
                width: 260px;
            }
            
            .sidebar.collapsed.show .logo-container span {
                display: block;
            }
            
            .sidebar.collapsed.show .menu-section-header span {
                display: inline;
            }
            
            .sidebar.collapsed.show .menu-section-header {
                justify-content: flex-start;
                padding: 12px 20px;
            }
            
            .sidebar.collapsed.show .arrow-icon {
                display: inline-block;
            }
            
            .sidebar.collapsed.show .menu-item-simple span {
                display: inline;
            }
            
            .sidebar.collapsed.show .menu-item-simple {
                justify-content: flex-start;
                padding: 12px 20px;
            }
            
            .sidebar.collapsed.show .submenu {
                display: block;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .top-navbar {
                padding: 0 15px;
            }
            
            .page-title {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .user-name {
                max-width: 80px;
            }
            
            .page-title {
                display: none;
            }
        }
        
        /* Loading spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner-overlay.show {
            display: flex;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--bg-light);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="loadingSpinner">
        <div class="spinner"></div>
    </div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-mairie">
                    <img src="{{ asset('images/logo-mairie.png') }}" 
                         alt="Mairie d'Aného" 
                         onerror="this.onerror=null; this.src='{{ asset('images/logo(2).png') }}'; this.onerror=function(){this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-city\'></i>';};">
                </div>
                <span><strong>Commune des Lacs 1</strong></span>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <!-- Tableau de bord (menu simple) -->
            <a href="{{ route('admin.dashboard') }}" class="menu-item-simple {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>Tableau de bord</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <!-- SECTION 1 : GESTION DU PATRIMOINE (menu déroulant) -->
            <div class="menu-section">
                <div class="menu-section-header" onclick="toggleSubmenu('patrimoine-submenu', this)">
                    <span>GESTION DU PATRIMOINE</span>
                    <i class="fas fa-chevron-right arrow-icon"></i>
                </div>
                <div class="submenu" id="patrimoine-submenu">
                    <a href="{{ route('admin.arbres.index') }}" class="menu-item {{ request()->routeIs('admin.arbres.*') ? 'active' : '' }}">
                        <span>Arbres</span>
                    </a>
                    
                    <a href="{{ route('admin.zones.index') }}" class="menu-item {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">
                        <span>Zones</span>
                    </a>
                    
                    <a href="{{ route('admin.especes.index') }}" class="menu-item {{ request()->routeIs('admin.especes.*') ? 'active' : '' }}">
                        <span>Espèces</span>
                    </a>
                    
                    <a href="{{ route('admin.actualites.index') }}" class="menu-item {{ request()->routeIs('admin.actualites.*') ? 'active' : '' }}">
                        <span>Actualités</span>
                    </a>
                </div>
            </div>
            
           <!-- SECTION 2 : COMMUNICATION (menu déroulant) -->
            <div class="menu-section">
                <div class="menu-section-header" onclick="toggleSubmenu('communication-submenu', this)">
                    <span>COMMUNICATION</span>
                    <i class="fas fa-chevron-right arrow-icon"></i>
                </div>

                <div class="submenu" id="communication-submenu">
                    <!-- Newsletter -->
                    <a href="{{ route('admin.newsletters.index') }}"
                       class="menu-item {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
                        <span>Newsletter</span>
                    </a>

                    <!-- Messages (avec permission + badge non lus) -->
                    @if(auth()->check() && auth()->user()->aPermission('contacts.voir'))
                        @php
                            try {
                                $unreadCount = \App\Models\Contact::where('lu', false)->count();
                            } catch (\Exception $e) {
                                $unreadCount = 0;
                            }
                        @endphp

                        <a href="{{ route('admin.contacts.index') }}"
                           class="menu-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <span>Messages</span>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-auto">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- SECTION 3 : ADMINISTRATION (menu déroulant) - visible seulement pour super_admin et admin -->
            @if(auth()->check() && (auth()->user()->is_super_admin || auth()->user()->is_admin))
                <div class="menu-section">
                    <div class="menu-section-header" onclick="toggleSubmenu('administration-submenu', this)">
                        <span>ADMINISTRATION</span>
                        <i class="fas fa-chevron-right arrow-icon"></i>
                    </div>
                    <div class="submenu" id="administration-submenu">
                        <a href="{{ route('admin.roles.index') }}" class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <span>Gestion des rôles</span>
                        </a>
                        
                        @if(auth()->user()->is_super_admin || auth()->user()->is_admin)
                            <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span>Utilisateurs</span>
                                @php
                                    $usersCount = \App\Models\User::count();
                                @endphp
                                @if($usersCount > 0)
                                    <span class="badge bg-info ms-auto">{{ $usersCount }}</span>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- SECTION 4 : MON ESPACE (menu déroulant) -->
            <div class="menu-section">
                <div class="menu-section-header" onclick="toggleSubmenu('espace-submenu', this)">
                    <span>MON ESPACE</span>
                    <i class="fas fa-chevron-right arrow-icon"></i>
                </div>
                <div class="submenu" id="espace-submenu">
                    <a href="{{ route('admin.profile') }}" class="menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <span>Mon profil</span>
                    </a>
                    
                    <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <span>Paramètres</span>
                    </a>
                </div>
            </div>
            
            <div class="menu-divider"></div>
            
            <!-- Lien vers le site -->
            <a href="{{ route('home') }}" target="_blank" class="menu-item-simple" style="background: rgba(0,0,0,0.2);">
                <span>Voir le site</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <div class="toggle-sidebar" onclick="toggleSidebar()">
                    <i class="fas fa-bars fa-lg"></i>
                </div>
                <span class="page-title">@yield('page-title', 'Tableau de bord')</span>
            </div>
            
            <div class="user-menu">
                <div class="dropdown">
                    <div class="user-info" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'A' }}
                        </div>
                        <div class="user-details">
                            <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Invité' }}</div>
                            <div class="user-role">
                                @if(auth()->check())
                                    @if(auth()->user()->is_super_admin)
                                        Super Admin
                                    @elseif(auth()->user()->is_admin)
                                        Admin
                                    @else
                                        Utilisateur
                                    @endif
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="fas fa-user me-2"></i>Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div style="padding: 20px;">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="admin-footer">
            <p class="mb-0">
                &copy; {{ date('Y') }} Mairie d'Aného - Tous droits réservés
            </p>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Sur mobile, on veut garder le menu visible même quand collapsed
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            }
            
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        // Restore sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
            document.getElementById('sidebar').classList.add('collapsed');
        }
        
        // Toggle submenu
        function toggleSubmenu(id, element) {
            const submenu = document.getElementById(id);
            submenu.classList.toggle('open');
            element.classList.toggle('open');
            
            // Sauvegarder l'état du sous-menu
            const openSubmenus = JSON.parse(localStorage.getItem('openSubmenus') || '{}');
            openSubmenus[id] = submenu.classList.contains('open');
            localStorage.setItem('openSubmenus', JSON.stringify(openSubmenus));
        }
        
        // Restaurer l'état des sous-menus
        document.addEventListener('DOMContentLoaded', function() {
            const openSubmenus = JSON.parse(localStorage.getItem('openSubmenus') || '{}');
            
            // Par défaut, ouvrir le premier sous-menu (GESTION DU PATRIMOINE) si aucun n'est sauvegardé
            if (Object.keys(openSubmenus).length === 0) {
                const patrimoineSubmenu = document.getElementById('patrimoine-submenu');
                const patrimoineHeader = document.querySelector('[onclick="toggleSubmenu(\'patrimoine-submenu\', this)"]');
                if (patrimoineSubmenu && patrimoineHeader) {
                    patrimoineSubmenu.classList.add('open');
                    patrimoineHeader.classList.add('open');
                }
            } else {
                for (const [id, isOpen] of Object.entries(openSubmenus)) {
                    const submenu = document.getElementById(id);
                    const header = document.querySelector(`[onclick="toggleSubmenu('${id}', this)"]`);
                    if (submenu && header && isOpen) {
                        submenu.classList.add('open');
                        header.classList.add('open');
                    }
                }
            }
        });
        
        // Gestion du menu responsive
        function handleResponsive() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('collapsed');
                document.getElementById('mainContent').classList.remove('expanded');
            } else {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                }
            }
        }
        
        window.addEventListener('resize', handleResponsive);
        handleResponsive();
        
        // Fermer le menu mobile quand on clique sur un lien
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
        
        // Loading spinner
        function showLoading() {
            document.getElementById('loadingSpinner').classList.add('show');
        }
        
        function hideLoading() {
            document.getElementById('loadingSpinner').classList.remove('show');
        }
        
        // Confirmation de suppression
        function confirmDelete(event, message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }
        
        // Notifications toast
        function showToast(message, type = 'success') {
            alert(message);
        }
        
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>