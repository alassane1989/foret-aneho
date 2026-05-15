<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Forêt Urbaine d\'Aného')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
    
    <style>
        /* ========== VARIABLES DE THÈME ========== */
        :root {
            --primary-color: #036f15;
            --primary-hover: #024e0f;
            --primary-light: #4caf50;
            --secondary-color: #ff9800;
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --bg-white: #ffffff;
            --bg-black: #000000;
            --text-primary: #212529;
            --text-secondary: #495057;
            --text-muted: #6c757d;
            --text-inverse: #ffffff;
            --text-white: #ffffff;
            --text-black: #000000;
            --border-color: #036f15;
            --border-light: #dee2e6;
            --border-white: #ffffff;
            --card-bg: #ffffff;
            --card-border: #e9ecef;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
            --input-bg: #ffffff;
            --input-border: #ced4da;
            --input-focus: rgba(3,111,21,0.25);
            --table-stripe: #f8f9fa;
            --table-border: #dee2e6;
            --table-header-bg: #f8f9fa;
            --header-bg: #ffffff;
            --footer-bg: #ffffff;
            --footer-text: #495057;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
            --transition-speed: 0.3s;
            --bs-body-bg: #ffffff;
            --bs-body-color: #212529;
            --bs-light: #f8f9fa;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark: #212529;
            --bs-dark-rgb: 33, 37, 41;
            --hero-bg: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            --section-bg: #ffffff;
            --article-bg: #ffffff;
            --sidebar-bg: #f8f9fa;
            --well-bg: #f8f9fa;
        }

        [data-theme="dark"] {
            --primary-color: #4caf50;
            --primary-hover: #2e7d32;
            --primary-light: #81c784;
            --secondary-color: #ffb74d;
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --bg-tertiary: #404040;
            --bg-white: #1a1a1a;
            --bg-black: #ffffff;
            --text-primary: #f0f0f0;
            --text-secondary: #cccccc;
            --text-muted: #b0b0b0;
            --text-inverse: #1a1a1a;
            --text-white: #1a1a1a;
            --text-black: #ffffff;
            --border-color: #4caf50;
            --border-light: #404040;
            --border-white: #404040;
            --card-bg: #2d2d2d;
            --card-border: #404040;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.3);
            --input-bg: #404040;
            --input-border: #666666;
            --input-focus: rgba(76,175,80,0.25);
            --table-stripe: #333333;
            --table-border: #404040;
            --table-header-bg: #404040;
            --header-bg: #2d2d2d;
            --footer-bg: #2d2d2d;
            --footer-text: #cccccc;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.4);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.5);
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #f0f0f0;
            --bs-light: #2d2d2d;
            --bs-light-rgb: 45, 45, 45;
            --bs-dark: #f0f0f0;
            --bs-dark-rgb: 240, 240, 240;
            --hero-bg: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            --section-bg: #1a1a1a;
            --article-bg: #2d2d2d;
            --sidebar-bg: #333333;
            --well-bg: #404040;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color var(--transition-speed) ease,
                        color var(--transition-speed) ease,
                        border-color var(--transition-speed) ease,
                        box-shadow var(--transition-speed) ease,
                        background-image var(--transition-speed) ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            padding-top: 0;
            margin: 0;
        }

        .bg-white {
            background-color: var(--bg-white) !important;
        }
        
        .bg-light {
            background-color: var(--bg-secondary) !important;
        }
        
        .bg-dark {
            background-color: var(--bg-tertiary) !important;
        }
        
        .text-white {
            color: var(--text-white) !important;
        }
        
        .text-dark {
            color: var(--text-primary) !important;
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .border-white {
            border-color: var(--border-white) !important;
        }
        
        .border-light {
            border-color: var(--border-light) !important;
        }

        .navbar {
            padding: 2px 0;
            background: var(--header-bg) !important;
            box-shadow: var(--shadow-md);
            border-bottom: 2px solid var(--border-color);
        }
        
        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .site-title {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-right: 2px solid var(--border-color);
            padding-right: 15px;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap;
        }
        
        .site-title-short {
            display: none;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 0.9rem;
            border-right: 2px solid var(--border-color);
            padding-right: 8px;
            margin-right: 5px;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap;
        }
        
        @media (max-width: 380px) {
            .site-title { display: none; }
            .site-title-short { display: inline-block; }
        }
        
        .navbar-brand img {
            height: 75px;
            width: auto;
            transition: transform 0.3s ease;
            filter: brightness(1);
        }
        
        [data-theme="dark"] .navbar-brand img {
            filter: brightness(0.9) contrast(1.2);
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .navbar-nav .nav-link {
            padding: 10px 15px;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary) !important;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .navbar-nav .nav-link:hover:after,
        .navbar-nav .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 15px;
            right: 15px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }
        
        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 700;
        }

        .search-wrapper {
            display: flex;
            align-items: center;
            margin-left: 15px;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            background: var(--bg-secondary);
            border-radius: 50px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .search-form:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--input-focus);
        }
        
        .search-input {
            border: none;
            background: transparent;
            padding: 8px 15px;
            font-size: 0.9rem;
            color: var(--text-primary);
            width: 200px;
            outline: none;
        }
        
        .search-input::placeholder {
            color: var(--text-muted);
        }
        
        .search-btn {
            background: transparent;
            border: none;
            padding: 8px 15px;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 991px) {
            .search-wrapper {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
            }
            .search-form {
                width: 100%;
            }
            .search-input {
                flex: 1;
                width: auto;
            }
        }
        
        @media (max-width: 576px) {
            .search-input {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            .search-btn {
                padding: 8px 12px;
            }
        }

        .theme-toggle-wrapper {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .theme-switch-btn {
            background: transparent;
            border: 1.5px solid var(--border-color);
            border-radius: 30px;
            padding: 5px 12px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .theme-switch-btn i {
            color: var(--primary-color);
            font-size: 0.9rem;
            transition: transform 0.5s ease;
        }

        .theme-switch-btn span {
            font-size: 0.8rem;
        }

        .theme-switch-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px var(--primary-color);
            border-color: var(--primary-color);
        }

        .theme-switch-btn:hover i {
            color: white;
            transform: rotate(180deg);
        }

        @keyframes themePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .theme-switch-btn.switching {
            animation: themePulse 0.3s ease;
        }

        .hero-section {
            background: var(--hero-bg);
            color: var(--text-primary);
            padding: 100px 0;
            margin-top: 0;
        }

        .section {
            background: var(--section-bg);
            color: var(--text-primary);
            padding: 60px 0;
        }

        .section-light {
            background: var(--bg-secondary);
        }

        .article-card {
            background: var(--article-bg);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
        }

        .sidebar-content {
            background: var(--sidebar-bg);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid var(--border-light);
        }

        .list-group-item {
            background: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--border-light);
        }

        .well, .jumbotron {
            background: var(--well-bg);
            color: var(--text-primary);
        }

        .footer-custom {
            background: var(--footer-bg) !important;
            color: var(--footer-text) !important;
            padding: 4rem 0 2rem;
            margin-top: 3rem;
            border-top: 3px solid var(--border-color);
            box-shadow: var(--shadow-lg);
        }
        
        .footer-custom h5 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.8rem;
            color: var(--text-primary) !important;
        }
        
        .footer-custom h5:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color) !important;
            border-radius: 3px;
        }
        
        .footer-custom a {
            color: var(--footer-text) !important;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .footer-custom a:hover {
            color: var(--primary-color) !important;
            transform: translateX(5px);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
            color: var(--footer-text) !important;
        }
        
        .footer-links i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--bg-secondary) !important;
            border-radius: 50%;
            transition: all 0.3s;
            margin-right: 8px;
            font-size: 1.1rem;
            color: var(--primary-color) !important;
            border: 1px solid var(--border-color);
        }
        
        .social-icons a:hover {
            background: var(--primary-color) !important;
            color: white !important;
            transform: translateY(-5px);
            border-color: transparent;
        }
        
        .footer-custom hr {
            background-color: var(--border-color) !important;
            opacity: 0.3;
            margin: 2rem 0;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background: var(--bg-secondary);
            border-bottom: 2px solid var(--border-color);
            color: var(--text-primary);
            padding: 15px 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
            color: var(--text-primary);
        }

        .card-footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-light);
            color: var(--text-primary);
        }

        .table {
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: var(--table-stripe);
        }

        .table td, .table th {
            border-color: var(--table-border);
            padding: 12px;
            vertical-align: middle;
        }

        .table thead th {
            background: var(--table-header-bg);
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
        }

        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem var(--input-focus);
            color: var(--text-primary);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--text-primary);
        }

        .alert {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 15px 20px;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            border-color: var(--primary-color);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
        }

        .alert-info {
            background: rgba(23, 162, 184, 0.1);
            border-color: #17a2b8;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border-color: #ffc107;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-success {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-success:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--primary-color);
        }

        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-success:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-light {
            background: var(--bg-secondary);
            border-color: var(--border-light);
            color: var(--text-primary);
        }

        .btn-light:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge.bg-success {
            background: var(--primary-color) !important;
        }

        .badge.bg-light {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        .badge.bg-dark {
            background: var(--bg-tertiary) !important;
            color: var(--text-white) !important;
        }

        .progress {
            background: var(--bg-tertiary);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--primary-color);
            transition: width 0.6s ease;
        }

        .pagination .page-link {
            background: var(--card-bg);
            border-color: var(--border-light);
            color: var(--text-primary);
        }

        .pagination .page-link:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            background: var(--bg-secondary);
            color: var(--text-muted);
        }

        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-light);
            color: var(--text-primary);
        }

        .modal-footer {
            border-top: 1px solid var(--border-light);
        }

        .modal-title {
            color: var(--text-primary);
        }

        .dropdown-menu {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-md);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--bg-secondary);
            color: var(--primary-color);
        }

        .dropdown-divider {
            border-top: 1px solid var(--border-light);
        }

        .nav-tabs .nav-link {
            color: var(--text-primary);
            background: var(--card-bg);
            border-color: var(--border-light);
        }

        .nav-tabs .nav-link:hover {
            border-color: var(--border-color);
            color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            background: var(--card-bg);
            color: var(--primary-color);
            border-color: var(--border-color);
            border-bottom-color: var(--card-bg);
        }

        .nav-pills .nav-link {
            color: var(--text-primary);
        }

        .nav-pills .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .breadcrumb {
            background: var(--bg-secondary);
        }

        .breadcrumb-item a {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: var(--text-primary);
        }

        @media (max-width: 1199px) {
            .navbar-brand img { height: 65px; }
            .navbar-nav .nav-link { padding: 8px 12px; font-size: 0.95rem; }
        }
        
        @media (max-width: 991px) {
            .navbar-brand img { height: 60px; }
            .navbar-collapse {
                background: var(--header-bg);
                padding: 15px;
                border-radius: 8px;
                margin-top: 10px;
                box-shadow: var(--shadow-md);
            }
            .navbar-nav .nav-link {
                padding: 10px 15px;
            }
            .theme-toggle-wrapper {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
            }
            .theme-switch-btn {
                width: 100%;
                justify-content: center;
                padding: 8px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand img { height: 55px; }
        }
        
        @media (max-width: 576px) {
            .navbar-brand img { height: 50px; }
        }

        .main-content {
            min-height: calc(100vh - 200px);
            padding: 30px 0;
            background: var(--bg-primary);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content > * {
            animation: slideUp 0.5s ease-out forwards;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

        .bg-theme-primary { background: var(--bg-primary) !important; }
        .bg-theme-secondary { background: var(--bg-secondary) !important; }
        .bg-theme-tertiary { background: var(--bg-tertiary) !important; }
        .text-theme-primary { color: var(--text-primary) !important; }
        .text-theme-secondary { color: var(--text-secondary) !important; }
        .border-theme { border-color: var(--border-color) !important; }

        /* Correction mode sombre pour les badges */
        [data-theme="dark"] .badge.bg-secondary {
            background-color: #4a4a4a !important;
            color: #f0f0f0 !important;
        }

        [data-theme="dark"] .text-muted {
            color: #b0b0b0 !important;
        }

        [data-theme="dark"] .btn-outline-light {
            color: #f8f9fa;
            border-color: #f8f9fa;
        }

        [data-theme="dark"] .btn-outline-light:hover {
            background-color: #f8f9fa;
            color: #1a1a1a;
        }
    </style>
</head>
<body data-theme="light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <div class="navbar-brand-wrapper">
                <span class="site-title">Forêt urbaine Aného</span>
                <span class="site-title-short">Forêt Aného</span>
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo(2).png') }}" alt="Forêt d'Aného">
                </a>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('arbres.*') ? 'active' : '' }}" href="{{ route('arbres.index') }}">Arbres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('especes.*') ? 'active' : '' }}" href="{{ route('especes.index') }}">Espèces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('actualites.*') ? 'active' : '' }}" href="{{ route('actualites.index') }}">Actualités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                    
                    <li class="nav-item search-wrapper">
                        <form class="search-form" action="{{ route('search') }}" method="GET">
                            <input type="text" name="q" class="search-input" placeholder="Rechercher..." value="{{ request('q') }}">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                    
                    <li class="nav-item theme-toggle-wrapper">
                        <button class="theme-switch-btn" onclick="toggleTheme()" id="themeToggleBtn">
                            <i class="fas fa-sun" id="themeIcon"></i>
                            <span id="themeText">Clair</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Forêt Urbaine d'Aného</h5>
                    <p>Projet écologique de la Commune des Lacs 1 pour préserver et valoriser notre patrimoine vert.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                        <li><a href="{{ route('zones.index') }}"><i class="fas fa-chevron-right"></i> Zones</a></li>
                        <li><a href="{{ route('arbres.index') }}"><i class="fas fa-chevron-right"></i> Arbres</a></li>
                        <li><a href="{{ route('especes.index') }}"><i class="fas fa-chevron-right"></i> Espèces</a></li>
                        <li><a href="{{ route('actualites.index') }}"><i class="fas fa-chevron-right"></i> Actualités</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Zones de la forêt</h5>
                    <ul class="footer-links">
                        @php
                            $zones = \App\Models\Zone::all();
                        @endphp
                        @foreach($zones as $zone)
                            <li><a href="{{ route('zones.show', $zone->slug) }}"><i class="fas fa-chevron-right"></i> Zone {{ $zone->nom }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="col-lg-3 mb-4">
                    <h5>Contact</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Mairie d'Aného, Togo</li>
                        <li><i class="fas fa-phone"></i> +228 70 52 67 67</li>
                        <li><i class="fas fa-envelope"></i> acontact@lacs1.mairie.tg</li>
                        <li><i class="fas fa-clock"></i> Lun-Vend, 7H00-17H30</li>
                    </ul>
                    
                    <div class="border-top border-success mt-3 mb-3" style="width: 40px; opacity: 0.5;"></div>
                    
                    <h6 class="mb-2" style="color: var(--primary-color); font-size: 0.9rem;">
                        <i class="fas fa-code me-1"></i> Réalisation (Stage 2026)
                    </h6>
                    <ul class="footer-links" style="list-style: none; padding-left: 0;">
                        <li class="mb-1">
                            <i class="fas fa-user-check text-success me-2" style="width: 18px;"></i>
                            <strong>Alassane SANT'ANNA</strong> 
                            <span class="text-muted" style="font-size: 0.7rem;">| Chef projet</span>
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-paint-brush text-success me-2" style="width: 18px;"></i>
                            <strong>Joel HOUNKPATI</strong> 
                            <span class="text-muted" style="font-size: 0.7rem;">| Front-end</span>
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-map-marked-alt text-success me-2" style="width: 18px;"></i>
                            <strong>Pauline AGBOKOU</strong> 
                            <span class="text-muted" style="font-size: 0.7rem;">| Cartographie</span>
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-database text-success me-2" style="width: 18px;"></i>
                            <strong>Koffi Justin KOUTOWOU</strong> 
                            <span class="text-muted" style="font-size: 0.7rem;">| Back-end</span>
                        </li>
                    </ul>
                    <small class="text-muted" style="font-size: 0.7rem;">
                        <i class="fas fa-graduation-cap me-1"></i> Projet réalisé à la Mairie Lacs 1 (Aného)
                    </small>
                </div>
            </div>
            <hr>

            <!-- LIGNE : DOCUMENTATION ET CRÉDITS 
            <div class="row mt-4 mb-3">
                <div class="col-md-12 text-center">
                    <a href="{{ route('documentation') }}" class="btn btn-sm btn-outline-light me-3" style="border-radius: 30px;">
                        <i class="fas fa-book me-1"></i> Documentation technique
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-light" style="border-radius: 30px;" data-bs-toggle="modal" data-bs-target="#creditsModal">
                        <i class="fas fa-users me-1"></i> Crédits
                    </button>
                </div>
            </div>-->
            
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2026 Mairie Lacs 1. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="me-3">Mentions légales</a>
                    <a href="#">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- MODAL DES CRÉDITS -->
    <div class="modal fade" id="creditsModal" tabindex="-1" aria-labelledby="creditsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-color); color: white;">
                    <h5 class="modal-title" id="creditsModalLabel">
                        <i class="fas fa-tree me-2"></i> Crédits du projet
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-code me-2"></i> Équipe de développement
                    </h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-database text-success"></i>
                                </div>
                                <div>
                                    <strong>Alassane SANT'ANNA</strong><br>
                                    <small class="text-muted">Chef de projet & Back-end</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-paint-brush text-success"></i>
                                </div>
                                <div>
                                    <strong>Joel HOUNKPATI</strong><br>
                                    <small class="text-muted">Front-end & Intégrateur</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-map-marked-alt text-success"></i>
                                </div>
                                <div>
                                    <strong>Pauline Amigan AGBOKOU</strong><br>
                                    <small class="text-muted">Cartographie & API</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-tachometer-alt text-success"></i>
                                </div>
                                <div>
                                    <strong>Koffi Justin KOUTOWOU</strong><br>
                                    <small class="text-muted">Administration & CRUD</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-chalkboard-user me-2"></i> Encadrement
                    </h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-2">
                                <i class="fas fa-user-tie fa-2x mb-2" style="color: var(--primary-color);"></i>
                                <p class="mb-0"><strong>Mme Priskila AGBOGBE</strong></p>
                                <small class="text-muted">Service communication</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-2">
                                <i class="fas fa-laptop-code fa-2x mb-2" style="color: var(--primary-color);"></i>
                                <p class="mb-0"><strong>M. Marthey & M. Blaise</strong></p>
                                <small class="text-muted">Responsables Informatique</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-2">
                                <i class="fas fa-university fa-2x mb-2" style="color: var(--primary-color);"></i>
                                <p class="mb-0"><strong>M. Alex WILSON</strong></p>
                                <small class="text-muted">Tuteur pédagogique</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-cogs me-2"></i> Technologies
                    </h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-secondary">Laravel 12</span>
                        <span class="badge bg-secondary">PHP 8.2</span>
                        <span class="badge bg-secondary">MySQL</span>
                        <span class="badge bg-secondary">Bootstrap 5</span>
                        <span class="badge bg-secondary">Leaflet.js</span>
                        <span class="badge bg-secondary">Chart.js</span>
                        <span class="badge bg-secondary">jQuery</span>
                        <span class="badge bg-secondary">Google Maps API</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            body.setAttribute('data-theme', newTheme);
            updateThemeUI(newTheme);
            localStorage.setItem('selectedTheme', newTheme);
            animateThemeButton();
        }

        function updateThemeUI(theme) {
            const icon = document.getElementById('themeIcon');
            const text = document.getElementById('themeText');
            const btn = document.getElementById('themeToggleBtn');
            
            if (theme === 'light') {
                icon.className = 'fas fa-sun';
                text.textContent = 'Clair';
                btn.style.borderColor = '#036f15';
            } else {
                icon.className = 'fas fa-moon';
                text.textContent = 'Sombre';
                btn.style.borderColor = '#4caf50';
            }
        }

        function animateThemeButton() {
            const btn = document.getElementById('themeToggleBtn');
            btn.classList.add('switching');
            setTimeout(() => btn.classList.remove('switching'), 300);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('selectedTheme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
            updateThemeUI(savedTheme);

            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const currentPath = window.location.pathname;
            
            navLinks.forEach(link => {
                if (link.closest('.search-wrapper') || link.closest('.theme-toggle-wrapper')) {
                    return;
                }
                
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
                
                link.addEventListener('click', function() {
                    navLinks.forEach(l => {
                        if (!l.closest('.search-wrapper') && !l.closest('.theme-toggle-wrapper')) {
                            l.classList.remove('active');
                        }
                    });
                    this.classList.add('active');
                });
            });

            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                const allLinks = document.querySelectorAll('.navbar-nav a');
                allLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                            navbarToggler.click();
                        }
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>