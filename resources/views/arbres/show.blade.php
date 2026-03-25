@extends('layouts.app')

@section('title', $arbre->nom . ' - Forêt Urbaine d\'Aného')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/css/lightgallery.min.css">
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
    
    /* Badges */
    .badge-premium {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
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
    
    /* Galerie d'images */
    .gallery-section {
        margin-top: 2rem;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 1rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .gallery-item:hover {
        border-color: var(--premium-gold);
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        z-index: 10;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-item-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
        padding: 1rem 0.5rem 0.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-item-overlay {
        opacity: 1;
    }
    
    .gallery-item-label {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .gallery-item-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--premium-gold);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-item-icon {
        opacity: 1;
    }
    
    /* Badge "Plus d'images" */
    .gallery-more-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--premium-gold);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        opacity: 0;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }
    
    .gallery-item:hover .gallery-more-badge {
        opacity: 1;
    }
    
    /* Lightgallery personnalisation */
    .lg-backdrop {
        background-color: rgba(0,0,0,0.9);
    }
    
    .lg-toolbar {
        background-color: rgba(0,0,0,0.5);
    }
    
    .lg-actions .lg-next, .lg-actions .lg-prev {
        background-color: var(--premium-gold);
        color: white;
    }
    
    .lg-actions .lg-next:hover, .lg-actions .lg-prev:hover {
        background-color: var(--premium-green);
    }
    
    /* QR Code */
    .qr-code-container {
        background: white;
        padding: 1.5rem;
        display: inline-block;
        box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
        border: 1px solid #eee;
    }
    
    .qr-code-container img {
        width: 150px;
        height: 150px;
    }
    
    /* Planteur photo */
    .planteur-photo {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--premium-gold);
        transition: transform 0.3s;
    }
    
    .planteur-photo:hover {
        transform: scale(1.1);
    }
    
    /* Similar tree card */
    .similar-tree-card {
        background: white;
        border: 1px solid #eee;
        transition: all 0.3s;
        margin-bottom: 0.8rem;
    }
    
    .similar-tree-card:hover {
        transform: translateX(5px);
        border-color: var(--premium-gold);
        box-shadow: 0 5px 15px -5px rgba(0,0,0,0.1);
    }
    
    /* Flèche directionnelle */
    .scroll-arrow {
        font-size: 1rem;
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    
    .scroll-arrow:hover {
        opacity: 1;
    }
    
    /* Espacements */
    .mt-5 {
        margin-top: 2.5rem !important;
    }
    
    .mb-4 {
        margin-bottom: 1.5rem !important;
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
        
        .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
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
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ $arbre->photo_url }}" alt="{{ $arbre->nom }}" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-8 hero-content">
                <div class="mb-4">
                    <span class="badge-premium" style="background-color: {{ $arbre->zone->couleur ?? '#C5A059' }}; margin-right: 0.5rem;">
                        <i class="fas fa-map-marked-alt me-2"></i>Zone {{ $arbre->zone->nom }}
                    </span>
                    <span class="badge-premium" style="background-color: var(--premium-gold);">
                        <i class="fas fa-leaf me-2"></i>{{ $arbre->espece->nom_local }}
                    </span>
                </div>
                
                <h1 class="hero-title float-animation">
                    {{ $arbre->nom }}
                </h1>
                <p class="hero-subtitle">
                    <em>{{ $arbre->espece->nom_scientifique }}</em>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#contenu" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Contenu principal -->
<section id="contenu" class="py-5">
    <div class="container">
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations principales -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-info-circle"></i>Informations principales
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr>
                                    <th>Nom</th>
                                    <td>{{ $arbre->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Espèce</th>
                                    <td>
                                        <a href="{{ route('especes.show', $arbre->espece->slug) }}" style="color: var(--premium-green); text-decoration: none;">
                                            {{ $arbre->espece->nom_local }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Zone</th>
                                    <td>
                                        <a href="{{ route('zones.show', $arbre->zone->slug) }}" style="text-decoration: none;">
                                            <span style="display: inline-block; width: 12px; height: 12px; background-color: {{ $arbre->zone->couleur }}; border-radius: 50%; margin-right: 5px;"></span>
                                            {{ $arbre->zone->nom }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date plantation</th>
                                    <td>{{ $arbre->date_plantation->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr>
                                    <th>Planteur</th>
                                    <td>{{ $arbre->planteur_nom }}</td>
                                </tr>
                                <tr>
                                    <th>Hauteur</th>
                                    <td>{{ $arbre->hauteur ?? 'Non mesurée' }}</td>
                                </tr>
                                <tr>
                                    <th>Circonférence</th>
                                    <td>{{ $arbre->circonference ?? 'Non mesurée' }}</td>
                                </tr>
                                <tr>
                                    <th>Âge</th>
                                    <td>{{ $arbre->age }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($arbre->planteur_photo)
                    <div class="mt-4 d-flex align-items-center" style="background-color: var(--premium-light); padding: 1rem;">
                        <img src="{{ $arbre->planteur_photo_url }}" class="planteur-photo me-3" alt="Planteur">
                        <div>
                            <small style="color: #999;">Photo du planteur</small>
                            <h6 style="margin: 0; color: var(--premium-dark);">{{ $arbre->planteur_nom }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Galerie d'images -->
           <!-- Images supplémentaires de l'arbre -->
@if($arbre->images->count() > 0)
<div class="info-card">
    <h3 class="section-title-sm">
        <i class="fas fa-images"></i>Images de l'arbre
    </h3>
    
    <div class="row g-3">
        @foreach($arbre->images as $image)
        <div class="col-md-4 col-sm-6">
            <div class="card h-100">
                <img src="{{ $image->full_thumbnail_url }}" 
                     class="card-img-top" 
                     alt="{{ $image->titre ?? $arbre->nom }}"
                     style="height: 200px; object-fit: cover; cursor: pointer;"
                     onclick="openImageModal('{{ $image->full_url }}', '{{ $image->titre ?? $arbre->nom }}', '{{ $image->description ?? '' }}')">
                <div class="card-body p-2">
                    <small class="text-muted">{{ $image->type_libelle }}</small>
                    @if($image->titre)
                        <p class="mb-0 small">{{ $image->titre }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal pour agrandir les images -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="">
                <p id="modalDescription" class="mt-3 text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(src, title, description) {
    document.getElementById('modalImage').src = src;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalDescription').textContent = description;
    
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endif
                
                <!-- Description -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-align-left"></i>Description
                    </h3>
                    <p style="color: #666; line-height: 1.8; margin-bottom: 0;">{{ $arbre->description }}</p>
                </div>
                
                <!-- Carte de localisation -->
                <div class="info-card p-0">
                    <div id="arbre-map" style="height: 350px;"></div>
                    <div style="padding: 1.5rem;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 0.8rem;">Localisation</h5>
                                <p style="margin-bottom: 0.3rem;"><i class="fas fa-map-pin" style="color: var(--premium-gold); width: 20px;"></i> Latitude: {{ $arbre->latitude }}° N</p>
                                <p><i class="fas fa-map-pin" style="color: var(--premium-gold); width: 20px;"></i> Longitude: {{ $arbre->longitude }}° E</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn-hover-effect" style="background: var(--premium-green); color: white; border: none; padding: 0.8rem 2rem; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase;" onclick="ouvrirItineraire({{ $arbre->latitude }}, {{ $arbre->longitude }}, '{{ $arbre->nom }}')">
                                    <i class="fas fa-route me-2"></i>ITINÉRAIRE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- QR Code -->
                <div class="info-card text-center">
                    <h3 class="section-title-sm" style="text-align: left;">
                        <i class="fas fa-qrcode"></i>QR Code
                    </h3>
                    
                    @if($arbre->qr_code_url)
                    <div class="qr-code-container">
                        <img src="{{ $arbre->qr_code_url }}" alt="QR Code">
                    </div>
                    <p style="font-size: 0.8rem; color: #999; margin: 1rem 0;">
                        Scannez pour accéder à la fiche
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ $arbre->qr_code_url }}" download="qr-{{ $arbre->slug }}.svg" class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.5rem 1rem; font-size: 0.8rem; text-decoration: none;">
                            <i class="fas fa-download me-1"></i> Télécharger
                        </a>
                        <button class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.5rem 1rem; font-size: 0.8rem;" onclick="partagerArbre()">
                            <i class="fas fa-share-alt me-1"></i> Partager
                        </button>
                    </div>
                    @else
                    <div class="qr-code-container">
                        <i class="fas fa-tree fa-4x" style="color: var(--premium-gold);"></i>
                    </div>
                    <p style="font-size: 0.8rem; color: #999; margin: 1rem 0;">QR code non disponible</p>
                    @endif
                </div>
                
                <!-- Actions rapides -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-bolt"></i>Actions
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <button class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid #ddd; padding: 0.8rem; font-size: 0.85rem; text-align: left;" onclick="partagerArbre()">
                            <i class="fas fa-share-alt me-2" style="color: var(--premium-gold);"></i> Partager
                        </button>
                        <button class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid #ddd; padding: 0.8rem; font-size: 0.85rem; text-align: left;" onclick="window.print()">
                            <i class="fas fa-print me-2" style="color: var(--premium-gold);"></i> Imprimer
                        </button>
                        <button class="btn-hover-effect" style="background: transparent; color: var(--premium-dark); border: 1px solid #ddd; padding: 0.8rem; font-size: 0.85rem; text-align: left;" onclick="ouvrirItineraire({{ $arbre->latitude }}, {{ $arbre->longitude }}, '{{ $arbre->nom }}')">
                            <i class="fas fa-route me-2" style="color: var(--premium-gold);"></i> Itinéraire
                        </button>
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-chart-simple"></i>Statistiques
                    </h3>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="color: #666;"><i class="fas fa-eye me-2" style="color: var(--premium-gold);"></i>Vues</span>
                        <span style="font-weight: 600; color: var(--premium-dark);">{{ $arbre->vues }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="color: #666;"><i class="fas fa-calendar me-2" style="color: var(--premium-gold);"></i>Planté depuis</span>
                        <span style="font-weight: 600; color: var(--premium-dark);">{{ $arbre->date_plantation->diffInDays(now()) }} jours</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #666;"><i class="fas fa-leaf me-2" style="color: var(--premium-gold);"></i>Espèce</span>
                        <span style="font-weight: 600; color: var(--premium-dark);">{{ $arbre->espece->nombre_arbres }} arbres</span>
                    </div>
                </div>
                
                <!-- État de santé (admin only) -->
                @auth
                    @if(auth()->user()->is_admin)
                    <div class="info-card">
                        <h3 class="section-title-sm">
                            <i class="fas fa-heartbeat"></i>État de santé
                        </h3>
                        <div style="text-align: center;">
                            <div style="display: inline-block; padding: 0.5rem 1.5rem; background-color: 
                                {{ $arbre->etat_sante == 'excellent' ? '#d4edda' : 
                                   ($arbre->etat_sante == 'bon' ? '#cce5ff' : 
                                   ($arbre->etat_sante == 'moyen' ? '#fff3cd' : '#f8d7da')) }}; 
                                color: {{ $arbre->etat_sante == 'excellent' ? '#155724' : 
                                   ($arbre->etat_sante == 'bon' ? '#004085' : 
                                   ($arbre->etat_sante == 'moyen' ? '#856404' : '#721c24')) }};">
                                <i class="fas fa-heartbeat me-2"></i>{{ ucfirst($arbre->etat_sante) }}
                            </div>
                            <p style="font-size: 0.8rem; color: #999; margin-top: 1rem;">Visible uniquement par l'administrateur</p>
                        </div>
                    </div>
                    @endif
                @endauth
                
                <!-- Fiche espèce rapide -->
                <div class="info-card">
                    <h3 class="section-title-sm">
                        <i class="fas fa-leaf"></i>À propos de l'espèce
                    </h3>
                    
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <img src="{{ $arbre->espece->photo_url ?? $arbre->photo_url }}" style="width: 100%; height: 120px; object-fit: cover; margin-bottom: 1rem;" alt="{{ $arbre->espece->nom_local }}">
                        <h5 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 0.3rem;">{{ $arbre->espece->nom_local }}</h5>
                        <p style="color: #999; font-style: italic; font-size: 0.85rem;">{{ $arbre->espece->nom_scientifique }}</p>
                    </div>
                    
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">{{ Str::limit($arbre->espece->description_generale, 100) }}</p>
                    
                    <a href="{{ route('especes.show', $arbre->espece->slug) }}" style="display: block; background: transparent; color: var(--premium-dark); border: 1px solid var(--premium-gold); padding: 0.8rem; text-decoration: none; text-align: center; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; transition: all 0.3s;" onmouseover="this.style.background='var(--premium-gold)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='var(--premium-dark)';">
                        VOIR LA FICHE <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Arbres similaires -->
        @if($similaires && $similaires->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h2 style="font-size: 2rem; font-weight: 300; color: var(--premium-dark); text-align: center; margin-bottom: 2rem; position: relative;">
                    Arbres <strong style="font-weight: 700; color: var(--premium-green);">similaires</strong>
                    <span style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 60px; height: 2px; background: var(--premium-gold);"></span>
                </h2>
                
                <div class="row g-3">
                    @foreach($similaires as $similaire)
                    <div class="col-md-4">
                        <a href="{{ route('arbres.show', $similaire->slug) }}" style="text-decoration: none;">
                            <div class="similar-tree-card" style="display: flex; overflow: hidden;">
                                <div style="width: 100px; height: 100px;">
                                    <img src="{{ $similaire->photo_url }}" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $similaire->nom }}">
                                </div>
                                <div style="padding: 0.8rem; flex: 1;">
                                    <h6 style="font-weight: 600; color: var(--premium-dark); margin-bottom: 0.2rem;">{{ $similaire->nom }}</h6>
                                    <p style="font-size: 0.8rem; color: #999; margin-bottom: 0;">
                                        <i class="fas fa-map-marker-alt me-1" style="color: {{ $similaire->zone->couleur }};"></i>{{ $similaire->zone->nom }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/plugins/thumbnail/lg-thumbnail.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/plugins/zoom/lg-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/plugins/fullscreen/lg-fullscreen.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/plugins/share/lg-share.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const arbre = @json($arbre);
        
        // Initialiser la carte
        const map = L.map('arbre-map').setView([arbre.latitude, arbre.longitude], 17);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        const zoneColor = arbre.zone ? arbre.zone.couleur : '#2E7D32';
        
        // Marqueur principal
        const treeIcon = L.divIcon({
            html: `<div style="background-color: ${zoneColor}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.3);"></div>`,
            className: 'tree-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        L.marker([arbre.latitude, arbre.longitude], {icon: treeIcon})
            .addTo(map)
            .bindPopup(`<b>${arbre.nom}</b>`)
            .openPopup();
        
        // Cercle de précision
        L.circle([arbre.latitude, arbre.longitude], {
            color: zoneColor,
            fillColor: zoneColor,
            fillOpacity: 0.1,
            radius: 8
        }).addTo(map);
        
        // Marqueurs des arbres similaires
        @if($similaires && $similaires->count() > 0)
            const similaires = @json($similaires);
            
            similaires.forEach(similaire => {
                if (similaire.latitude && similaire.longitude) {
                    const simIcon = L.divIcon({
                        html: `<div style="background-color: #ccc; width: 18px; height: 18px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.2);"></div>`,
                        className: 'sim-marker',
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    });
                    
                    L.marker([similaire.latitude, similaire.longitude], {icon: simIcon})
                        .addTo(map)
                        .bindPopup(`<b>${similaire.nom}</b>`);
                }
            });
        @endif
        
        // Initialiser la galerie d'images
        @if(isset($arbre->images) && count($arbre->images) > 0)
        const gallery = document.getElementById('arbre-gallery');
        if (gallery) {
            lightGallery(gallery, {
                selector: '.gallery-item',
                plugins: [lgThumbnail, lgZoom, lgFullscreen, lgShare],
                licenseKey: 'D41EC4D7-F004-4C11-9A8D-4592F83C6C91',
                speed: 500,
                thumbWidth: 100,
                thumbHeight: 100,
                thumbMargin: 5,
                actualSize: true,
                download: true,
                share: true,
                zoom: true,
                fullscreen: true
            });
        }
        @endif
    });
    
    function ouvrirItineraire(lat, lng, nom) {
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
    
    function partagerArbre() {
        const url = window.location.href;
        const titre = "{{ $arbre->nom }} - Forêt d'Aného";
        
        if (navigator.share) {
            navigator.share({
                title: titre,
                text: "Découvrez cet arbre dans la forêt urbaine",
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('Lien copié dans le presse-papier !');
            }).catch(() => {
                alert('Lien : ' + url);
            });
        }
    }
</script>
@endpush