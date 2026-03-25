@extends('admin.layouts.admin')

@section('title', $actualite->titre . ' - Administration')
@section('page-title', 'Détail de l\'actualité')

@push('styles')
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --purple: #9C27B0;        /* Violet pour partenariat */
        --info: #17a2b8;           /* Info */
    }
    
    /* ===== STYLES GÉNÉRAUX ===== */
    .card-header {
        background: linear-gradient(135deg, var(--primary), #1B5E20) !important;
        color: white;
        border-bottom: none;
    }
    
    .card-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .card-header i {
        margin-right: 8px;
        color: rgba(255,255,255,0.9);
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }
    
    .btn-warning {
        background: var(--warning);
        border-color: var(--warning);
        color: white;
    }
    .btn-warning:hover {
        background: #EF6C00;
        border-color: #EF6C00;
        color: white;
    }
    
    .btn-info {
        background: var(--info);
        border-color: var(--info);
        color: white;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #138496;
    }
    
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #1B5E20;
        border-color: #1B5E20;
    }
    
    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
    }
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #B71C1C;
        border-color: #B71C1C;
    }
    
    /* ===== BADGES CATÉGORIE ===== */
    .badge-categorie {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-block;
    }
    .badge-plantation { background: var(--primary); color: white; }
    .badge-education { background: var(--secondary); color: white; }
    .badge-infrastructure { background: var(--warning); color: white; }
    .badge-partenariat { background: var(--purple); color: white; }
    .badge-evenement { background: var(--danger); color: white; }
    
    /* ===== BADGES STATUT ===== */
    .status-badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-block;
        border: none;
    }
    .status-publie { background: var(--primary); color: white; }
    .status-brouillon { background: var(--danger); color: white; }
    
    /* ===== BADGE VUES ===== */
    .badge.bg-info {
        background: var(--info) !important;
        color: white;
        padding: 5px 10px;
    }
    
    /* ===== TABLEAU ===== */
    .table-borderless th {
        color: var(--primary);
        font-weight: 600;
        width: 35%;
    }
    
    .table-borderless td {
        color: #333;
    }
    
    /* ===== CONTENU ARTICLE ===== */
    .article-content {
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 10px 0;
        border: 2px solid var(--primary);
    }
    
    .article-content h1, .article-content h2, .article-content h3 {
        color: var(--primary);
        margin-top: 20px;
    }
    
    .article-content h4, .article-content h5, .article-content h6 {
        color: var(--secondary);
    }
    
    .article-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 15px;
        margin: 15px 0;
        font-style: italic;
        color: #555;
        background: rgba(46, 125, 50, 0.05);
        padding: 10px 15px;
        border-radius: 0 10px 10px 0;
    }
    
    .article-content a {
        color: var(--secondary);
        text-decoration: underline;
    }
    
    .article-content a:hover {
        color: var(--primary);
    }
    
    .article-content ul, .article-content ol {
        padding-left: 20px;
    }
    
    /* ===== TAGS ===== */
    .badge.bg-secondary {
        background: #6c757d !important;
        color: white;
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 50px;
        transition: all 0.3s;
    }
    
    .badge.bg-secondary:hover {
        background: var(--primary) !important;
        transform: translateY(-2px);
    }
    
    /* ===== GALERIE ===== */
    .galerie-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        transition: transform 0.3s;
    }
    
    .galerie-item:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
    }
    
    .galerie-item img {
        height: 150px;
        width: 100%;
        object-fit: cover;
        border: 2px solid var(--primary);
    }
    
    /* ===== CARTES ===== */
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    /* ===== BOUTONS D'ACTION ===== */
    .d-grid .btn {
        margin-bottom: 10px;
        border-radius: 10px;
        padding: 10px;
        font-weight: 500;
    }
    
    .d-grid .btn:last-child {
        margin-bottom: 0;
    }
    
    /* ===== RESPONSIVITÉ ===== */
    @media (max-width: 768px) {
        .table-borderless th {
            width: 40%;
        }
        
        .galerie-item img {
            height: 100px;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('actualites.voir'),
        'modifier' => $user->aPermission('actualites.modifier'),
        'publier' => $user->aPermission('actualites.publier'),
        'creer' => $user->aPermission('actualites.creer'),
        'supprimer' => $user->aPermission('actualites.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les détails des actualités.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge bg-success me-2">ID: #{{ $actualite->id }}</span>
            <span class="badge bg-info">Vues: {{ $actualite->vues }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Image principale -->
        @if($actualite->image_principale)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image"></i>Image principale</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $actualite->image_url }}" class="img-fluid rounded" alt="{{ $actualite->titre }}" style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- Contenu -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i>Contenu</h5>
            </div>
            <div class="card-body">
                <div class="article-content">
                    {!! $actualite->contenu !!}
                </div>
            </div>
        </div>

        <!-- Galerie -->
        @if($actualite->galerie && is_array($actualite->galerie) && count($actualite->galerie) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-images"></i>Galerie</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($actualite->galerie_urls as $image)
                    <div class="col-md-3 col-6">
                        <div class="galerie-item">
                            <img src="{{ $image }}" class="img-fluid rounded" alt="Galerie">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Informations -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i>Informations</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Titre :</th>
                        <td><strong class="text-success">{{ $actualite->titre }}</strong></td>
                    </tr>
                    <tr>
                        <th>Catégorie :</th>
                        <td>
                            <span class="badge-categorie badge-{{ $actualite->categorie }}">
                                {{ $actualite->categorie_formatee }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Auteur :</th>
                        <td><span class="text-primary">{{ $actualite->auteur_nom }}</span></td>
                    </tr>
                    <tr>
                        <th>Date publication :</th>
                        <td>{{ $actualite->date_publication->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Dernière modif :</th>
                        <td>{{ $actualite->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Vues :</th>
                        <td><span class="badge bg-info">{{ $actualite->vues }}</span></td>
                    </tr>
                    <tr>
                        <th>Statut :</th>
                        <td>
                            @if($actualite->est_publie)
                                <span class="status-badge status-publie">
                                    <i class="fas fa-check-circle me-1"></i>Publié
                                </span>
                            @else
                                <span class="status-badge status-brouillon">
                                    <i class="fas fa-clock me-1"></i>Brouillon
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description courte -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-paragraph"></i>Description courte</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $actualite->description_courte }}</p>
            </div>
        </div>

        <!-- Tags -->
        @if($actualite->tags && is_array($actualite->tags) && count($actualite->tags) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tags"></i>Tags</h5>
            </div>
            <div class="card-body">
                @foreach($actualite->tags as $tag)
                <span class="badge bg-secondary me-1 mb-1 p-2">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        @if($perms['modifier'] || $perms['publier'] || $perms['creer'] || $perms['supprimer'])
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i>Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($perms['modifier'])
                    <a href="{{ route('admin.actualites.edit', $actualite->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    @endif
                    
                    @if($perms['publier'])
                    <form action="{{ route('admin.actualites.toggle-status', $actualite->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $actualite->est_publie ? 'secondary' : 'success' }} w-100">
                            <i class="fas fa-{{ $actualite->est_publie ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $actualite->est_publie ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>
                    @endif
                    
                    @if($perms['creer'])
                    <form action="{{ route('admin.actualites.duplicate', $actualite->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-copy me-2"></i>Dupliquer
                        </button>
                    </form>
                    @endif
                    
                    @if($perms['supprimer'])
                    <form action="{{ route('admin.actualites.destroy', $actualite->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush