@extends('layouts.app')

@section('title', 'Actualités - Forêt Urbaine d\'Aného')

@push('styles')
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
         --logo-gold: #FFC107;  
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
    }
    
    .btn-outline-premium:hover {
        background: var(--premium-gold);
        color: white;
        border-color: var(--premium-gold);
    }
    
    /* Cartes actualités - Version Premium */
    .actualites-grid {
        margin-top: 2rem;
    }
    
    .actualite-card {
        position: relative;
        height: 500px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 2rem;
    }
    
    .actualite-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 40px -15px rgba(0,0,0,0.3);
    }
    
    .actualite-card-overlay {
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
    
    .actualite-card:hover .actualite-card-overlay {
        transform: translateY(-5px);
    }
    
    .actualite-card-category {
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
    
    .actualite-card-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    
    .actualite-card-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .actualite-card-date i {
        color: var(--premium-gold);
    }
    
    .actualite-card-excerpt {
        font-size: 0.95rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        max-width: 80%;
    }
    
    .actualite-card-link {
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
    
    .actualite-card-link:hover {
        gap: 1.2rem;
        border-bottom-color: var(--premium-gold);
    }
    
    .actualite-card-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    .actualite-card-link:hover i {
        transform: translateX(5px);
    }
    
    /* Actualité à la une */
    .featured-actualite {
        background: white;
        border: 1px solid #eee;
        padding: 3rem;
        margin-bottom: 4rem;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
    }
    
    .featured-actualite-content {
        max-width: 600px;
    }
    
    .featured-category {
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
    
    .featured-title {
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--premium-dark);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }
    
    .featured-title strong {
        font-weight: 700;
        color: var(--premium-green);
    }
    
    .featured-meta {
        display: flex;
        gap: 2rem;
        margin-bottom: 1.5rem;
        color: #999;
        font-size: 0.9rem;
    }
    
    .featured-meta i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .featured-description {
        color: #666;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .featured-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    
    .pagination .page-link {
        border: 1px solid #eee;
        padding: 0.5rem 1rem;
        color: var(--premium-dark);
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s;
        background: white;
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
    
    /* Newsletter - MODIFIÉ */
    .newsletter-section {
        background: var(--premium-light);
        padding: 4rem 0 0 0 !important;
        margin-top: 0 !important;
    }
    
    .newsletter-title {
        font-size: 2rem;
        font-weight: 300;
        color: var(--premium-dark);
        margin-bottom: 1rem;
    }
    
    .newsletter-title strong {
        font-weight: 700;
        color: var(--premium-green);
    }
    
    .newsletter-form {
        display: flex;
        gap: 0.5rem;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .newsletter-input {
        flex: 1;
        padding: 0.8rem 1.2rem;
        border: 1px solid #ddd;
        font-size: 0.9rem;
    }
    
    .newsletter-input:focus {
        outline: none;
        border-color: var(--premium-gold);
    }
    
    .newsletter-btn {
        background: var(--premium-green);
        color: white;
        border: none;
        padding: 0 2rem;
        font-weight: 600;
        letter-spacing: 1px;
        cursor: pointer;
        transition: background 0.3s;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    
    .newsletter-btn:hover {
        background: var(--premium-gold);
    }
    
    /* Espacements réduits */
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
        
        .actualite-card {
            height: 450px;
        }
        
        .actualite-card-title {
            font-size: 1.8rem;
        }
        
        .featured-title {
            font-size: 2rem;
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
        
        .actualite-card {
            height: 400px;
        }
        
        .actualite-card-overlay {
            padding: 2rem;
        }
        
        .actualite-card-excerpt {
            max-width: 100%;
        }
        
        .featured-actualite {
            padding: 1.5rem;
        }
        
        .featured-title {
            font-size: 1.8rem;
        }
        
        .section-title-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .newsletter-form {
            flex-direction: column;
        }
        
        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 0 !important;
        }
        
        .newsletter-section {
            padding: 3rem 0 0 0 !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ asset('images/forest-header.jpg') }}" alt="Actualités" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
                <h1 class="hero-title float-animation">
                    Actualités
                </h1>
                <p class="hero-subtitle">
                    Restez informé des dernières nouvelles, événements et projets de la forêt urbaine d'Aného.
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

<!-- Filtres -->
{{--<section id="filtres" style="padding-top: 3rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="filter-box">
            <form method="GET" action="{{ route('actualites.index') }}" id="filter-form">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="filter-label"><i class="fas fa-search"></i>Rechercher</label>
                        <input type="text" name="search" class="form-control" placeholder="Titre..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="filter-label"><i class="fas fa-tags"></i>Catégorie</label>
                        <select name="categorie" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="plantation" {{ request('categorie') == 'plantation' ? 'selected' : '' }}>Plantations</option>
                            <option value="education" {{ request('categorie') == 'education' ? 'selected' : '' }}>Éducation</option>
                            <option value="infrastructure" {{ request('categorie') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                            <option value="partenariat" {{ request('categorie') == 'partenariat' ? 'selected' : '' }}>Partenariats</option>
                            <option value="evenement" {{ request('categorie') == 'evenement' ? 'selected' : '' }}>Événements</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <a href="{{ route('actualites.index') }}" class="btn-outline-premium w-100">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section> --}}

<!-- Liste des actualités -->
<section class="bg-light" style="padding-top: 3rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        @if($actualites->total() > 0)
            <!-- Actualité à la une (première) -->
            @php $premiere = $actualites->first(); @endphp
            @if($premiere)
            <div class="featured-actualite">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <span class="featured-category">{{ $premiere->categorie_formatee }}</span>
                        <h2 class="featured-title">
                            {{ $premiere->titre }}
                        </h2>
                        
                        <div class="featured-meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $premiere->date_publication->format('d F Y') }}</span>
                            <span><i class="fas fa-user"></i> {{ $premiere->auteur_nom }}</span>
                        </div>
                        
                        <p class="featured-description">{{ $premiere->description_courte }}</p>
                        
                        <a href="{{ route('actualites.show', $premiere->slug) }}" class="btn-outline-premium">
                            LIRE L'ARTICLE <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="{{ $premiere->image_url }}" alt="{{ $premiere->titre }}" class="featured-image">
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Autres actualités -->
            <div class="section-title-wrapper">
                <h2 class="section-title">Toutes les <strong>actualités</strong></h2>
                <span class="section-badge">{{ $actualites->total() - 1 }} articles</span>
            </div>
            
            <div class="actualites-grid">
                <div class="row g-4">
                    @foreach($actualites as $actualite)
                    @if($loop->first) @continue @endif
                    <div class="col-md-6">
                        <a href="{{ route('actualites.show', $actualite->slug) }}" class="text-decoration-none">
                            <div class="actualite-card" style="background-image: url('{{ $actualite->image_url }}');">
                                <div class="actualite-card-category">{{ $actualite->categorie_formatee }}</div>
                                
                                <div class="actualite-card-overlay">
                                    <h3 class="actualite-card-title">{{ $actualite->titre }}</h3>
                                    
                                    <div class="actualite-card-date">
                                        <i class="fas fa-calendar-alt"></i> {{ $actualite->date_publication->format('d/m/Y') }}
                                    </div>
                                    
                                    <p class="actualite-card-excerpt">{{ Str::limit($actualite->description_courte, 100) }}</p>
                                    
                                    <span class="actualite-card-link">
                                        LIRE <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center align-items-center gap-3 mt-4">

    {{-- Bouton précédent --}}
    @if ($actualites->onFirstPage())
        <span class="btn-outline-premium disabled">← Précédent</span>
    @else
        <a href="{{ $actualites->previousPageUrl() }}" class="btn-outline-premium">
            ← Précédent
        </a>
    @endif

    {{-- Numéro de page --}}
    <span>
        Page {{ $actualites->currentPage() }} / {{ $actualites->lastPage() }}
    </span>

    {{-- Bouton suivant --}}
    @if ($actualites->hasMorePages())
        <a href="{{ $actualites->nextPageUrl() }}" class="btn-outline-premium">
            Suivant →
        </a>
    @else
        <span class="btn-outline-premium disabled">Suivant →</span>
    @endif

</div>
        @else
            <div class="text-center py-5" style="background: white; border: 1px solid #eee;">
                <i class="fas fa-newspaper fa-4x" style="color: #ddd; margin-bottom: 1rem;"></i>
                <h4 style="color: #999;">Aucune actualité trouvée</h4>
                <p style="color: #ccc;">Il n'y a pas encore d'articles.</p>
            </div>
        @endif
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">
    <div class="container text-center">
        <h2 class="newsletter-title">Restez <strong>informé</strong></h2>
        <p style="color: #666; margin-bottom: 2rem;">Recevez nos dernières actualités directement dans votre boîte mail.</p>
        
        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
            @csrf
            <input type="text" name="nom" class="newsletter-input" placeholder="Votre nom" required>
            <input type="email" name="email" class="newsletter-input" placeholder="Votre email" required>
            <button type="submit" class="newsletter-btn">S'ABONNER</button>
        </form>
        
        <div class="mt-3" style="margin-bottom: 0 !important;">
            <input type="checkbox" id="newsletter-consent" required>
            <label for="newsletter-consent" style="color: #999; font-size: 0.8rem; margin-left: 0.5rem;">
                J'accepte de recevoir les actualités
            </label>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Formulaire newsletter
        const form = document.querySelector('.newsletter-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const consent = document.getElementById('newsletter-consent');
                if (!consent.checked) {
                    e.preventDefault();
                    alert('Veuillez accepter de recevoir les actualités.');
                }
            });
        }
        
        // Animation au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.actualite-card, .featured-actualite').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1)';
            observer.observe(el);
        });
    });
</script>
@endpush