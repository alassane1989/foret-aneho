@extends('admin.layouts.admin')

@section('title', $arbre->nom . ' - Administration')
@section('page-title', 'Détail de l\'arbre')

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
    
    .badge-sante {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .badge-excellent { background: var(--primary); color: white; }
    .badge-bon { background: var(--secondary); color: white; }
    .badge-moyen { background: var(--warning); color: white; }
    .badge-surveille { background: var(--danger); color: white; }
    
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
    
    .table-borderless th {
        color: var(--primary);
        font-weight: 600;
    }
    
    .table-borderless td {
        color: #333;
    }
    
    .img-fluid.rounded {
        border: 3px solid var(--primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .badge[style] {
        padding: 5px 10px;
        font-weight: 500;
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
    
    /* Style pour la photo du planteur */
    .rounded-circle {
        border: 2px solid var(--primary);
        object-fit: cover;
    }
    
    /* Style pour les cartes */
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-body {
        padding: 20px;
    }
    
    /* Ligne de séparation */
    hr {
        border-top: 1px solid rgba(46, 125, 50, 0.2);
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('arbres.voir'),
        'modifier' => $user->aPermission('arbres.modifier'),
        'supprimer' => $user->aPermission('arbres.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les détails des arbres.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.arbres.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.arbres.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge bg-success me-2">ID: #{{ $arbre->id }}</span>
            <span class="badge bg-primary">Créé le {{ $arbre->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Photo de l'arbre -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tree"></i>Photo de l'arbre</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $arbre->photo_url }}" class="img-fluid rounded" alt="{{ $arbre->nom }}" style="max-height: 300px;">
            </div>
        </div>

        <!-- QR Code -->
        @if($arbre->qr_code_url)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-qrcode"></i>QR Code</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $arbre->qr_code_url }}" alt="QR Code" style="width: 150px; height: 150px;">
                <p class="mt-2">
                    <a href="{{ $arbre->qr_code_url }}" download class="btn btn-success btn-sm">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </a>
                </p>
            </div>
        </div>
        @endif

        <!-- Photo du planteur (si existe) -->
        @if($arbre->planteur_photo)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i>Photo du planteur</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $arbre->planteur_photo_url }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--primary);" alt="{{ $arbre->planteur_nom }}">
                <p class="mt-2 mb-0">{{ $arbre->planteur_nom }}</p>
            </div>
        </div>
        @endif
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
                        <th width="30%">Nom :</th>
                        <td><strong class="text-success">{{ $arbre->nom }}</strong></td>
                    </tr>
                    <tr>
                        <th>Espèce :</th>
                        <td>
                            <strong class="text-primary">{{ $arbre->espece->nom_local }}</strong><br>
                            <small class="text-muted">{{ $arbre->espece->nom_scientifique }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Zone :</th>
                        <td>
                            <span class="badge" style="background-color: {{ $arbre->zone->couleur }}; color: white; padding: 5px 10px;">
                                {{ $arbre->zone->nom }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Date de plantation :</th>
                        <td>
                            <span class="text-primary">{{ $arbre->date_plantation->format('d/m/Y') }}</span>
                            <span class="badge bg-info ms-2">{{ $arbre->age }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Planteur :</th>
                        <td>
                            @if($arbre->planteur_photo)
                                <img src="{{ $arbre->planteur_photo_url }}" class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle text-primary me-2"></i>
                            @endif
                            <span class="text-primary">{{ $arbre->planteur_nom }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>État de santé :</th>
                        <td>
                            <span class="badge-sante badge-{{ $arbre->etat_sante }}">
                                {{ ucfirst($arbre->etat_sante) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i>Description</h5>
            </div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-line;">{{ $arbre->description }}</p>
            </div>
        </div>

        <!-- Localisation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i>Localisation GPS</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><i class="fas fa-map-pin text-primary me-2"></i><strong>Latitude :</strong> {{ $arbre->latitude }}</p>
                        <p><i class="fas fa-map-pin text-primary me-2"></i><strong>Longitude :</strong> {{ $arbre->longitude }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><i class="fas fa-ruler text-success me-2"></i><strong>Hauteur :</strong> {{ $arbre->hauteur ?? 'Non mesurée' }}</p>
                        <p><i class="fas fa-ruler-combined text-success me-2"></i><strong>Circonférence :</strong> {{ $arbre->circonference ?? 'Non mesurée' }}</p>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <a href="https://www.google.com/maps?q={{ $arbre->latitude }},{{ $arbre->longitude }}" target="_blank" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>Voir sur Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions (boutons du bas) -->
@if($perms['modifier'] || $perms['supprimer'])
<div class="row mt-4">
    <div class="col-12 text-center">
        @if($perms['modifier'])
        <a href="{{ route('admin.arbres.edit', $arbre->id) }}" class="btn btn-warning btn-lg px-4">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        @endif
        
        @if($perms['supprimer'])
        <form action="{{ route('admin.arbres.destroy', $arbre->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
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
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet arbre ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush