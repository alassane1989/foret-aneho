@extends('admin.layouts.admin')

@section('title', $espece->nom_local . ' - Administration')
@section('page-title', 'Détail de l\'espèce : ' . $espece->nom_local)

@push('styles')
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
    }
    
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
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #B71C1C;
        border-color: #B71C1C;
    }
    
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #1B5E20;
        border-color: #1B5E20;
    }
    
    .btn-outline-success {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-outline-success:hover {
        background: var(--primary);
        color: white;
    }
    
    .table-borderless th {
        color: var(--primary);
        font-weight: 600;
        width: 30%;
    }
    
    .table-borderless td {
        color: #333;
    }
    
    .img-fluid.rounded {
        border: 3px solid var(--primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-height: 300px;
        object-fit: cover;
    }
    
    .badge.bg-success {
        background: var(--primary) !important;
        color: white;
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .badge.bg-info {
        background: var(--secondary) !important;
        color: white;
    }
    
    .badge.bg-warning {
        background: var(--warning) !important;
        color: white;
    }
    
    .badge.bg-danger {
        background: var(--danger) !important;
        color: white;
    }
    
    .badge.bg-secondary {
        background: #6c757d !important;
        color: white;
    }
    
    .text-success {
        color: var(--primary) !important;
    }
    
    .text-primary {
        color: var(--secondary) !important;
    }
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    .text-warning {
        color: var(--warning) !important;
    }
    
    .display-6 {
        font-size: 2rem;
        font-weight: 600;
        color: var(--primary);
    }
    
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
    
    hr {
        border-top: 1px solid rgba(46, 125, 50, 0.2);
    }
    
    .galerie-item img {
        height: 150px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--primary);
        transition: transform 0.3s;
    }
    
    .galerie-item img:hover {
        transform: scale(1.05);
    }
    
    .zone-badge {
        display: inline-block;
        padding: 8px 15px;
        margin: 5px;
        background: rgba(46, 125, 50, 0.1);
        color: var(--primary);
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid var(--primary);
    }
    
    .zone-badge:hover {
        background: var(--primary);
        color: white;
    }
    
    .stat-box {
        background: rgba(46, 125, 50, 0.05);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        height: 100%;
    }
    
    .stat-box .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .stat-box .label {
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
    }
    
    /* Responsivité */
    @media (max-width: 768px) {
        .table-borderless th {
            width: 40%;
        }
        
        .display-6 {
            font-size: 1.5rem;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        
        .galerie-item {
            margin-bottom: 15px;
        }
        
        .galerie-item img {
            height: 120px;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('especes.voir'),
        'modifier' => $user->aPermission('especes.modifier'),
        'supprimer' => $user->aPermission('especes.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les détails des espèces.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.especes.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.especes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge bg-success me-2">ID: #{{ $espece->id }}</span>
            <span class="badge bg-primary">Créé le {{ $espece->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Image principale -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image"></i>Image principale</h5>
            </div>
            <div class="card-body text-center">
                @if($espece->image_principale)
                    <img src="{{ Storage::url($espece->image_principale) }}" class="img-fluid rounded" alt="{{ $espece->nom_local }}">
                @else
                    <div class="bg-light p-5 rounded">
                        <i class="fas fa-leaf fa-4x text-muted"></i>
                        <p class="mt-2 text-muted">Aucune image</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <div class="number">{{ $espece->arbres->count() }}</div>
                            <div class="label">Arbres dans la forêt</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <div class="number">{{ $espece->zones->count() }}</div>
                            <div class="label">Zones présentes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galerie (miniatures) -->
        @if($espece->galerie && is_array($espece->galerie) && count($espece->galerie) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-images"></i>Aperçu galerie</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(array_slice($espece->galerie, 0, 4) as $image)
                    <div class="col-6 mb-2">
                        <img src="{{ Storage::url($image) }}" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover; border: 2px solid var(--primary);">
                    </div>
                    @endforeach
                </div>
                @if(count($espece->galerie) > 4)
                <div class="text-center mt-2">
                    <small class="text-muted">+ {{ count($espece->galerie) - 4 }} autres images</small>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <!-- Informations générales -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i>Informations générales</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nom scientifique :</th>
                        <td><em class="text-primary">{{ $espece->nom_scientifique }}</em></td>
                    </tr>
                    <tr>
                        <th>Nom local :</th>
                        <td><strong class="text-success">{{ $espece->nom_local }}</strong></td>
                    </tr>
                    <tr>
                        <th>Famille :</th>
                        <td>{{ $espece->famille ?? 'Non spécifiée' }}</td>
                    </tr>
                    <tr>
                        <th>Genre :</th>
                        <td>{{ $espece->genre ?? 'Non spécifié' }}</td>
                    </tr>
                    <tr>
                        <th>Origine :</th>
                        <td>{{ $espece->origine ?? 'Non spécifiée' }}</td>
                    </tr>
                    <tr>
                        <th>Type de feuillage :</th>
                        <td>{{ $espece->type ? ucfirst($espece->type) : 'Non spécifié' }}</td>
                    </tr>
                    <tr>
                        <th>Hauteur maximale :</th>
                        <td>{{ $espece->hauteur_max ?? 'Non spécifiée' }}</td>
                    </tr>
                    <tr>
                        <th>Longévité :</th>
                        <td>{{ $espece->longevite ?? 'Non spécifiée' }}</td>
                    </tr>
                    <tr>
                        <th>Catégorie :</th>
                        <td>
                            <span class="badge bg-success p-2">
                                {{ $espece->categorie_formatee }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Statut conservation :</th>
                        <td>
                            @if($espece->statut_conservation)
                                @php
                                    $statutClass = match($espece->statut_conservation) {
                                        'Vulnérable', 'En danger' => 'danger',
                                        'Quasi menacé' => 'warning',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statutClass }} p-2">
                                    {{ $espece->statut_conservation }}
                                </span>
                            @else
                                <span class="badge bg-secondary p-2">Non défini</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Origine :</th>
                        <td>
                            @if($espece->est_locale)
                                <span class="badge bg-info p-2"><i class="fas fa-check-circle me-1"></i>Locale</span>
                            @else
                                <span class="badge bg-secondary p-2"><i class="fas fa-globe me-1"></i>Introduite</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Descriptions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i>Descriptions</h5>
            </div>
            <div class="card-body">
                <h6 class="text-success fw-bold">Description générale :</h6>
                <p class="mb-4" style="white-space: pre-line;">{{ $espece->description_generale }}</p>
                
                @if($espece->description_botanique)
                <h6 class="text-success fw-bold mt-3">Description botanique :</h6>
                <p class="mb-4" style="white-space: pre-line;">{{ $espece->description_botanique }}</p>
                @endif
                
                @if($espece->utilisation)
                <h6 class="text-success fw-bold mt-3">Utilisations :</h6>
                <p class="mb-4" style="white-space: pre-line;">{{ $espece->utilisation }}</p>
                @endif
                
                @if($espece->importance_culturelle)
                <h6 class="text-success fw-bold mt-3">Importance culturelle :</h6>
                <p style="white-space: pre-line;">{{ $espece->importance_culturelle }}</p>
                @endif
            </div>
        </div>

        <!-- Conseils de plantation -->
        @if($espece->conseils_plantation && is_array($espece->conseils_plantation) && count($espece->conseils_plantation) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-seedling"></i>Conseils de plantation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($espece->conseils_plantation as $key => $value)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-{{ 
                                $key == 'soleil' ? 'sun' : 
                                ($key == 'eau' ? 'tint' : 
                                ($key == 'sol' ? 'mountain' : 
                                ($key == 'temperature' ? 'thermometer-half' : 
                                ($key == 'espace' ? 'ruler-combined' : 
                                ($key == 'entretien' ? 'tools' : 'leaf'))))) 
                            }} text-success fa-lg me-3 mt-1"></i>
                            <div>
                                <strong class="text-success">{{ ucfirst($key) }} :</strong>
                                <p class="mb-0">{{ $value }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Périodes -->
        @if($espece->periodes && is_array($espece->periodes) && count($espece->periodes) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i>Cycles et périodes</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($espece->periodes as $periode => $date)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-calendar-check text-success fa-lg me-3 mt-1"></i>
                            <div>
                                <strong class="text-success">{{ ucfirst(str_replace('_', ' ', $periode)) }} :</strong>
                                <p class="mb-0">{{ $date }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Galerie complète -->
        @if($espece->galerie && is_array($espece->galerie) && count($espece->galerie) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-images"></i>Galerie d'images</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($espece->galerie as $image)
                    <div class="col-md-3 col-6 mb-3 galerie-item">
                        <img src="{{ Storage::url($image) }}" class="img-fluid rounded" alt="Galerie">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Zones où l'espèce est présente -->
        @if($espece->zones->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i>Zones de présence</h5>
            </div>
            <div class="card-body">
                @foreach($espece->zones as $zone)
                <a href="{{ route('admin.zones.show', $zone->id) }}" class="zone-badge">
                    <i class="fas fa-map-pin me-1"></i>
                    {{ $zone->nom }} ({{ $zone->arbres()->where('espece_id', $espece->id)->count() }} arbres)
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Actions -->
@if($perms['modifier'] || $perms['supprimer'])
<div class="row mt-4">
    <div class="col-12 text-center">
        @if($perms['modifier'])
        <a href="{{ route('admin.especes.edit', $espece->id) }}" class="btn btn-warning btn-lg px-4">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        @endif
        
        @if($perms['supprimer'] && $espece->arbres->count() == 0)
        <form action="{{ route('admin.especes.destroy', $espece->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg px-4 ms-2">
                <i class="fas fa-trash me-2"></i>Supprimer
            </button>
        </form>
        @endif
    </div>
</div>
@endif

@endif
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette espèce ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush