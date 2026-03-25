@extends('layouts.app')

@section('title', $espece->nom_local . ' - Fiche Espèce - Forêt Urbaine d\'Aného')

@push('styles')
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs premium */
    :root {
        --premium-green: #2E7D32;
        --premium-gold:  #f3ff07; ;
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
    
    /* Badge catégorie */
    .badge-categorie-premium {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
        background: var(--premium-gold);
        margin-bottom: 1rem;
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
        border-bottom: 2px solid var(--premium-gold);
        position: relative;
    }
    
    .section-title-sm i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .info-table {
        width: 100%;
    }
    
    .info-table th {
        width: 40%;
        padding: 0.8rem 0;
        color: #666;
        font-weight: 500;
        border-bottom: 1px solid #eee;
    }
    
    .info-table td {
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        color: var(--premium-dark);
        font-weight: 500;
    }
    
    .info-table tr:last-child th,
    .info-table tr:last-child td {
        border-bottom: none;
    }
    
    .stat-box {
        background: var(--premium-light);
        padding: 1.5rem;
        text-align: center;
        border: 1px solid #eee;
        transition: all 0.3s;
    }
    
    .stat-box:hover {
        border-color: var(--premium-gold);
    }
    
    .stat-box .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--premium-green);
        line-height: 1.2;
    }
    
    .stat-box .label {
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .periode-item {
        background: var(--premium-light);
        padding: 1rem;
        margin-bottom: 0.8rem;
        border-left: 3px solid var(--premium-gold);
    }
    
    .conseil-item {
        background: var(--premium-light);
        padding: 1rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
    }
    
    .conseil-item i {
        color: var(--premium-gold);
        margin-right: 1rem;
        width: 20px;
    }
    
    /* Onglets */
    .tabs-container {
        border-bottom: 1px solid #eee;
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
    }
    
    .tab {
        padding: 0.8rem 2rem;
        font-weight: 500;
        color: #999;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
    
    .tab:hover {
        color: var(--premium-gold);
    }
    
    .tab.active {
        color: var(--premium-dark);
    }
    
    .tab.active:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--premium-gold);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Cartes arbres */
    .trees-grid {
        margin-top: 2rem;
    }
    
    .tree-card {
        position: relative;
        height: 400px;
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
        padding: 2rem;
        background: linear-gradient(to top, var(--premium-overlay), transparent);
        color: white;
        transform: translateY(0);
        transition: transform 0.5s ease;
    }
    
    .tree-card:hover .tree-card-overlay {
        transform: translateY(-5px);
    }
    
    .tree-card-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .tree-card-zone {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    
    .tree-card-zone i {
        color: var(--premium-gold);
        margin-right: 0.3rem;
    }
    
    .tree-card-link {
        color: var(--premium-gold);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.3s;
    }
    
    .tree-card-link:hover {
        gap: 0.8rem;
    }
    
    /* Section title */
    .section-title-wrapper {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 2.5rem;
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
        margin: 0 auto;
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
            height: 350px;
        }
        
        .tab {
            padding: 0.6rem 1rem;
            font-size: 0.75rem;
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
            height: 300px;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .tabs-container {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ $espece->image_url }}" alt="{{ $espece->nom_local }}" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
               {{-- <nav class="breadcrumb-custom">
                    <a href="{{ route('home') }}">Accueil</a> / 
                    <a href="{{ route('especes.index') }}">Espèces</a> / 
                    <span class="active">{{ $espece->nom_local }}</span>
                </nav>--}}
                
                <div class="badge-categorie-premium">
                    {{ $espece->categorie_formatee }}
                </div>
                
                <h1 class="hero-title float-animation">
                    {{ $espece->nom_scientifique }}
                </h1>
                <p class="hero-subtitle">
                    {{ $espece->nom_local }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#description" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Contenu principal -->
<section id="description" class="py-5">
    <div class="container">
        <!-- Onglets -->
        <div class="tabs-container">
            <div class="tab active" onclick="showTab('presentation')">PRÉSENTATION</div>
            @if($espece->description_botanique)
            <div class="tab" onclick="showTab('botanique')">BOTANIQUE</div>
            @endif
            @if($espece->utilisation)
            <div class="tab" onclick="showTab('utilisation')">UTILISATIONS</div>
            @endif
            @if($espece->importance_culturelle)
            <div class="tab" onclick="showTab('culture')">CULTURE</div>
            @endif
            @if($espece->conseils_plantation)
            <div class="tab" onclick="showTab('conseils')">CONSEILS</div>
            @endif
            @if($espece->periodes)
            <div class="tab" onclick="showTab('periodes')">CYCLES</div>
            @endif
            @if($espece->galerie)
            <div class="tab" onclick="showTab('galerie')">GALERIE</div>
            @endif
        </div>
        
        <!-- Contenu - Présentation (toujours visible) -->
        <div id="presentation" class="tab-content active">
            <!-- Description générale -->
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-info-circle"></i>Description
                </h3>
                <p style="color: #666; line-height: 1.8;">{{ $espece->description_generale }}</p>
            </div>
            
            <!-- Caractéristiques principales -->
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-list-ul"></i>Caractéristiques
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <table class="info-table">
                            <tr>
                                <th>Famille</th>
                                <td>{{ $espece->famille ?? 'Non spécifiée' }}</td>
                            </tr>
                            <tr>
                                <th>Genre</th>
                                <td>{{ $espece->genre ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <th>Origine</th>
                                <td>{{ $espece->origine ?? 'Non spécifiée' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ ucfirst($espece->type) ?? 'Non spécifié' }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <table class="info-table">
                            <tr>
                                <th>Hauteur max</th>
                                <td>{{ $espece->hauteur_max ?? 'Non spécifiée' }}</td>
                            </tr>
                            <tr>
                                <th>Longévité</th>
                                <td>{{ $espece->longevite ?? 'Non spécifiée' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu - Botanique -->
        @if($espece->description_botanique)
        <div id="botanique" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-leaf"></i>Description botanique
                </h3>
                <p style="color: #666; line-height: 1.8;">{{ $espece->description_botanique }}</p>
            </div>
        </div>
        @endif
        
        <!-- Contenu - Utilisations -->
        @if($espece->utilisation)
        <div id="utilisation" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-utensils"></i>Utilisations
                </h3>
                <p style="color: #666; line-height: 1.8;">{{ $espece->utilisation }}</p>
            </div>
        </div>
        @endif
        
        <!-- Contenu - Culture -->
        @if($espece->importance_culturelle)
        <div id="culture" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-users"></i>Importance culturelle
                </h3>
                <p style="color: #666; line-height: 1.8;">{{ $espece->importance_culturelle }}</p>
            </div>
        </div>
        @endif
        
        <!-- Contenu - Conseils -->
        @if($espece->conseils_plantation)
        <div id="conseils" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-seedling"></i>Conseils de plantation
                </h3>
                <div class="row">
                    @php
                        $conseils = is_array($espece->conseils_plantation) 
                            ? $espece->conseils_plantation 
                            : (is_string($espece->conseils_plantation) 
                                ? json_decode($espece->conseils_plantation, true) 
                                : []);
                    @endphp
                    
                    @if(is_array($conseils) && count($conseils) > 0)
                        @foreach($conseils as $key => $value)
                        <div class="col-md-6">
                            <div class="conseil-item">
                                <i class="fas fa-{{ 
                                    $key == 'soleil' ? 'sun' : 
                                    ($key == 'eau' ? 'tint' : 
                                    ($key == 'sol' ? 'mountain' : 
                                    ($key == 'temperature' ? 'thermometer-half' : 
                                    ($key == 'espace' ? 'ruler-combined' : 'leaf')))) 
                                }}"></i>
                                <strong>{{ ucfirst($key) }} :</strong>
                                <span class="float-end">{{ $value }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        <!-- Contenu - Périodes -->
        @if($espece->periodes)
        <div id="periodes" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-calendar-alt"></i>Cycles et périodes
                </h3>
                <div class="row">
                    @php
                        $periodes = is_array($espece->periodes) 
                            ? $espece->periodes 
                            : (is_string($espece->periodes) 
                                ? json_decode($espece->periodes, true) 
                                : []);
                    @endphp
                    
                    @if(is_array($periodes) && count($periodes) > 0)
                        @foreach($periodes as $periode => $date)
                        <div class="col-md-6">
                            <div class="periode-item">
                                <strong>{{ ucfirst(str_replace('_', ' ', $periode)) }} :</strong>
                                <span class="float-end">{{ $date }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        <!-- Contenu - Galerie -->
        @if($espece->galerie)
        <div id="galerie" class="tab-content">
            <div class="info-card">
                <h3 class="section-title-sm">
                    <i class="fas fa-images"></i>Galerie
                </h3>
                <div class="row g-3">
                    @foreach($espece->galerie_urls as $image)
                    <div class="col-md-4 col-6">
                        <img src="{{ $image }}" class="img-fluid" style="height: 150px; width: 100%; object-fit: cover;" alt="Galerie">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Statistiques dans la forêt -->
        <div class="info-card">
            <h3 class="section-title-sm">
                <i class="fas fa-chart-simple"></i>Dans notre forêt
            </h3>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="stat-box">
                        <div class="number">{{ $arbres->count() }}</div>
                        <div class="label">Arbres</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-box">
                        <div class="number">{{ $espece->zones->count() }}</div>
                        <div class="label">Zones</div>
                    </div>
                </div>
            </div>
            
            @if($espece->zones->count() > 0)
            <div style="margin-top: 1.5rem;">
                <h6 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 1rem;">Présent dans les zones :</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($espece->zones as $zone)
                    <a href="{{ route('zones.show', $zone->slug) }}" style="display: inline-block; padding: 0.3rem 1rem; background: var(--premium-light); color: var(--premium-dark); text-decoration: none; border: 1px solid #eee;">
                        {{ $zone->nom }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Arbres de cette espèce -->
        @if($arbres->count() > 0)
        <div class="mt-5">
            <div class="section-title-wrapper">
                <h2 class="section-title">Arbres de cette <strong>espèce</strong></h2>
                <div class="section-divider"></div>
            </div>
            
            <div class="trees-grid">
                <div class="row g-4">
                    @foreach($arbres->take(4) as $arbre)
                    <div class="col-md-6">
                        <a href="{{ route('arbres.show', $arbre->slug) }}" class="text-decoration-none">
                            <div class="tree-card" style="background-image: url('{{ $arbre->photo_url }}');">
                                <div class="tree-card-overlay">
                                    <h4 class="tree-card-title">{{ $arbre->nom }}</h4>
                                    <div class="tree-card-zone">
                                        <i class="fas fa-map-marker-alt"></i> Zone {{ $arbre->zone->nom }}
                                    </div>
                                    <span class="tree-card-link">
                                        DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            @if($arbres->count() > 4)
            <div class="text-center mt-4">
                <a href="{{ route('arbres.index') }}?espece={{ $espece->id }}" class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.8rem 2rem; text-decoration: none; display: inline-block;">
                    VOIR TOUS LES ARBRES
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showTab(tabName) {
        // Cacher tous les contenus
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Désactiver tous les onglets
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Activer l'onglet sélectionné
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.info-card, .tree-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1)';
            observer.observe(el);
        });
    });
    
    function partagerEspece() {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({
                title: "{{ $espece->nom_local }}",
                text: "Découvrez cette espèce dans la forêt urbaine",
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('Lien copié !');
            });
        }
    }
</script>
@endpush