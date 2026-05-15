@extends('layouts.app')

@section('title', 'Les Arbres - Forêt Urbaine d\'Aného')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs premium */
    :root {
        --premium-green: #2E7D32;
        --premium-gold:  #f3ff07 ;
        --premium-dark: #1B4D3E;
        --premium-light: #F8F9FA;
        --premium-overlay: rgba(27, 77, 62, 0.7);
    }
    
    /* Styles généraux */
    * {
        font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    
    .hover-scale {
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
    }
    .hover-scale:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 30px 40px -15px rgba(0,0,0,0.2) !important;
    }
    
    .btn-hover-effect {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .btn-hover-effect:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.2);
        transition: left 0.3s ease;
        z-index: -1;
    }
    .btn-hover-effect:hover:before {
        left: 0;
    }
    .btn-hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(46,125,50,0.3);
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }
    
    .float-animation {
        animation: float 4s ease-in-out infinite;
    }
    
    /* Header */
    .hero-section {
        height: 60vh;
        min-height: 500px;
        position: relative;
        overflow: hidden;
        margin-top: -20px;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--premium-overlay) 0%, rgba(0,0,0,0.4) 100%);
        z-index: 1;
    }
    
    .hero-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        font-weight: 300;
        max-width: 600px;
        margin-bottom: 2rem;
        opacity: 0.9;
        line-height: 1.6;
    }
    
    /* Breadcrumb */
    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 1.5rem;
    }
    
    .breadcrumb-custom a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .breadcrumb-custom a:hover {
        color: var(--premium-gold);
    }
    
    .breadcrumb-custom .active {
        color: white;
    }
    
    /* Filtres */
    .filter-box {
        background: white;
        border-radius: 0;
        padding: 2rem;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
        border: 1px solid #eee;
    }
    
    .filter-label {
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    
    .filter-label i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 0;
        padding: 0.8rem 1.2rem;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--premium-gold);
        box-shadow: none;
    }
    
    .btn-outline-premium {
        background: transparent;
        color: var(--premium-dark);
        border: 1px solid var(--premium-gold);
        padding: 0.8rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.4s;
        display: inline-block;
        width: 25%;
    }
    
    .btn-outline-premium:hover {
        background: var(--premium-gold);
        color: white;
        border-color: var(--premium-gold);
    }
    
    /* Carte */
    .map-container {
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
        margin-bottom: 3rem;
    }
    
    #arbres-map {
        height: 450px;
        width: 100%;
    }
    
    /* Cartes arbres - Version Premium avec image de fond */
    .trees-grid {
        margin-top: 2rem;
    }
    
    .tree-card {
        position: relative;
        height: 500px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 2rem;
    }
    
    .tree-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 40px -15px rgba(0,0,0,0.3);
    }
    
    .tree-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 3rem;
        background: linear-gradient(to top, var(--premium-overlay), transparent);
        color: white;
        transform: translateY(0);
        transition: transform 0.5s ease;
    }
    
    .tree-card:hover .tree-card-overlay {
        transform: translateY(-5px);
    }
    
    .tree-zone-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--premium-gold);
        color: white;
        padding: 0.5rem 1.2rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 2;
    }
    
    .tree-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    
    .tree-species {
        font-size: 1rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 0.8rem;
        font-style: italic;
    }
    
    .tree-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.2rem;
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .tree-meta i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .tree-description {
        font-size: 0.95rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        max-width: 80%;
    }
    
    .tree-link {
        color: var(--premium-gold);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: gap 0.3s;
        border-bottom: 1px solid transparent;
        padding-bottom: 3px;
    }
    
    .tree-link:hover {
        gap: 1.2rem;
        border-bottom-color: var(--premium-gold);
    }
    
    .tree-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    .tree-link:hover i {
        transform: translateX(5px);
    }
    
    /* Section title */
    .section-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 300;
        color: var(--premium-dark);
        margin-bottom: 0;
    }
    
    .section-title strong {
        font-weight: 700;
        color: var(--premium-green);
    }
    
    .section-badge {
        background: var(--premium-gold);
        color: white;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }
    
    /* Pagination ULTRA RÉDUITE */
    .pagination {
        justify-content: center;
        gap: 0.2rem;
        margin: 1rem 0 0.5rem 0 !important;
    }
    
    .pagination .page-link {
        border: none;
        background: transparent;
        padding: 0.3rem 0.6rem !important;
        color: var(--premium-dark);
        font-weight: 400;
        font-size: 0.75rem !important;
        transition: all 0.2s;
        border-radius: 3px;
    }
    
    .pagination .page-link:hover {
        background: var(--premium-gold);
        color: white;
        transform: translateY(-1px);
    }
    
    .pagination .active .page-link {
        background: var(--premium-green);
        color: white;
    }
    
    .pagination .disabled .page-link {
        opacity: 0.3;
        pointer-events: none;
    }
    
    /* Styles pour les boutons de navigation personnalisés (Précédent/Suivant) - RÉDUITS */
    .btn-outline-premium{
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .custom-pagination .nav-btn {
        background: transparent;
        border: 1px solid var(--premium-gold);
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
        color: var(--premium-dark);
        border-radius: 3px;
    }
    
    .custom-pagination .nav-btn:hover {
        background: var(--premium-gold);
        color: white;
        transform: translateY(-2px);
    }
    
    .custom-pagination .nav-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .custom-pagination .page-badge {
        background: var(--premium-gold);
        color: white;
        padding: 0.3rem 1rem;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 0.75rem;
        border-radius: 3px;
    }


    /*pagination nouveeau pour les boutons précédent et suivant*/

    
    /* Espacements réduits */
    .mt-5 {
        margin-top: 2rem !important;
    }
    
    .pb-5 {
        padding-bottom: 2rem !important;
    }
    
    .py-5 {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    
    /* Masquer le message d'activation Windows */
    .text-center:contains("Activer Windows"),
    [class*="Activer"] {
        display: none !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .tree-card {
            height: 450px;
        }
        
        .tree-title {
            font-size: 1.8rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-section {
            height: 50vh;
            min-height: 400px;
        }
        
        .filter-box {
            padding: 1.5rem;
        }
        
        .section-title-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .tree-card {
            height: 400px;
        }
        
        .tree-card-overlay {
            padding: 2rem;
        }
        
        .tree-description {
            max-width: 100%;
        }
        
        .pagination .page-link {
            padding: 0.2rem 0.4rem !important;
            font-size: 0.7rem !important;
        }
        
        .custom-pagination {
            gap: 0.75rem;
        }
        
        .custom-pagination .nav-btn {
            padding: 0.3rem 0.8rem;
            font-size: 0.7rem;
        }
        
        .custom-pagination .page-badge {
            padding: 0.25rem 0.8rem;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ asset('images/arbres-header.jpg') }}" alt="Arbres" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
               {{-- <nav class="breadcrumb-custom">
                    <a href="{{ route('home') }}">Accueil</a> / <span class="active">Arbres</span>
                </nav> --}}
                
                <h1 class="hero-title float-animation">
                    Les Arbres
                </h1>
                <p class="hero-subtitle">
                    Découvrez tous les arbres de notre forêt urbaine. Utilisez la carte et les filtres pour trouver un arbre spécifique.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filtres Premium -->
{{--<section id="filtres" class="py-5">
    <div class="container">
        <div class="filter-box">
            <form method="GET" action="{{ route('arbres.index') }}" id="search-form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="filter-label"><i class="fas fa-search"></i>Rechercher</label>
                        <input type="text" name="search" class="form-control" placeholder="Nom de l'arbre..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="filter-label"><i class="fas fa-map-marked-alt"></i>Zone</label>
                        <select name="zone" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes les zones</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ request('zone') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="filter-label"><i class="fas fa-leaf"></i>Espèce</label>
                        <select name="espece" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes les espèces</option>
                            @foreach($especes as $espece)
                                <option value="{{ $espece->id }}" {{ request('espece') == $espece->id ? 'selected' : '' }}>
                                    {{ $espece->nom_local }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('arbres.index') }}" class="btn-outline-premium">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>--}}

<!-- Liste des arbres Premium -->
<section class="pb-5">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Tous les <strong>arbres</strong></h2>
            <span class="section-badge">Page {{ $arbres->currentPage() }} / {{ $arbres->lastPage() }}</span>
        </div>
        
        <div class="trees-grid">
            <div class="row g-4">
                @forelse($arbres as $arbre)
                <div class="col-md-6">
                    <a href="{{ route('arbres.show', $arbre->slug) }}" class="text-decoration-none">
                        <div class="tree-card" style="background-image: url('{{ $arbre->photo_url}}');">
                            <div class="tree-zone-badge">{{ $arbre->zone->nom }}</div>
                            
                            <div class="tree-card-overlay">
                                <h3 class="tree-title">{{ $arbre->nom }}</h3>
                                <p class="tree-species">{{ $arbre->espece->nom_local }}</p>
                                
                                <div class="tree-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ $arbre->date_plantation->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-user"></i> {{ $arbre->planteur_nom }}</span>
                                </div>
                                
                                <p class="tree-description">{{ Str::limit($arbre->description, 120) }}</p>
                                
                                <span class="tree-link">
                                    DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-tree fa-4x" style="color: #ddd; margin-bottom: 1rem;"></i>
                        <h4 style="color: #999;">Aucun arbre trouvé</h4>
                        <p style="color: #ccc;">Essayez de modifier vos filtres de recherche.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Pagination -->
<div class="d-flex justify-content-center align-items-center gap-3 mt-4">

    {{-- Bouton précédent --}}
    @if ($arbres->onFirstPage())
        <span class="btn-outline-premium disabled">← Précédent</span>
    @else
        <a href="{{ $arbres->previousPageUrl() }}" class="btn-outline-premium">
            ← Précédent
        </a>
    @endif

    {{-- Numéro de page --}}
    <span>
        Page {{ $arbres->currentPage() }} / {{ $arbres->lastPage() }}
    </span>

    {{-- Bouton suivant --}}
    @if ($arbres->hasMorePages())
        <a href="{{ $arbres->nextPageUrl() }}" class="btn-outline-premium">
            Suivant →
        </a>
    @else
        <span class="btn-outline-premium disabled">Suivant →</span>
    @endif

</div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Arbres <strong>par zone</strong></h2>
            <div class="section-divider"></div>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Zone</th>
                        <th>Arbres</th>
                        <th>Espèces principales</th>
                        <th>Dernière plantation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zones as $zone)
                    <tr>
                        <td>
                            <span style="display: inline-block; width: 12px; height: 12px; background-color: {{ $zone->couleur }}; border-radius: 50%; margin-right: 8px;"></span>
                            {{ $zone->nom }}
                        </td>
                        <td><span style="font-weight: 600;">{{ $zone->nombre_arbres }}</span></td>
                        <td>
                            <small style="color: #666;">
                                @php
                                    $especesPrincipales = $zone->arbres()
                                        ->with('espece')
                                        ->get()
                                        ->pluck('espece.nom_local')
                                        ->unique()
                                        ->take(3)
                                        ->implode(', ');
                                @endphp
                                {{ $especesPrincipales ?: 'Non défini' }}
                            </small>
                        </td>
                        <td>
                            @php
                                $dernierArbre = $zone->arbres()->orderBy('date_plantation', 'desc')->first();
                            @endphp
                            <small style="color: #666;">{{ $dernierArbre ? $dernierArbre->date_plantation->format('d/m/Y') : 'N/A' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('arbres.index') }}?zone={{ $zone->id }}" style="color: var(--premium-green); text-decoration: none; font-size: 0.85rem;">
                                Voir <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('arbres.index') }}" class="btn-exporter" style="padding: 0.8rem 3rem; display: inline-block;">
                
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let markers = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser la carte
        map = L.map('arbres-map').setView([6.2276, 1.5968], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        // Données des arbres
        const arbres = @json($arbres->items());
        
        // Ajouter les marqueurs
        arbres.forEach(arbre => {
            if (arbre.latitude && arbre.longitude) {
                const zoneColor = arbre.zone ? arbre.zone.couleur : '#2E7D32';
                
                const treeIcon = L.divIcon({
                    html: `<div style="background-color: ${zoneColor}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.3);"></div>`,
                    className: 'tree-marker',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
                
                const marker = L.marker([arbre.latitude, arbre.longitude], {icon: treeIcon})
                    .addTo(map)
                    .bindPopup(`
                        <div style="text-align: center; min-width: 160px;">
                            <h6 style="margin: 5px 0; font-weight: 600; font-size: 13px;">${arbre.nom}</h6>
                            <p style="margin: 2px 0; font-size: 11px; color: #666;">${arbre.zone ? arbre.zone.nom : ''} • ${arbre.espece ? arbre.espece.nom_local : ''}</p>
                            <a href="/arbres/${arbre.slug}" style="display: inline-block; background: #2E7D32; color: white; text-decoration: none; padding: 3px 10px; font-size: 10px; margin-top: 5px;">VOIR</a>
                        </div>
                    `);
                
                markers.push(marker);
            }
        });
        
        // Ajuster la vue
        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    });
</script>
@endpush