@extends('layouts.app')

@section('title', $actualite->titre . ' - Forêt Urbaine d\'Aného')

@push('styles')
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
        height: 80vh;
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
        display: flex;
        align-items: center;
        height: 100%;
    }
    
    .hero-title {
        font-size: 4rem;
        font-weight: 700;
        letter-spacing: -1px;
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        max-width: 700px;
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
        font-size: 0.9rem;
    }
    
    .breadcrumb-custom a:hover {
        color: var(--premium-gold);
    }
    
    .breadcrumb-custom .active {
        color: white;
        font-size: 0.9rem;
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
        margin-bottom: 1.5rem;
    }
    
    /* Meta informations */
    .meta-info {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        color: rgba(255,255,255,0.8);
        font-size: 0.95rem;
    }
    
    .meta-info i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    /* Article content */
    .article-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 0;
    }
    
    .article-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #444;
    }
    
    .article-content h2 {
        font-size: 2rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.8rem;
    }
    
    .article-content h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--premium-gold);
    }
    
    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 500;
        color: var(--premium-dark);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .article-content p {
        margin-bottom: 1.5rem;
    }
    
    .article-content ul, .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    
    .article-content li {
        margin-bottom: 0.5rem;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        margin: 2rem 0;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.2);
    }
    
    .article-content blockquote {
        background: var(--premium-light);
        border-left: 3px solid var(--premium-gold);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
        font-size: 1.1rem;
    }
    
    /* Table des matières */
    .table-of-contents {
        background: white;
        border: 1px solid #eee;
        padding: 2rem;
        margin-bottom: 3rem;
    }
    
    .table-of-contents h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 1rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .table-of-contents ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .table-of-contents li {
        margin-bottom: 0.5rem;
    }
    
    .table-of-contents a {
        color: #666;
        text-decoration: none;
        display: inline-block;
        transition: color 0.3s, transform 0.3s;
    }
    
    .table-of-contents a:hover {
        color: var(--premium-gold);
        transform: translateX(5px);
    }
    
    .table-of-contents a i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Tags */
    .tags-container {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }
    
    .tag {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: var(--premium-light);
        color: #666;
        text-decoration: none;
        font-size: 0.85rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
        border: 1px solid transparent;
    }
    
    .tag:hover {
        background: var(--premium-gold);
        color: white;
        border-color: var(--premium-gold);
    }
    
    .tag i {
        margin-right: 0.3rem;
        font-size: 0.75rem;
    }
    
    /* Galerie */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        aspect-ratio: 1;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    .gallery-item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .gallery-item:hover .gallery-item-overlay {
        opacity: 1;
    }
    
    .gallery-item-overlay i {
        color: white;
        font-size: 2rem;
    }
    
    /* Navigation entre articles */
    .articles-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        text-decoration: none;
        color: var(--premium-dark);
        font-weight: 500;
        transition: gap 0.3s;
        max-width: 300px;
    }
    
    .nav-link:hover {
        gap: 1.2rem;
        color: var(--premium-gold);
    }
    
    .nav-link.prev:hover {
        gap: 0.4rem;
    }
    
    .nav-link i {
        color: var(--premium-gold);
        font-size: 0.9rem;
        transition: transform 0.3s;
    }
    
    .nav-link.prev:hover i {
        transform: translateX(-3px);
    }
    
    .nav-link.next:hover i {
        transform: translateX(3px);
    }
    
    .nav-link span {
        font-size: 0.9rem;
    }
    
    .nav-link strong {
        display: block;
        font-size: 0.8rem;
        color: #999;
        font-weight: 400;
        margin-bottom: 0.2rem;
    }
    
    /* Sidebar */
    .sidebar {
        position: sticky;
        top: 100px;
    }
    
    .sidebar-card {
        background: white;
        border: 1px solid #eee;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .sidebar-card h5 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .sidebar-card h5 i {
        color: var(--premium-gold);
        margin-right: 0.5rem;
    }
    
    /* Share buttons */
    .share-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .share-btn {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid transparent;
    }
    
    .share-btn:hover {
        transform: translateY(-3px);
        opacity: 0.9;
    }
    
    .share-facebook { background: #3b5998; }
    .share-twitter { background: #1da1f2; }
    .share-whatsapp { background: #25d366; }
    .share-linkedin { background: #0077b5; }
    
    /* Auteur */
    .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1rem;
        border: 3px solid var(--premium-gold);
    }
    
    .author-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 0.3rem;
    }
    
    .author-role {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    
    .author-bio {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .author-social {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .author-social a {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        color: #999;
        transition: all 0.3s;
    }
    
    .author-social a:hover {
        background: var(--premium-gold);
        border-color: var(--premium-gold);
        color: white;
    }
    
    /* Articles récents */
    .recent-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s;
    }
    
    .recent-item:hover {
        transform: translateX(5px);
    }
    
    .recent-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
    }
    
    .recent-title {
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 0.3rem;
        font-size: 0.95rem;
        line-height: 1.4;
    }
    
    .recent-date {
        color: #999;
        font-size: 0.8rem;
    }
    
    .recent-date i {
        color: var(--premium-gold);
        margin-right: 0.3rem;
    }
    
    /* Newsletter sidebar */
    .newsletter-form-sidebar {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    
    .newsletter-input-sidebar {
        padding: 0.8rem 1rem;
        border: 1px solid #eee;
        font-size: 0.9rem;
    }
    
    .newsletter-input-sidebar:focus {
        outline: none;
        border-color: var(--premium-gold);
    }
    
    .newsletter-btn-sidebar {
        background: var(--premium-green);
        color: white;
        border: none;
        padding: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.8rem;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .newsletter-btn-sidebar:hover {
        background: var(--premium-gold);
    }
    
    .newsletter-check-sidebar {
        font-size: 0.75rem;
        color: #999;
        margin-top: 0.5rem;
    }
    
    /* Articles similaires */
    .similar-articles {
        background: var(--premium-light);
        padding: 4rem 0;
    }
    
    .similar-card {
        background: white;
        border: 1px solid #eee;
        transition: all 0.4s;
        height: 100%;
    }
    
    .similar-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.2);
    }
    
    .similar-image {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }
    
    .similar-content {
        padding: 1.5rem;
    }
    
    .similar-category {
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
    
    .similar-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--premium-dark);
        margin-bottom: 0.8rem;
        line-height: 1.4;
    }
    
    .similar-excerpt {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .similar-link {
        color: var(--premium-gold);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.3s;
    }
    
    .similar-link:hover {
        gap: 0.8rem;
    }
    
    .similar-link i {
        font-size: 0.75rem;
    }
    
    /* Flèche de scroll - petite */
    .scroll-arrow {
        font-size: 1rem;
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    
    .scroll-arrow:hover {
        opacity: 1;
    }
    
    /* Modal */
    .modal-content-custom {
        background: transparent;
        border: none;
    }
    
    .modal-close {
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 1rem;
        right: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .modal-close:hover {
        background: var(--premium-gold);
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-section {
            height: 70vh;
            min-height: 500px;
        }
        
        .meta-info {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .articles-navigation {
            flex-direction: column;
            gap: 1rem;
        }
        
        .nav-link {
            max-width: 100%;
        }
        
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .sidebar {
            position: static;
            margin-top: 3rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Premium -->
<div class="hero-section">
    <img src="{{ $actualite->image_url }}" alt="{{ $actualite->titre }}" class="hero-image">
    <div class="hero-overlay"></div>
    
    <div class="container">
        <div class="hero-content">
            <div class="row">
                <div class="col-lg-10">
                    {{--<nav class="breadcrumb-custom">
                        <a href="{{ route('home') }}">Accueil</a> / 
                        <a href="{{ route('actualites.index') }}">Actualités</a> / 
                        <span class="active">Article</span>
                    </nav>--}}
                    
                    <span class="badge-categorie-premium">{{ $actualite->categorie_formatee }}</span>
                    
                    <h1 class="hero-title float-animation">
                        {{ $actualite->titre }}
                    </h1>
                    
                    <p class="hero-subtitle">
                        {{ $actualite->description_courte }}
                    </p>
                    
                    <div class="meta-info">
                        <span><i class="fas fa-calendar-alt"></i> {{ $actualite->date_publication->format('d F Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $actualite->auteur_nom }}</span>
                        <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($actualite->contenu)) / 200) }} min de lecture</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Petite flèche de scroll -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4" style="z-index: 2;">
        <a href="#article-content" class="text-white scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</div>

<!-- Contenu de l'article -->
<div id="article-content" class="article-wrapper">
    <div class="container">
        <div class="row">
            <!-- Article principal -->
            <div class="col-lg-8">
                <!-- Table des matières (si plusieurs sections) -->
                @php
                    preg_match_all('/<h2>(.*?)<\/h2>/', $actualite->contenu, $matches);
                @endphp
                
                @if(count($matches[1]) > 0)
                <div class="table-of-contents">
                    <h5><i class="fas fa-list"></i> Sommaire</h5>
                    <ul>
                        @foreach($matches[1] as $index => $titre)
                        <li>
                            <a href="#section-{{ $index }}">
                                <i class="fas fa-chevron-right"></i> {{ $titre }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Contenu -->
                <div class="article-content">
                    @php
                        $contenu = $actualite->contenu;
                        foreach($matches[1] as $index => $titre) {
                            $ancre = '<h2 id="section-' . $index . '">' . $titre . '</h2>';
                            $contenu = str_replace('<h2>' . $titre . '</h2>', $ancre, $contenu);
                        }
                    @endphp
                    
                    {!! $contenu !!}
                </div>
                
                <!-- Tags -->
                @if($actualite->tags)
                <div class="tags-container">
                    @php
                        $tags = is_array($actualite->tags) ? $actualite->tags : 
                               (is_string($actualite->tags) ? json_decode($actualite->tags, true) : []);
                    @endphp
                    
                    @if(is_array($tags) && count($tags) > 0)
                        @foreach($tags as $tag)
                        <a href="{{ route('actualites.index') }}?search={{ urlencode($tag) }}" class="tag">
                            <i class="fas fa-tag"></i> {{ $tag }}
                        </a>
                        @endforeach
                    @endif
                </div>
                @endif
                
                <!-- Galerie -->
                @if($actualite->galerie)
                <div style="margin-top: 4rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 300; color: var(--premium-dark); margin-bottom: 2rem;">
                        <i class="fas fa-images me-2" style="color: var(--premium-gold);"></i>Galerie
                    </h3>
                    
                    <div class="gallery-grid">
                        @foreach($actualite->galerie_urls as $image)
                        <div class="gallery-item" onclick="ouvrirImage('{{ $image }}')">
                            <img src="{{ $image }}" alt="Galerie">
                            <div class="gallery-item-overlay">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Navigation entre articles -->
                <div class="articles-navigation">
                    @if($similaires->count() > 0 && isset($similaires[0]))
                    <a href="{{ route('actualites.show', $similaires[0]->slug) }}" class="nav-link prev">
                        <i class="fas fa-arrow-left"></i>
                        <div>
                            <strong>Article précédent</strong>
                            <span>{{ Str::limit($similaires[0]->titre, 40) }}</span>
                        </div>
                    </a>
                    @endif
                    
                    @if($similaires->count() > 1 && isset($similaires[1]))
                    <a href="{{ route('actualites.show', $similaires[1]->slug) }}" class="nav-link next">
                        <div class="text-end">
                            <strong>Article suivant</strong>
                            <span>{{ Str::limit($similaires[1]->titre, 40) }}</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Boutons de partage -->
                    <div class="sidebar-card">
                        <h5><i class="fas fa-share-alt"></i> Partager</h5>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn share-facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($actualite->titre) }}" target="_blank" class="share-btn share-twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($actualite->titre . ' ' . request()->url()) }}" target="_blank" class="share-btn share-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="share-btn share-linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Auteur -->
                    <div class="sidebar-card text-center">
                        <img src="{{ asset('images/author-placeholder.jpg') }}" alt="{{ $actualite->auteur_nom }}" class="author-avatar">
                        <h4 class="author-name">{{ $actualite->auteur_nom }}</h4>
                        <p class="author-role">Rédacteur</p>
                        <p class="author-bio">Équipe de communication de la Forêt d'Aného. Passionné par la nature et la biodiversité.</p>
                        <div class="author-social">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    
                    <!-- Articles récents -->
                    <div class="sidebar-card">
                        <h5><i class="fas fa-history"></i> Articles récents</h5>
                        @php
                            $recents = \App\Models\Actualite::where('id', '!=', $actualite->id)
                                ->where('est_publie', true)
                                ->orderBy('date_publication', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @foreach($recents as $recent)
                        <a href="{{ route('actualites.show', $recent->slug) }}" class="recent-item">
                            <img src="{{ $recent->image_url }}" alt="{{ $recent->titre }}" class="recent-image">
                            <div>
                                <h6 class="recent-title">{{ Str::limit($recent->titre, 40) }}</h6>
                                <div class="recent-date">
                                    <i class="fas fa-calendar-alt"></i> {{ $recent->date_publication->format('d/m/Y') }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    
                    <!-- Newsletter -->
                    <div class="sidebar-card">
                        <h5><i class="fas fa-envelope"></i> Newsletter</h5>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem;">Recevez nos derniers articles directement dans votre boîte mail.</p>
                        
                        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form-sidebar">
                            @csrf
                            <input type="email" name="email" class="newsletter-input-sidebar" placeholder="Votre email" required>
                            <input type="hidden" name="nom" value="Abonné">
                            <button type="submit" class="newsletter-btn-sidebar">S'abonner</button>
                        </form>
                        
                        <div class="newsletter-check-sidebar">
                            <input type="checkbox" id="newsletter-consent-sidebar" required>
                            <label for="newsletter-consent-sidebar">J'accepte de recevoir les actualités</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Articles similaires -->
@if($similaires && $similaires->count() > 0)
<section class="similar-articles">
    <div class="container">
        <h2 style="font-size: 2rem; font-weight: 300; color: var(--premium-dark); text-align: center; margin-bottom: 3rem;">
            Articles <strong style="font-weight: 700; color: var(--premium-green);">similaires</strong>
        </h2>
        
        <div class="row g-4">
            @foreach($similaires as $similaire)
            <div class="col-md-4">
                <a href="{{ route('actualites.show', $similaire->slug) }}" style="text-decoration: none;">
                    <div class="similar-card">
                        <img src="{{ $similaire->image_url }}" alt="{{ $similaire->titre }}" class="similar-image">
                        <div class="similar-content">
                            <span class="similar-category">{{ $similaire->categorie_formatee }}</span>
                            <h3 class="similar-title">{{ $similaire->titre }}</h3>
                            <p class="similar-excerpt">{{ Str::limit($similaire->description_courte, 80) }}</p>
                            <span class="similar-link">
                                Lire <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Modal pour les images -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-body p-0 text-center">
                <img src="" id="modalImage" class="img-fluid" style="max-height: 80vh;">
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function ouvrirImage(src) {
        document.getElementById('modalImage').src = src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Formulaire newsletter
        const form = document.querySelector('.newsletter-form-sidebar');
        if (form) {
            form.addEventListener('submit', function(e) {
                const consent = document.getElementById('newsletter-consent-sidebar');
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
        
        document.querySelectorAll('.article-content p, .article-content h2, .article-content h3, .article-content img, .article-content blockquote, .similar-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1)';
            observer.observe(el);
        });
    });
</script>
@endpush