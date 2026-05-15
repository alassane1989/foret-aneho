@extends('layouts.app')

@section('title', 'Catalogue des Espèces - Forêt Urbaine d\'Aného')

@push('styles')
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs premium */
    :root {
        --premium-green: #2E7D32;
        --premium-gold:  #f3ff07;
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
    
    /* Cartes espèces - Version Premium avec image de fond */
    .especes-grid {
        margin-top: 2rem;
    }
    
    .espece-card {
        position: relative;
        height: 500px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 2rem;
    }
    
    .espece-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 40px -15px rgba(0,0,0,0.3);
    }
    
    .espece-card-overlay {
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
    
    .espece-card:hover .espece-card-overlay {
        transform: translateY(-5px);
    }
    
    .espece-card-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        letter-spacing: -0.5px;
    }
    
    .espece-card-scientific {
        font-size: 0.9rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        font-style: italic;
    }
    
    .espece-card-badge {
        display: inline-block;
        padding: 0.3rem 1rem;
        background: var(--premium-gold);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    
    .espece-card-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .espece-card-stats i {
        color: var(--premium-gold);
        margin-right: 0.3rem;
    }
    
    .espece-card-link {
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
    
    .espece-card-link:hover {
        gap: 1.2rem;
        border-bottom-color: var(--premium-gold);
    }
    
    .espece-card-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    .espece-card-link:hover i {
        transform: translateX(5px);
    }
    
    /* Badge origine en haut */
    .origine-badge-card {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--premium-gold);
        color: white;
        padding: 0.3rem 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 2;
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
    
    /* Flèche directionnelle - petite */
    .scroll-arrow {
        font-size: 1rem;
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    
    .scroll-arrow:hover {
        opacity: 1;
    }
    
    /* Pagination */
    .pagination {
        justify-content: center;
        gap: 0.2rem;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .pagination .page-link {
        border: 1px solid #eee;
        padding: 0.4rem 0.8rem !important;
        color: var(--premium-dark);
        font-weight: 500;
        font-size: 0.85rem !important;
        transition: all 0.3s;
    }
    
    .pagination .page-link:hover {
        background: var(--premium-gold);
        border-color: var(--premium-gold);
        color: white;
    }
    
    .pagination .active .page-link {
        background: var(--premium-green);
        border-color: var(--premium-green);
        color: white;
    }
    
    /* Espacements réduits */
    .mt-5 {
        margin-top: 2rem !important;
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
        
        .espece-card {
            height: 450px;
        }
        
        .espece-card-title {
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
        
        .espece-card {
            height: 400px;
        }
        
        .espece-card-overlay {
            padding: 2rem;
        }
        
        .pagination .page-link {
            padding: 0.3rem 0.6rem !important;
            font-size: 0.8rem !important;
        }
        
        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 0 !important;
        }
    }

    /* Empêcher la pagination d'être cachée par le footer */
.bg-light {
    padding-bottom: 3rem !important;
}

.d-flex.justify-content-center.mt-4 {
    margin-bottom: 2rem !important;
    position: relative;
    z-index: 1;
}

footer.footer-custom {
    position: relative;
    z-index: 0;
}
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ asset('images/especes-header.jpg') }}" alt="Espèces" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
                <h1 class="hero-title float-animation">
                    Catalogue des Espèces
                </h1>
                <p class="hero-subtitle">
                    Découvrez la richesse botanique de notre forêt à travers les {{ $especes->total() }} espèces répertoriées.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#filtres" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Filtres et recherche -->
{{--<section id="filtres" class="py-5" style="padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="filter-box">
            <form method="GET" action="{{ route('especes.index') }}" id="filter-form">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="filter-label"><i class="fas fa-search"></i>Rechercher</label>
                        <input type="text" name="search" class="form-control" placeholder="Nom scientifique ou local..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="filter-label"><i class="fas fa-tags"></i>Catégorie</label>
                        <select name="categorie" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes les catégories</option>
                            <option value="fruitier" {{ request('categorie') == 'fruitier' ? 'selected' : '' }}>Fruitiers</option>
                            <option value="ornemental" {{ request('categorie') == 'ornemental' ? 'selected' : '' }}>Ornementaux</option>
                            <option value="foret" {{ request('categorie') == 'foret' ? 'selected' : '' }}>Forêt</option>
                            <option value="sacre" {{ request('categorie') == 'sacre' ? 'selected' : '' }}>Sacrés</option>
                            <option value="medicinal" {{ request('categorie') == 'medicinal' ? 'selected' : '' }}>Médicinaux</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="filter-label"><i class="fas fa-globe"></i>Origine</label>
                        <select name="origine" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="locale" {{ request('origine') == 'locale' ? 'selected' : '' }}>Locales</option>
                            <option value="introduite" {{ request('origine') == 'introduite' ? 'selected' : '' }}>Introduites</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('especes.index') }}" class="btn-outline-premium">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>--}}

<!-- Liste des espèces - 2 par ligne avec image de fond -->
<section class="bg-light" style="padding-top: 3rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Catalogue des <strong>espèces</strong></h2>
            <span class="section-badge">Page {{ $especes->currentPage() }} / {{ $especes->lastPage() }}</span>
        </div>
        
        <div class="especes-grid">
            <div class="row g-4">
                @forelse($especes as $espece)
                <div class="col-md-6">
                    <a href="{{ route('especes.show', $espece->slug) }}" class="text-decoration-none">
                        <div class="espece-card" style="background-image: url('{{ $espece->image_url }}');">
                            <div class="origine-badge-card">
                                {{ $espece->est_locale ? 'LOCALE' : 'INTRODUITE' }}
                            </div>
                            
                            <div class="espece-card-overlay">
                                <div class="espece-card-badge">{{ $espece->categorie_formatee }}</div>
                                <h3 class="espece-card-title">{{ $espece->nom_local }}</h3>
                                <p class="espece-card-scientific">{{ $espece->nom_scientifique }}</p>
                                
                                <div class="espece-card-stats">
                                    <span><i class="fas fa-tree"></i> {{ $espece->nombre_arbres }} arbres</span>
                                </div>
                                
                                <span class="espece-card-link">
                                    DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12">
                    <div style="text-align: center; padding: 3rem; background: var(--premium-light);">
                        <i class="fas fa-leaf fa-3x" style="color: #ddd; margin-bottom: 1rem;"></i>
                        <h4 style="color: #999;">Aucune espèce trouvée</h4>
                        <p style="color: #ccc;">Essayez de modifier vos critères de recherche.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Pagination 
        <div class="mt-4" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">-->
                <!-- Pagination -->
<div class="d-flex justify-content-center align-items-center gap-3 mt-4">

    {{-- Bouton précédent --}}
    @if ($especes->onFirstPage())
        <span class="btn-outline-premium disabled">← Précédent</span>
    @else
        <a href="{{ $especes->previousPageUrl() }}" class="btn-outline-premium">
            ← Précédent
        </a>
    @endif

    {{-- Numéro de page --}}
    <span>
        Page {{ $especes->currentPage() }} / {{ $especes->lastPage() }}
    </span>

    {{-- Bouton suivant --}}
    @if ($especes->hasMorePages())
        <a href="{{ $especes->nextPageUrl() }}" class="btn-outline-premium">
            Suivant →
        </a>
    @else
        <span class="btn-outline-premium disabled">Suivant →</span>
    @endif

</div>
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
    padding-top: 3rem !important;
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

/* Forcer la suppression des marges Bootstrap */
.mb-0, .mb-1, .mb-2, .mb-3, .mb-4, .mb-5 {
    margin-bottom: 0 !important;
}

.mt-0, .mt-1, .mt-2, .mt-3, .mt-4, .mt-5 {
    margin-top: 0 !important;
}

.pb-0, .pb-1, .pb-2, .pb-3, .pb-4, .pb-5 {
    padding-bottom: 0 !important;
}
</style>
@endsection

@push('scripts')
<script>
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
        
        document.querySelectorAll('.espece-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1)';
            observer.observe(el);
        });
    });
</script>
@endpush