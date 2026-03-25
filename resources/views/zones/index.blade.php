@extends('layouts.app')

@section('title', 'Zones de la Forêt - Forêt Urbaine d\'Aného')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs premium */
    :root {
        --premium-green: #2E7D32;
        --premium-gold: #f3ff07;
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
    
    /* Légende pour la carte */
    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    /* Cartes zones - Version Premium */
    .zones-grid {
        margin-top: 2rem;
    }
    
    .zone-card {
        position: relative;
        height: 500px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 2rem;
    }
    
    .zone-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 40px -15px rgba(0,0,0,0.3);
    }
    
    .zone-card-overlay {
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
    
    .zone-card:hover .zone-card-overlay {
        transform: translateY(-5px);
    }
    
    .zone-card-title {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
        letter-spacing: -0.5px;
    }
    
    .zone-card-text {
        font-size: 1rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        line-height: 1.6;
        max-width: 80%;
    }
    
    .zone-card-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .zone-card-stats i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .zone-card-link {
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
    
    .zone-card-link:hover {
        gap: 1.2rem;
        border-bottom-color: var(--premium-gold);
    }
    
    .zone-card-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    .zone-card-link:hover i {
        transform: translateX(5px);
    }
    
    /* Carte */
    .map-container {
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
        margin-bottom: 3rem;
    }
    
    #map-zones {
        height: 500px;
        width: 100%;
    }
    
    /* Section title */
    .section-title-wrapper {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 2.8rem;
        font-weight: 300;
        color: var(--premium-dark);
        margin-bottom: 1rem;
    }
    
    .section-title strong {
        font-weight: 700;
        color: var(--premium-green);
    }
    
    .section-divider {
        width: 60px;
        height: 2px;
        background: var(--premium-gold);
        margin: 1rem auto 0;
    }
    
    /* Flèche directionnelle - petite */
    .scroll-arrow {
        font-size: 1rem;
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    
    .scroll-arrow:hover {
        opacity: 1;
    }
    
    /* Tableau */
    .table-container {
        background: white;
        border: 1px solid #eee;
        padding: 2rem;
        margin-top: 3rem;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background: var(--premium-green);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        transition: background 0.3s;
    }
    
    .table tbody tr:hover {
        background: var(--premium-light);
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
    }
    
    /* Bouton exporter */
    .btn-exporter {
        background: transparent;
        color: var(--premium-dark);
        border: 1px solid var(--premium-gold);
        padding: 0.5rem 1.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s;
        margin-left: 0.5rem;
    }
    
    .btn-exporter:hover {
        background: var(--premium-gold);
        color: white;
    }
    
    /* Espacements - MODIFIÉS */
    .mt-5 {
        margin-top: 2rem !important;
        margin-bottom: 0 !important;
    }
    
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 0 !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .zone-card {
            height: 400px;
        }
        
        .zone-card-title {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 2.2rem;
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
        
        .zone-card {
            height: 350px;
        }
        
        .zone-card-overlay {
            padding: 2rem;
        }
        
        .zone-card-text {
            max-width: 100%;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .scroll-arrow {
            font-size: 0.9rem;
        }
        
        .table-container {
            padding: 1rem;
            overflow-x: auto;
        }
        
        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ asset('images/forest-header.jpg') }}" alt="Zones" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
                <h1 class="hero-title float-animation">
                    Les Zones de la Forêt
                </h1>
                <p class="hero-subtitle">
                    Découvrez les {{ $zones->count() }} zones qui composent notre forêt urbaine, chacune avec ses caractéristiques uniques.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#carte" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Carte des zones -->
<section id="carte" style="padding-top: 3rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Carte <strong>interactive</strong></h2>
            <div class="section-divider"></div>
            <p style="color: #666; margin-top: 1rem;">Cliquez sur une zone pour en savoir plus</p>
        </div>
        
        <div class="map-container">
            <div id="map-zones"></div>
        </div>
        
        <div class="row mt-4" style="margin-bottom: 0 !important;">
            <div class="col-md-8">
                <div class="legend">
                    <h6 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 1rem;">Légende des zones :</h6>
                    <div class="d-flex flex-wrap">
                        @foreach($zones as $zone)
                        <div class="me-4 mb-2">
                            <span class="legend-dot" style="background-color: {{ $zone->couleur }};"></span> 
                            <span style="font-weight: 500; color: #666;">{{ $zone->nom }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn-exporter" onclick="resetMap()">
                    <i class="fas fa-sync-alt me-2"></i>Réinitialiser
                </button>
                <button class="btn-exporter" onclick="exportMap()">
                    <i class="fas fa-download me-2"></i>Exporter
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Liste des zones - MÊME DESIGN QUE L'ACCUEIL -->
<section class="bg-light" style="padding-top: 3rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Découvrez <strong>chaque zone</strong></h2>
            <div class="section-divider"></div>
            <p style="color: #666; margin-top: 1rem;">Quatre univers uniques au cœur de la forêt urbaine</p>
        </div>
        
        <div class="row g-4" style="margin-bottom: 0 !important;">
            @foreach($zones as $zone)
            <div class="col-md-6">
                <a href="{{ route('zones.show', $zone->slug) }}" class="text-decoration-none">
                    <div class="zone-card" style="background-image: url('{{ $zone->image_url }}');">
                        <div class="zone-card-overlay">
                            <h3 class="zone-card-title">{{ $zone->nom }}</h3>
                            <p class="zone-card-text">{{ $zone->description_courte }}</p>
                            <div class="zone-card-stats">
                                <span><i class="fas fa-tree"></i> {{ $zone->nombre_arbres }} arbres</span>
                                <span><i class="fas fa-leaf"></i> {{ $zone->nombre_especes }} espèces</span>
                            </div>
                            <span class="zone-card-link">
                                DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
/* Surcharge des styles du layout pour supprimer tous les espaces */
.main-content {
    min-height: auto !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.footer-custom {
    margin-top: 0 !important;
}

/* Supprimer toutes les marges et paddings entre les sections */
section {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Supprimer l'espace du dernier élément avant le footer */
.container > :last-child,
.bg-light > :last-child,
section > :last-child {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let polygons = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser la carte
        map = L.map('map-zones').setView([6.2276, 1.5968], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        // Données des zones
        const zones = @json($zones);
        
        // Coordonnées approximatives
        const coordsMap = {
            'GLIDJI': { center: [6.233, 1.588], points: [[6.230,1.590], [6.232,1.592], [6.234,1.588], [6.238,1.586], [6.235,1.582]] },
            'LOLAN': { center: [6.228, 1.598], points: [[6.225,1.600], [6.228,1.602], [6.232,1.598], [6.230,1.596], [6.227,1.594]] },
            'NLESS': { center: [6.240, 1.577], points: [[6.240,1.580], [6.244,1.578], [6.242,1.576], [6.238,1.576], [6.236,1.578]] },
            'YVELINES': { center: [6.219, 1.604], points: [[6.215,1.605], [6.218,1.608], [6.220,1.605], [6.222,1.602], [6.218,1.600]] }
        };
        
        // Ajouter les polygones
        zones.forEach(zone => {
            const zoneData = coordsMap[zone.nom] || { 
                center: [6.2276, 1.5968],
                points: [[6.2276,1.5968]]
            };
            
            const polygon = L.polygon(zoneData.points, {
                color: zone.couleur,
                fillColor: zone.couleur,
                fillOpacity: 0.3,
                weight: 2
            }).addTo(map);
            
            polygon.bindPopup(`
                <div style="text-align: center; padding: 0.5rem;">
                    <h6 style="font-weight: 600; margin-bottom: 0.3rem;">Zone ${zone.nom}</h6>
                    <p style="margin-bottom: 0.5rem; font-size: 0.8rem;">${zone.nombre_arbres} arbres</p>
                    <a href="/zones/${zone.slug}" style="display: inline-block; background: #2E7D32; color: white; text-decoration: none; padding: 0.3rem 1rem; font-size: 0.8rem;">Explorer</a>
                </div>
            `);
            
            polygons.push(polygon);
            
            // Ajouter un marqueur
            L.marker(zoneData.center).addTo(map)
                .bindPopup(`<b>Zone ${zone.nom}</b>`);
        });
    });
    
    function resetMap() {
        map.setView([6.2276, 1.5968], 13);
    }
    
    function exportMap() {
        alert('Fonctionnalité d\'export à venir');
    }
</script>
@endpush