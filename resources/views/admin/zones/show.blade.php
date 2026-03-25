@extends('admin.layouts.admin')

@section('title', $zone->nom . ' - Administration')
@section('page-title', 'Détail de la zone : ' . $zone->nom)

@push('styles')
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --info: #17a2b8;           /* Info */
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
    
    .btn-info {
        background: var(--info);
        border-color: var(--info);
        color: white;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #138496;
    }
    
    .table-borderless th {
        color: var(--primary);
        font-weight: 600;
        width: 30%;
    }
    
    .table-borderless td {
        color: #333;
    }
    
    .color-preview {
        width: 30px;
        height: 30px;
        display: inline-block;
        border-radius: 50%;
        vertical-align: middle;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .img-fluid.rounded {
        border: 3px solid var(--primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-height: 300px;
        object-fit: cover;
    }
    
    .display-6 {
        font-size: 2rem;
        font-weight: 600;
        color: var(--primary);
    }
    
    .badge.bg-success {
        background: var(--primary) !important;
        color: white;
        padding: 5px 10px;
    }
    
    .badge.bg-info {
        background: var(--info) !important;
        color: white;
    }
    
    .badge.bg-secondary {
        background: #6c757d !important;
        color: white;
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
    
    .stat-box {
        background: rgba(46, 125, 50, 0.05);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        height: 100%;
    }
    
    /* Activités */
    .activite-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background: rgba(46, 125, 50, 0.05);
        border-radius: 10px;
        margin-bottom: 10px;
    }
    
    .activite-item i {
        width: 40px;
        text-align: center;
    }
    
    /* Espèces */
    .espece-badge {
        background: transparent;
        color: var(--primary);
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        border: 1px solid var(--primary);
        display: inline-block;
        margin: 5px;
        transition: all 0.3s;
    }
    .espece-badge:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Tableau des arbres */
    .table thead th {
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
    }
    
    .table tbody tr:hover {
        background: rgba(46, 125, 50, 0.05);
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
        
        .img-fluid.rounded {
            max-height: 200px;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('zones.voir'),
        'modifier' => $user->aPermission('zones.modifier'),
        'supprimer' => $user->aPermission('zones.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les détails des zones.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge bg-success me-2">ID: #{{ $zone->id }}</span>
            <span class="badge bg-info">Ordre: {{ $zone->ordre }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Image de la zone -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image"></i>Image de la zone</h5>
            </div>
            <div class="card-body text-center">
                @if($zone->image_principale)
                    <img src="{{ $zone->image_url }}" class="img-fluid rounded" alt="{{ $zone->nom }}">
                @else
                    <div class="bg-light p-5 rounded">
                        <i class="fas fa-map-marked-alt fa-4x text-muted"></i>
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
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <div class="display-6">{{ $zone->nombre_arbres }}</div>
                            <div class="text-muted small">Arbres</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <div class="display-6">{{ $zone->nombre_especes }}</div>
                            <div class="text-muted small">Espèces</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métadonnées -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i>Métadonnées</h5>
            </div>
            <div class="card-body">
                <p><i class="fas fa-calendar-alt text-primary me-2"></i><strong>Créé le :</strong> {{ $zone->created_at->format('d/m/Y H:i') }}</p>
                <p><i class="fas fa-edit text-primary me-2"></i><strong>Modifié le :</strong> {{ $zone->updated_at->format('d/m/Y H:i') }}</p>
                <p><i class="fas fa-hashtag text-primary me-2"></i><strong>Slug :</strong> {{ $zone->slug }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Informations principales -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i>Informations générales</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nom :</th>
                        <td><strong class="text-success">{{ $zone->nom }}</strong></td>
                    </tr>
                    <tr>
                        <th>Couleur :</th>
                        <td>
                            <span class="color-preview" style="background-color: {{ $zone->couleur }};"></span>
                            <span class="ms-2">{{ $zone->couleur }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Superficie :</th>
                        <td>{{ $zone->superficie ?? 'Non définie' }}</td>
                    </tr>
                    <tr>
                        <th>Ordre d'affichage :</th>
                        <td>{{ $zone->ordre }}</td>
                    </tr>
                    <tr>
                        <th>Statut :</th>
                        <td>
                            @if($zone->est_active)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
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
                <h6 class="text-success fw-bold">Description courte :</h6>
                <p class="mb-4">{{ $zone->description_courte }}</p>
                
                @if($zone->description_longue)
                <h6 class="text-success fw-bold">Description longue :</h6>
                <p class="mb-0" style="white-space: pre-line;">{{ $zone->description_longue }}</p>
                @endif
            </div>
        </div>

        <!-- Localisation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i>Localisation</h5>
            </div>
            <div class="card-body">
                @if($zone->latitude && $zone->longitude)
                <div class="row">
                    <div class="col-md-6">
                        <p><i class="fas fa-map-pin text-primary me-2"></i><strong>Latitude :</strong> {{ $zone->latitude }}</p>
                        <p><i class="fas fa-map-pin text-primary me-2"></i><strong>Longitude :</strong> {{ $zone->longitude }}</p>
                    </div>
                </div>
                @else
                <p class="text-muted"><i class="fas fa-exclamation-circle me-2"></i>Coordonnées non définies</p>
                @endif

                @if($zone->adresse_acces)
                <p><i class="fas fa-road text-primary me-2"></i><strong>Adresse d'accès :</strong> {{ $zone->adresse_acces }}</p>
                @endif

                @if($zone->latitude && $zone->longitude)
                <div class="text-center mt-3">
                    <a href="https://www.google.com/maps?q={{ $zone->latitude }},{{ $zone->longitude }}" target="_blank" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>Voir sur Google Maps
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Activités -->
        @if($zone->activites && is_array($zone->activites) && count($zone->activites) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tasks"></i>Activités proposées</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($zone->activites as $activite)
                    <div class="col-md-6 mb-3">
                        <div class="activite-item">
                            <i class="fas fa-{{ $activite['icone'] ?? 'tree' }} text-success fa-lg me-3"></i>
                            <span>{{ $activite['nom'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Espèces principales -->
        @if($zone->especes_principales && is_array($zone->especes_principales) && count($zone->especes_principales) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-leaf"></i>Espèces principales</h5>
            </div>
            <div class="card-body">
                @foreach($zone->especes_principales as $espece)
                <span class="espece-badge">
                    <i class="fas fa-leaf me-1"></i>{{ $espece }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Liste des arbres de la zone -->
@if($zone->arbres->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tree"></i>Arbres de cette zone ({{ $zone->arbres->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Espèce</th>
                                <th>Date plantation</th>
                                <th>Planteur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($zone->arbres as $arbre)
                            <tr>
                                <td><span class="text-success">{{ $arbre->nom }}</span></td>
                                <td><span class="text-primary">{{ $arbre->espece->nom_local }}</span></td>
                                <td>{{ $arbre->date_plantation->format('d/m/Y') }}</td>
                                <td>{{ $arbre->planteur_nom }}</td>
                                <td>
                                    @if($perms['modifier'])
                                    <a href="{{ route('admin.arbres.show', $arbre->id) }}" class="btn btn-sm btn-outline-primary btn-action" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Actions -->
@if($perms['modifier'] || $perms['supprimer'])
<div class="row mt-4">
    <div class="col-12 text-center">
        @if($perms['modifier'])
        <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-warning btn-lg px-4">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        @endif
        
        @if($perms['supprimer'] && $zone->arbres->count() == 0)
        <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
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
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette zone ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush