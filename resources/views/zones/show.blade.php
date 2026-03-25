@extends('layouts.app')

@section('title', 'Zone ' . $zone->nom . ' - Forêt Urbaine d\'Aného')

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
        height: 70vh;
        min-height: 600px;
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
        background: linear-gradient(135deg, var(--premium-overlay) 0%, rgba(0,0,0,0.3) 100%);
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
        font-size: 4rem;
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        line-height: 1.1;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
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
    
    /* Badges */
    .badge-premium {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
        background: {{ $zone->couleur }};
    }
    
    /* Info cards */
    .info-card {
        background: white;
        border-radius: 0;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
        border: 1px solid #eee;
    }
    
    .section-title-sm {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid {{ $zone->couleur }};
        position: relative;
    }
    
    .section-title-sm i {
        color: {{ $zone->couleur }};
        margin-right: 0.5rem;
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
    
    .tree-card-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    
    .tree-card-species {
        font-size: 1rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 0.8rem;
        font-style: italic;
    }
    
    .tree-card-date {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 1.5rem;
    }
    
    .tree-card-date i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .tree-card-link {
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
    
    .tree-card-link:hover {
        gap: 1.2rem;
        border-bottom-color: var(--premium-gold);
    }
    
    .tree-card-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    .tree-card-link:hover i {
        transform: translateX(5px);
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
        color: {{ $zone->couleur }};
    }
    
    .section-divider {
        width: 60px;
        height: 2px;
        background: var(--premium-gold);
        margin: 1rem auto 0;
    }
    
    /* Espacements réduits */
    .mt-5 {
        margin-top: 2.5rem !important;
    }
    
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .tree-card {
            height: 450px;
        }
        
        .tree-card-title {
            font-size: 1.8rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-section {
            height: 60vh;
            min-height: 500px;
        }
        
        .info-card {
            padding: 1.5rem;
        }
        
        .tree-card {
            height: 400px;
        }
        
        .tree-card-overlay {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ $zone->image_url }}" alt="Zone {{ $zone->nom }}" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
               {{-- <nav class="breadcrumb-custom">
                    <a href="{{ route('home') }}">Accueil</a> / 
                    <a href="{{ route('zones.index') }}">Zones</a> / 
                    <span class="active">{{ $zone->nom }}</span>
                </nav>--}}
                
                <div class="mb-4">
                    <span class="badge-premium">
                        <i class="fas fa-map-marked-alt me-2"></i>Zone {{ $zone->nom }}
                    </span>
                </div>
                
                <h1 class="hero-title float-animation">
                    {{ $zone->nom }}
                </h1>
                <p class="hero-subtitle">
                    {{ $zone->description_courte }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#arbres" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Contenu principal -->
<section id="contenu" class="py-5">
    <div class="container">
        <div class="row">
            <!-- Colonne principale (description) -->
            <div class="col-lg-8">
                <!-- Description détaillée -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-info-circle"></i>Description
                    </h3>
                    <p style="color: #666; line-height: 1.8;">{{ $zone->description_longue ?? $zone->description_courte }}</p>
                </div>
                
                <!-- Espèces principales -->
                @if($zone->especes_principales)
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-leaf"></i>Espèces principales
                    </h3>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $especesPrincipales = is_array($zone->especes_principales) ? $zone->especes_principales :
                                                  (is_string($zone->especes_principales) ? json_decode($zone->especes_principales, true) : []);
                        @endphp
                        
                        @if(is_array($especesPrincipales) && count($especesPrincipales) > 0)
                            @foreach($especesPrincipales as $espece)
                            <span style="display: inline-block; padding: 0.4rem 1rem; background: var(--premium-light); color: var(--premium-dark); border: 1px solid #eee;">
                                {{ is_array($espece) ? $espece['nom'] : $espece }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Colonne latérale (carte) -->
            <div class="col-lg-4">
                <!-- Carte de localisation -->
                <div class="info-card p-0">
                    <div id="zone-map" style="height: 300px;"></div>
                    <div style="padding: 1.5rem;">
                        <h5 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 1rem;">Localisation</h5>
                        @if($zone->latitude && $zone->longitude)
                        <p style="margin-bottom: 0.3rem; color: #666;">
                            <i class="fas fa-map-pin me-2" style="color: {{ $zone->couleur }};"></i> Lat: {{ $zone->latitude }}° N
                        </p>
                        <p style="margin-bottom: 1rem; color: #666;">
                            <i class="fas fa-map-pin me-2" style="color: {{ $zone->couleur }};"></i> Long: {{ $zone->longitude }}° E
                        </p>
                        @else
                        <p style="color: #999; margin-bottom: 1rem;">Coordonnées non disponibles</p>
                        @endif
                        
                        <button class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.8rem; width: 100%; font-size: 0.85rem;" onclick="ouvrirItineraire()">
                            <i class="fas fa-route me-2" style="color: var(--premium-gold);"></i>ITINÉRAIRE
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Arbres de la zone - 2 par ligne avec image de fond -->
        <div id="arbres" class="row mt-5">
            <div class="col-12">
                <div class="section-title-wrapper">
                    <h2 class="section-title">Arbres de la zone <strong>{{ $zone->nom }}</strong></h2>
                    <div class="section-divider"></div>
                </div>
                
                <div class="trees-grid">
                    <div class="row g-4">
                        @forelse($arbres as $arbre)
                        <div class="col-md-6">
                            <a href="{{ route('arbres.show', $arbre->slug) }}" class="text-decoration-none">
                                <div class="tree-card" style="background-image: url('{{ $arbre->photo_url }}');">
                                    <div class="tree-card-overlay">
                                        <h3 class="tree-card-title">{{ $arbre->nom }}</h3>
                                        <p class="tree-card-species">{{ $arbre->espece->nom_local }}</p>
                                        <div class="tree-card-date">
                                            <i class="fas fa-calendar-alt"></i> {{ $arbre->date_plantation->format('d/m/Y') }}
                                        </div>
                                        <span class="tree-card-link">
                                            DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-12">
                            <div style="text-align: center; padding: 3rem; background: var(--premium-light);">
                                <i class="fas fa-tree fa-3x" style="color: #ddd; margin-bottom: 1rem;"></i>
                                <h4 style="color: #999;">Aucun arbre enregistré</h4>
                                <p style="color: #ccc;">Cette zone n'a pas encore d'arbres.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                @if($arbres->count() > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('arbres.index') }}?zone={{ $zone->id }}" class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.8rem 3rem; text-decoration: none; display: inline-block;">
                        <i class="fas fa-tree me-2"></i>VOIR TOUS LES ARBRES
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const zone = @json($zone);
        
        let lat = zone.latitude ? parseFloat(zone.latitude) : 6.2276;
        let lng = zone.longitude ? parseFloat(zone.longitude) : 1.5968;
        
        const zoneMap = L.map('zone-map').setView([lat, lng], 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(zoneMap);
        
        const zoneIcon = L.divIcon({
            html: `<div style="background-color: ${zone.couleur || '#2E7D32'}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.3);"></div>`,
            className: 'zone-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        L.marker([lat, lng], {icon: zoneIcon})
            .addTo(zoneMap)
            .bindPopup(`<b>Zone ${zone.nom}</b>`)
            .openPopup();
    });
    
    function ouvrirItineraire() {
        const lat = {{ $zone->latitude ?? 6.2276 }};
        const lng = {{ $zone->longitude ?? 1.5968 }};
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const url = `https://www.google.com/maps/dir/?api=1&origin=${position.coords.latitude},${position.coords.longitude}&destination=${lat},${lng}&travelmode=walking`;
                    window.open(url, '_blank');
                },
                function() {
                    window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`, '_blank');
                }
            );
        } else {
            window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`, '_blank');
        }
    }
</script>
@endpush