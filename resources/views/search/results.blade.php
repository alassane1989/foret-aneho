@extends('layouts.app')

@section('title', 'Résultats de recherche - ' . $query)

@push('styles')
<style>
    .search-hero {
        background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 3rem;
    }
    
    .search-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .search-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .result-section {
        margin-bottom: 3rem;
    }
    
    .result-section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2E7D32;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #ffc107;
        display: inline-block;
    }
    
    .result-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 0;
        margin-bottom: 1rem;
        transition: all 0.3s;
        overflow: hidden;
    }
    
    .result-card:hover {
        transform: translateX(5px);
        border-left: 3px solid #ffc107;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .result-card a {
        text-decoration: none;
        color: inherit;
        display: flex;
        padding: 1rem;
    }
    
    .result-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .result-content {
        flex: 1;
    }
    
    .result-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2E7D32;
        margin-bottom: 0.5rem;
    }
    
    .result-meta {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.5rem;
    }
    
    .result-excerpt {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .result-badge {
        display: inline-block;
        padding: 0.2rem 0.8rem;
        background: #ffc107;
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    
    .no-results {
        text-align: center;
        padding: 4rem;
        background: #f8f9fa;
        border: 1px solid #eee;
    }
    
    .no-results i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 1rem;
    }
    
    .search-suggestion {
        margin-top: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-left: 3px solid #ffc107;
    }
</style>
@endpush

@section('content')
<div class="search-hero">
    <div class="container">
        <h1>Résultats de recherche</h1>
        <p>Vous avez cherché : <strong>"{{ $query }}"</strong></p>
        <p>{{ $totalResults }} résultat(s) trouvé(s)</p>
    </div>
</div>

<div class="container py-5">
    @if($totalResults > 0)
        <!-- Arbres -->
        @if($arbres->count() > 0)
        <div class="result-section">
            <h2 class="result-section-title">Arbres</h2>
            @foreach($arbres as $arbre)
            <div class="result-card">
                <a href="{{ route('arbres.show', $arbre->slug) }}">
                    <img src="{{ $arbre->photo_url }}" alt="{{ $arbre->nom }}" class="result-image">
                    <div class="result-content">
                        <div class="result-badge">Arbre</div>
                        <div class="result-title">{{ $arbre->nom }}</div>
                        <div class="result-meta">
                            <i class="fas fa-leaf"></i> {{ $arbre->espece->nom_local }} |
                            <i class="fas fa-map-marker-alt"></i> Zone {{ $arbre->zone->nom }}
                        </div>
                        <div class="result-excerpt">{{ Str::limit($arbre->description, 100) }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Espèces -->
        @if($especes->count() > 0)
        <div class="result-section">
            <h2 class="result-section-title">Espèces</h2>
            @foreach($especes as $espece)
            <div class="result-card">
                <a href="{{ route('especes.show', $espece->slug) }}">
                    <img src="{{ $espece->image_url }}" alt="{{ $espece->nom_local }}" class="result-image">
                    <div class="result-content">
                        <div class="result-badge">Espèce</div>
                        <div class="result-title">{{ $espece->nom_local }}</div>
                        <div class="result-meta">
                            <i class="fas fa-microscope"></i> {{ $espece->nom_scientifique }}
                        </div>
                        <div class="result-excerpt">{{ Str::limit($espece->description_generale, 100) }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Actualités -->
        @if($actualites->count() > 0)
        <div class="result-section">
            <h2 class="result-section-title">Actualités</h2>
            @foreach($actualites as $actualite)
            <div class="result-card">
                <a href="{{ route('actualites.show', $actualite->slug) }}">
                    <img src="{{ $actualite->image_url }}" alt="{{ $actualite->titre }}" class="result-image">
                    <div class="result-content">
                        <div class="result-badge">Actualité</div>
                        <div class="result-title">{{ $actualite->titre }}</div>
                        <div class="result-meta">
                            <i class="fas fa-calendar"></i> {{ $actualite->date_publication->format('d/m/Y') }} |
                            <i class="fas fa-tag"></i> {{ $actualite->categorie_formatee }}
                        </div>
                        <div class="result-excerpt">{{ Str::limit($actualite->description_courte, 100) }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Zones -->
        @if($zones->count() > 0)
        <div class="result-section">
            <h2 class="result-section-title">Zones</h2>
            @foreach($zones as $zone)
            <div class="result-card">
                <a href="{{ route('zones.show', $zone->slug) }}">
                    <img src="{{ $zone->image_url }}" alt="{{ $zone->nom }}" class="result-image">
                    <div class="result-content">
                        <div class="result-badge">Zone</div>
                        <div class="result-title">Zone {{ $zone->nom }}</div>
                        <div class="result-excerpt">{{ Str::limit($zone->description, 100) }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        
    @else
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>Aucun résultat trouvé</h3>
            <p>Nous n'avons trouvé aucun résultat pour "{{ $query }}"</p>
            <div class="search-suggestion">
                <strong>Suggestions :</strong>
                <ul class="mt-2 mb-0">
                    <li>Vérifiez l'orthographe de votre recherche</li>
                    <li>Essayez avec des mots-clés plus généraux</li>
                    <li>Utilisez le nom scientifique d'une espèce</li>
                </ul>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-success mt-4">Retour à l'accueil</a>
        </div>
    @endif
</div>
@endsection