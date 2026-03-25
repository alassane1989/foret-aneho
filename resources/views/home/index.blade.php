@extends('layouts.app')

@section('title', 'Forêt Urbaine d\'Aného - Accueil')

@push('styles')
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs basées sur le logo */
    :root {
        --logo-green: #2E7D32;      /* Vert du logo */
        --logo-blue: #1976D2;        /* Bleu du logo */
        --logo-gold: #f3ff07;         /* Jaune du logo */
        --logo-dark: #1B4D3E;         /* Vert foncé */
        --logo-light: #F8F9FA;        /* Fond clair */
    }
    
    /* Styles généraux */
    * {
        font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    
    .hover-zoom {
        overflow: hidden;
    }
    .hover-zoom img {
        transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .hover-zoom:hover img {
        transform: scale(1.05);
    }
    
    /* Effets de boutons professionnels avec les couleurs du logo */
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
    
    /* Bouton principal du slider - BLEU */
    .hero-btn {
        background: var(--logo-blue);
        color: white;
        border: none;
        padding: 1rem 3rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-decoration: none;
        transition: all 0.4s;
        display: inline-block;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(25, 118, 210, 0.2);
    }
    
    .hero-btn:hover {
        background: var(--logo-green);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(46, 125, 50, 0.3);
    }
    
    /* Bouton outline - JAUNE (pour "TOUTES LES ZONES") */
    .btn-outline-premium {
        background: transparent;
        color: var(--logo-gold);
        border: 2px solid var(--logo-gold);
        padding: 1rem 3rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.4s;
        display: inline-block;
        border-radius: 4px;
    }
    
    .btn-outline-premium:hover {
        background: var(--logo-gold);
        color: var(--logo-dark);
        border-color: var(--logo-gold);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 193, 7, 0.25);
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }
    
    .float-animation {
        animation: float 4s ease-in-out infinite;
    }
    
    /* Styles pour le slider */
    .hero-slider {
        height: 90vh;
        min-height: 700px;
        position: relative;
        overflow: hidden;
        margin-top: -20px;
    }
    
    .hero-slider .carousel-inner,
    .hero-slider .carousel-item {
        height: 100%;
    }
    
    .hero-slider .carousel-item {
        position: relative;
    }
    
    .hero-slider .carousel-item img {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    
    .hero-slider .carousel-item:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg,/* rgba(25, 118, 210, 0.8) 0%*/, rgba(46, 125, 32, 0.3) 100%);
        z-index: 1;
    }
    
    .hero-slider .carousel-caption {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        bottom: auto;
        left: 10%;
        right: auto;
        text-align: left;
        max-width: 600px;
        z-index: 2;
        padding: 0;
    }
    
    .hero-slider .carousel-caption h1 {
        font-size: 4rem;
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-slider .carousel-caption p {
        font-size: 1.2rem;
        font-weight: 300;
        letter-spacing: 0.5px;
        max-width: 600px;
        margin-bottom: 2.5rem;
        opacity: 0.9;
        line-height: 1.8;
        color: white;
    }
    
    .hero-slider .carousel-indicators {
        bottom: 30px;
        z-index: 3;
    }
    
    .hero-slider .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 8px;
        background-color: var(--logo-gold);
        opacity: 0.5;
    }
    
    .hero-slider .carousel-indicators button.active {
        opacity: 1;
        background-color: var(--logo-gold);
    }
    
    .hero-slider .carousel-control-prev,
    .hero-slider .carousel-control-next {
        width: 50px;
        height: 50px;
        background: var(--logo-blue);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 3;
    }
    
    .hero-slider:hover .carousel-control-prev,
    .hero-slider:hover .carousel-control-next {
        opacity: 0.8;
    }
    
    .carousel-control-prev {
        left: 30px;
    }
    
    .carousel-control-next {
        right: 30px;
    }
    
    /* Section zones */
    .zones-section {
        padding: 6rem 0;
        background: var(--logo-light);
    }
    
    .section-title-wrapper {
        text-align: center;
        margin-bottom: 4rem;
    }
    
    .section-title {
        font-size: 2.8rem;
        font-weight: 300;
        color: var(--logo-dark);
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }
    
    .section-title strong {
        font-weight: 700;
        color: var(--logo-green);
    }
    
    .section-divider {
        width: 80px;
        height: 2px;
        background: var(--logo-gold);
        margin: 1.5rem auto 0;
    }
    
    .zone-card {
        position: relative;
        height: 500px;
        overflow: hidden;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .zone-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 40px -15px rgba(25, 118, 210, 0.3);
    }
    
    .zone-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 3rem;
        background: linear-gradient(to top, rgba(25, 118, 210, 0.8), transparent);
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
    
    /* Bouton DÉCOUVRIR sur les zones - JAUNE */
    .zone-card-link {
        color: var(--logo-gold);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
        padding-bottom: 5px;
    }
    
    .zone-card-link:hover {
        gap: 1.2rem;
        color: white;
        border-bottom-color: var(--logo-gold);
        transform: translateY(-2px);
    }
    
    .zone-card-link i {
        font-size: 0.8rem;
        transition: transform 0.3s;
        color: var(--logo-gold);
    }
    
    .zone-card-link:hover i {
        transform: translateX(8px);
        color: white;
    }
    
    /* Newsletter - MODIFIÉ : padding-bottom supprimé */
    .newsletter-section {
        padding: 5rem 0 0 0;  /* padding-top: 5rem, padding-bottom: 0 */
        background: var(--logo-light);
    }
    
    .newsletter-title {
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--logo-dark);
        margin-bottom: 1rem;
    }
    
    .newsletter-title strong {
        font-weight: 700;
        color: var(--logo-blue);
    }
    
    .newsletter-text {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    .newsletter-form {
        display: flex;
        gap: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .newsletter-input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        transition: all 0.3s;
        border-radius: 4px;
    }
    
    .newsletter-input:focus {
        outline: none;
        border-color: var(--logo-gold);
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
    }
    
    /* Bouton newsletter - VERT */
    .newsletter-btn {
        background: var(--logo-green);
        color: white;
        border: none;
        padding: 0 2.5rem;
        font-weight: 600;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        text-transform: uppercase;
        font-size: 0.9rem;
        border-radius: 4px;
    }
    
    .newsletter-btn:hover {
        background: var(--logo-blue);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(25, 118, 210, 0.3);
    }
    
    .newsletter-check {
        margin-top: 1.5rem;
        font-size: 0.85rem;
        color: #666;
    }
    
    .newsletter-check input {
        margin-right: 0.5rem;
        accent-color: var(--logo-green);
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 1s ease forwards;
    }
    
    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-slider .carousel-caption h1 {
            font-size: 3rem;
        }
        
        .zone-card {
            height: 400px;
        }
        
        .zone-card-text {
            max-width: 100%;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-slider {
            height: 80vh;
            min-height: 600px;
        }
        
        .hero-slider .carousel-caption h1 {
            font-size: 2.5rem;
        }
        
        .hero-slider .carousel-caption {
            left: 5%;
            right: 5%;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            display: none;
        }
        
        .zone-card {
            height: 350px;
        }
        
        .zone-card-title {
            font-size: 2rem;
        }
        
        .newsletter-form {
            flex-direction: column;
            padding: 0 1rem;
        }
        
        .newsletter-btn {
            padding: 1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        /* Ajustement responsive pour la newsletter */
        .newsletter-section {
            padding: 3rem 0 0 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium avec Slider 3 Images -->
<div id="heroSlider" class="carousel slide hero-slider" data-bs-ride="carousel" data-bs-interval="5000">
    <!-- Indicateurs (points) -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    
    <!-- Images du slider -->
    <div class="carousel-inner">
        <!-- Slide 1 - Forêt -->
        <div class="carousel-item active">
            <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100" alt="Forêt d'Aného">
            <div class="carousel-caption d-block">
                <h1 class="animate-fadeInUp">Forêt Urbaine d'Aného</h1>
                <p class="animate-fadeInUp delay-1">Un projet écologique de la Commune des Lacs 1 pour préserver et valoriser notre patrimoine vert.</p>
                <a href="{{ route('zones.index') }}" class="hero-btn animate-fadeInUp delay-2">DÉCOUVRIR</a>
            </div>
        </div>
        
        <!-- Slide 2 - Zones -->
        <div class="carousel-item">
            <img src="{{ asset('images/slide2.jpg') }}" class="d-block w-100" alt="Zones de la forêt">
            <div class="carousel-caption d-block">
                <h1 class="animate-fadeInUp">Explorez nos zones</h1>
                <p class="animate-fadeInUp delay-1">Cinq univers uniques au cœur de la nature, chacun avec sa propre identité et sa biodiversité.</p>
                <a href="{{ route('zones.index') }}" class="hero-btn animate-fadeInUp delay-2">DÉCOUVRIR</a>
            </div>
        </div>
        
        <!-- Slide 3 - Arbres -->
        <div class="carousel-item">
            <img src="{{ asset('images/slide3.jpg') }}" class="d-block w-100" alt="Arbres de la forêt">
            <div class="carousel-caption d-block">
                <h1 class="animate-fadeInUp">Découvrez nos arbres</h1>
                <p class="animate-fadeInUp delay-1">Des espèces variées et remarquables à observer au fil des saisons, chacune avec son histoire.</p>
                <a href="{{ route('arbres.index') }}" class="hero-btn animate-fadeInUp delay-2">EXPLORER</a>
            </div>
        </div>
    </div>
    
    <!-- Flèches de navigation (gauche/droite) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
    
    <!-- Flèche de scroll vers le bas -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5" style="z-index: 3;">
        <a href="#zones" class="text-white" style="opacity: 0.7;">
            <i class="fas fa-chevron-down fa-2x"></i>
        </a>
    </div>
</div>

<!-- Section Zones Premium - avec images uniques pour chaque zone -->
<section id="zones" class="zones-section">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Découvrez <strong>nos zones</strong></h2>
            <p style="color: #666; max-width: 600px; margin: 0 auto;">Cinq univers uniques au cœur de la forêt urbaine</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row g-4">
            @foreach(\App\Models\Zone::all() as $zone)
            <div class="col-md-6">
                <a href="{{ route('zones.show', $zone->slug) }}" class="text-decoration-none">
                    <div class="zone-card" style="background-image: url('{{ $zone->image_url }}');">
                        <div class="zone-card-overlay">
                            <h3 class="zone-card-title">{{ $zone->nom }}</h3>
                            <p class="zone-card-text">{{ $zone->description_courte }}</p>
                            <span class="zone-card-link">
                                DÉCOUVRIR <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('zones.index') }}" class="btn-outline-premium">
                TOUTES LES ZONES
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Premium - MODIFIÉ : espace du bas supprimé -->
<section class="newsletter-section" style="margin-bottom: 0; padding-bottom: 0;">
    <div class="container text-center">
        <h2 class="newsletter-title">
            Restez <strong>informé</strong>
        </h2>
        <p class="newsletter-text">
            Recevez nos dernières actualités directement dans votre boîte mail.
        </p>
        
        <form method="POST" action="{{ route('newsletter.subscribe') }}" id="newsletterForm">
            @csrf
            <div class="newsletter-form">
                <input type="text" name="nom" class="newsletter-input" placeholder="Votre nom" required>
                <input type="email" name="email" class="newsletter-input" placeholder="Votre email" required>
                <button type="submit" class="newsletter-btn">S'ABONNER</button>
            </div>
            
            <div class="newsletter-check">
                <input type="checkbox" id="newsletter-consent" required>
                <label for="newsletter-consent">J'accepte de recevoir les actualités</label>
            </div>
        </form>
    </div>
</section>

<style>
/* Surcharge des styles du layout pour supprimer les espaces */
.main-content {
    min-height: auto !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.footer-custom {
    margin-top: 0 !important;  /* Supprime le margin-top du footer */
}

/* S'assurer qu'il n'y a pas d'espace entre la newsletter et le footer */
.newsletter-section {
    margin-bottom: 0 !important;
}

.newsletter-section + footer,
.newsletter-section + .footer-custom {
    margin-top: 0 !important;
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Formulaire newsletter
        const form = document.getElementById('newsletterForm');
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
        
        document.querySelectorAll('.zone-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1)';
            observer.observe(el);
        });
    });
</script>
@endpush