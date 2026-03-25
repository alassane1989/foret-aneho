@extends('layouts.app')

@section('title', 'Contact - Forêt Urbaine d\'Aného')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Polices premium */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    /* Variables de couleurs premium */
    :root {
        --premium-green: #2E7D32;
        --premium-gold: #f3ff07; ;
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
    
    /* Formulaire */
    .contact-form {
        background: white;
        border: 1px solid #eee;
        padding: 3rem;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 300;
        color: var(--premium-dark);
        margin-bottom: 2rem;
    }
    
    .form-title i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--premium-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
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
    
    .btn-submit {
        background: var(--premium-green);
        color: white;
        border: none;
        padding: 0.8rem 3rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .btn-submit:hover {
        background: var(--premium-gold);
    }
    
    /* Cartes d'information */
    .info-card {
        background: white;
        border: 1px solid #eee;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s;
    }
    
    .info-card:hover {
        border-color: var(--premium-gold);
    }
    
    .info-card-icon {
        width: 60px;
        height: 60px;
        background: var(--premium-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--premium-gold);
        font-size: 1.8rem;
    }
    
    .info-card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 1rem;
    }
    
    .info-card-content {
        color: #666;
        line-height: 1.8;
    }
    
    .info-ligne {
        display: flex;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .info-ligne:last-child {
        border-bottom: none;
    }
    
    .info-ligne i {
        color: var(--premium-gold);
        width: 30px;
        font-size: 1.1rem;
    }
    
    .social-icons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .social-icon:hover {
        transform: translateY(-3px);
    }
    
    .social-facebook { background: #3b5998; }
    .social-twitter { background: #1da1f2; }
    .social-instagram { background: #e4405f; }
    .social-youtube { background: #ff0000; }
    
    /* Carte */
    .map-container {
        border: 1px solid #eee;
        overflow: hidden;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
    }
    
    #contact-map {
        height: 400px;
        width: 100%;
    }
    
    /* Conseils visite */
    .tips-card {
        background: white;
        border: 1px solid #eee;
        padding: 2rem;
        height: 100%;
    }
    
    .tips-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 1.5rem;
    }
    
    .tips-title i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: #666;
    }
    
    .tips-list i {
        color: var(--premium-gold);
        margin-right: 1rem;
        width: 20px;
    }
    
    /* FAQ */
    .faq-section {
        background: var(--premium-light);
        padding: 4rem 0 0 0 !important;
        margin-bottom: 0 !important;
    }
    
    .faq-item {
        background: white;
        border: 1px solid #eee;
        margin-bottom: 1rem;
    }
    
    .faq-question {
        padding: 1.2rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        color: var(--premium-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s;
    }
    
    .faq-question:hover {
        background: var(--premium-light);
    }
    
    .faq-question i {
        color: var(--premium-gold);
        transition: transform 0.3s;
    }
    
    .faq-question[aria-expanded="true"] i {
        transform: rotate(90deg);
    }
    
    .faq-answer {
        padding: 1.5rem;
        border-top: 1px solid #eee;
        color: #666;
        line-height: 1.8;
    }
    
    /* Alert */
    .alert-premium {
        background: var(--premium-light);
        border-left: 3px solid var(--premium-gold);
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .alert-premium i {
        color: var(--premium-gold);
        font-size: 2rem;
    }
    
    .alert-premium-content {
        color: var(--premium-dark);
    }
    
    .alert-premium strong {
        display: block;
        font-weight: 600;
        margin-bottom: 0.2rem;
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
    
    /* Espacements - MODIFIÉS */
    .py-5 {
        padding-top: 4rem !important;
        padding-bottom: 0 !important;
    }
    
    .mt-5 {
        margin-top: 2rem !important;
        margin-bottom: 0 !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
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
        
        .contact-form {
            padding: 1.5rem;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 0 !important;
        }
        
        .faq-section {
            padding: 3rem 0 0 0 !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ asset('images/forest-header.jpg') }}" alt="Contact" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
                <h1 class="hero-title float-animation">
                    Contact
                </h1>
                <p class="hero-subtitle">
                    Nous sommes à votre écoute pour toute question, suggestion ou demande concernant la forêt urbaine d'Aného.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#formulaire" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Message de succès -->
@if(session('success'))
<div class="container mt-4" style="margin-bottom: 0 !important;">
    <div class="alert-premium">
        <i class="fas fa-check-circle"></i>
        <div class="alert-premium-content">
            <strong>Message envoyé !</strong>
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

<!-- Section Contact -->
<section id="formulaire" style="padding-top: 4rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <div class="row g-5">
            <!-- Formulaire -->
            <div class="col-lg-7">
                <div class="contact-form">
                    <h2 class="form-title">
                        <i class="fas fa-paper-plane"></i> Envoyez-nous un message
                    </h2>
                    
                    <form method="POST" action="{{ route('contact.send') }}" id="contactForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Jean Dupont" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="sujet" class="form-label">Sujet</label>
                            <select class="form-select @error('sujet') is-invalid @enderror" id="sujet" name="sujet" required>
                                <option value="" selected disabled>Choisissez un sujet</option>
                                <option value="info" {{ old('sujet') == 'info' ? 'selected' : '' }}>Information</option>
                                <option value="visite" {{ old('sujet') == 'visite' ? 'selected' : '' }}>Visite</option>
                                <option value="participation" {{ old('sujet') == 'participation' ? 'selected' : '' }}>Participation</option>
                                <option value="projet" {{ old('sujet') == 'projet' ? 'selected' : '' }}>Projet</option>
                                <option value="partenariat" {{ old('sujet') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                                <option value="autre" {{ old('sujet') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('sujet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Votre message..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="consent" name="consent" required {{ old('consent') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="consent">
                                J'accepte que mes données soient utilisées pour traiter ma demande
                            </label>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn-submit">
                                ENVOYER <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Informations -->
            <div class="col-lg-5">
                <!-- Adresse -->
                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="info-card-title">Adresse</h3>
                    <div class="info-card-content">
                        <div class="info-ligne">
                            <i class="fas fa-building"></i>
                            <span>Mairie d'Aného</span>
                        </div>
                        <div class="info-ligne">
                            <i class="fas fa-map-pin"></i>
                            <span>Commune des Lacs 1</span>
                        </div>
                        <div class="info-ligne">
                            <i class="fas fa-globe"></i>
                            <span>Aného, Togo</span>
                        </div>
                    </div>
                    
                    <div class="social-icons">
                        <a href="#" class="social-icon social-facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon social-twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon social-instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon social-youtube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="info-card-title">Contact</h3>
                    <div class="info-card-content">
                        <div class="info-ligne">
                            <i class="fas fa-phone"></i>
                            <span>+228 22 23 45 67</span>
                        </div>
                        <div class="info-ligne">
                            <i class="fas fa-mobile-alt"></i>
                            <span>+228 90 12 34 56</span>
                        </div>
                        <div class="info-ligne">
                            <i class="fas fa-envelope"></i>
                            <span>contact@foret-aneho.tg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Carte -->
<section class="bg-light" style="padding-top: 4rem; padding-bottom: 0 !important; margin-bottom: 0 !important;">
    <div class="container">
        <h2 style="font-size: 2rem; font-weight: 300; color: var(--premium-dark); text-align: center; margin-bottom: 1rem;">
            Nous <strong style="font-weight: 700; color: var(--premium-green);">trouver</strong>
        </h2>
        <p style="text-align: center; color: #999; margin-bottom: 3rem;">La forêt urbaine est située à Aného, à 45 minutes de Lomé.</p>
        
        <div class="map-container">
            <div id="contact-map"></div>
        </div>
        
        <div class="row mt-5 g-4" style="margin-bottom: 0 !important;">
            <div class="col-md-6">
                <div class="tips-card">
                    <h3 class="tips-title"><i class="fas fa-map-signs"></i> Points d'accès</h3>
                    <div class="row">
                        <div class="col-6">
                            <ul class="tips-list">
                                <li><i class="fas fa-circle" style="color: #4CAF50; font-size: 0.6rem;"></i> GLIDJI : Rue des Baobabs</li>
                                <li><i class="fas fa-circle" style="color: #2196F3; font-size: 0.6rem;"></i> LOLAN : Av. des Manguiers</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="tips-list">
                                <li><i class="fas fa-circle" style="color: #FF9800; font-size: 0.6rem;"></i> NLESSI : Chemin des Acacias</li>
                                <li><i class="fas fa-circle" style="color: #9C27B0; font-size: 0.6rem;"></i> YVELINES : Bd des Flamboyants</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="tips-card">
                    <h3 class="tips-title"><i class="fas fa-info-circle"></i> Conseils visite</h3>
                    <ul class="tips-list">
                        <li><i class="fas fa-check"></i> Eau et chaussures de marche</li>
                        <li><i class="fas fa-check"></i> Respectez les sentiers</li>
                        <li><i class="fas fa-check"></i> Ne cueillez pas les plantes</li>
                        <li><i class="fas fa-check"></i> Utilisez les poubelles</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq-section" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">
    <div class="container">
        <h2 style="font-size: 2rem; font-weight: 300; color: var(--premium-dark); text-align: center; margin-bottom: 3rem;">
            Questions <strong style="font-weight: 700; color: var(--premium-green);">fréquentes</strong>
        </h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-item">
                    <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <span>Comment organiser une visite guidée ?</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div id="faq1" class="collapse">
                        <div class="faq-answer">
                            Pour organiser une visite guidée, sélectionnez "Visite" dans le formulaire. Précisez la date souhaitée et le nombre de personnes. Nous vous répondrons dans les 48h.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq2">
                        <span>Puis-je participer aux plantations ?</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div id="faq2" class="collapse">
                        <div class="faq-answer">
                            Oui, nous accueillons des bénévoles. Consultez nos actualités pour les prochaines campagnes.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq3">
                        <span>Accès personnes à mobilité réduite ?</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div id="faq3" class="collapse">
                        <div class="faq-answer">
                            La zone LOLAN est accessible. Contactez-nous pour organiser une visite adaptée.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq4">
                        <span>Parking disponible ?</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div id="faq4" class="collapse">
                        <div class="faq-answer">
                            Parking gratuit à l'entrée principale (Rue de la Forêt).
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center" style="margin-top: 2rem; margin-bottom: 0 !important; padding-bottom: 0 !important;">
            <p style="color: #666;">Vous ne trouvez pas votre réponse ?</p>
            <a href="#formulaire" class="btn-submit" style="display: inline-block; text-decoration: none;">
                CONTACTEZ-NOUS
            </a>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser la carte
        const map = L.map('contact-map').setView([6.2276, 1.5968], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        // Coordonnées des entrées
        const entries = [
            { name: "Mairie d'Aného", lat: 6.2276, lng: 1.5968, color: '#2E7D32' },
            { name: "Entrée GLIDJI", lat: 6.233, lng: 1.588, color: '#4CAF50' },
            { name: "Entrée LOLAN", lat: 6.228, lng: 1.598, color: '#2196F3' },
            { name: "Entrée NLESS", lat: 6.240, lng: 1.577, color: '#FF9800' },
            { name: "Entrée YVELINES", lat: 6.219, lng: 1.604, color: '#9C27B0' }
        ];
        
        // Ajouter les marqueurs
        entries.forEach(entry => {
            const marker = L.circleMarker([entry.lat, entry.lng], {
                radius: 8,
                color: entry.color,
                fillColor: entry.color,
                fillOpacity: 1,
                weight: 2
            }).addTo(map);
            
            marker.bindPopup(`<b>${entry.name}</b>`);
        });
        
        // Zone forêt
        L.polygon([
            [6.230, 1.590], [6.232, 1.592], [6.234, 1.588], [6.238, 1.586],
            [6.240, 1.580], [6.244, 1.578], [6.242, 1.576], [6.238, 1.576],
            [6.235, 1.582], [6.233, 1.588], [6.230, 1.590]
        ], {
            color: '#2E7D32',
            fillColor: '#4CAF50',
            fillOpacity: 0.1,
            weight: 1
        }).addTo(map).bindPopup('<b>Forêt Urbaine d\'Aného</b>');
        
        // Ajuster la vue
        const bounds = L.latLngBounds(entries.map(e => [e.lat, e.lng]));
        map.fitBounds(bounds.pad(0.2));
    });
    
    // Validation formulaire
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        const consent = document.getElementById('consent');
        if (!consent.checked) {
            e.preventDefault();
            alert('Veuillez accepter le traitement de vos données.');
        }
    });
</script>
@endpush