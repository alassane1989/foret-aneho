@extends('admin.layouts.admin')

@section('title', 'Gestion des Zones - Administration')
@section('page-title', 'Gestion des Zones')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --info: #17a2b8;           /* Info */
    }
    
    /* ===== ZONE CARD ===== */
    .zone-card {
        background: white;
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border-left: 5px solid transparent;
        border: 1px solid rgba(46, 125, 50, 0.1);
        width: 100%;
        overflow: hidden;
    }
    .zone-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(46,125,50,0.15);
        border-color: var(--primary);
    }
    
    /* ===== CONTENEUR FLEX POUR LA CARTE ===== */
    .zone-container {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
    }
    
    /* ===== POIGNÉE DE TRI ===== */
    .handle-container {
        flex: 0 0 40px;
        text-align: center;
    }
    .handle {
        cursor: move;
        color: #adb5bd;
        transition: color 0.3s;
        font-size: 24px;
    }
    .handle:hover {
        color: var(--primary);
    }
    
    /* ===== INDICATEUR DE COULEUR ===== */
    .color-container {
        flex: 0 0 50px;
        text-align: center;
    }
    .zone-color {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* ===== INFORMATIONS DE LA ZONE ===== */
    .info-container {
        flex: 1;
        display: flex;
        gap: 15px;
        min-width: 0; /* Pour éviter le débordement */
    }
    
    .info-item {
        flex: 1;
        min-width: 0; /* Pour permettre le text-overflow */
        overflow: hidden;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .info-value.text-success {
        color: var(--primary) !important;
        font-weight: 600;
    }
    
    /* ===== BADGES STATUT ===== */
    .badge-active {
        background: transparent;
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid var(--primary);
        display: inline-block;
    }
    .badge-inactive {
        background: transparent;
        color: var(--danger);
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid var(--danger);
        display: inline-block;
    }
    
    .badge-count {
        background: var(--primary) !important;
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-block;
    }
    
    /* ===== BOUTONS D'ACTION ===== */
    .actions-container {
        flex: 0 0 auto;
        display: flex;
        gap: 5px;
    }
    
    .btn-action {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid transparent;
        transition: all 0.3s;
    }
    
    .btn-outline-primary {
        color: var(--secondary);
        border-color: var(--secondary);
        background: transparent;
    }
    .btn-outline-primary:hover {
        background: var(--secondary);
        color: white;
    }
    
    .btn-outline-success {
        color: var(--primary);
        border-color: var(--primary);
        background: transparent;
    }
    .btn-outline-success:hover {
        background: var(--primary);
        color: white;
    }
    
    .btn-outline-danger {
        color: var(--danger);
        border-color: var(--danger);
        background: transparent;
    }
    .btn-outline-danger:hover:not(:disabled) {
        background: var(--danger);
        color: white;
    }
    
    .btn-outline-danger:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* ===== EN-TÊTE ===== */
    .page-header {
        margin-bottom: 20px;
    }
    
    .page-header h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .page-header h4 i {
        color: var(--primary);
    }
    
    /* ===== ALERTE INFO ===== */
    .alert-info {
        background: rgba(25, 118, 210, 0.1);
        border: 1px solid var(--secondary);
        color: var(--secondary);
        border-radius: 10px;
        padding: 12px 20px;
    }
    
    /* ===== RESPONSIVITÉ ===== */
    @media (max-width: 992px) {
        .zone-container {
            flex-wrap: wrap;
        }
        
        .handle-container, .color-container {
            flex: 0 0 auto;
        }
        
        .info-container {
            flex: 1 1 100%;
            order: 1;
            flex-wrap: wrap;
        }
        
        .info-item {
            flex: 1 1 calc(50% - 15px);
            min-width: calc(50% - 15px);
        }
        
        .actions-container {
            flex: 1 1 100%;
            justify-content: center;
            margin-top: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .zone-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .handle-container, .color-container {
            align-self: center;
        }
        
        .info-container {
            flex-direction: column;
            width: 100%;
        }
        
        .info-item {
            flex: 1 1 100%;
            min-width: 100%;
            text-align: center;
        }
        
        .actions-container {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('zones.voir'),
        'creer' => $user->aPermission('zones.creer'),
        'modifier' => $user->aPermission('zones.modifier'),
        'supprimer' => $user->aPermission('zones.supprimer'),
        'exporter' => $user->aPermission('zones.exporter'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les zones.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
        </a>
    </div>
@else

<!-- En-tête -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">
            <i class="fas fa-map-marked-alt me-2 text-success"></i>
            Gestion des Zones
            <span class="badge bg-success ms-2">{{ $zones->count() }}</span>
        </h4>
        <div class="mt-2 mt-md-0">
            <!-- Boutons d'export -->
            @if($perms['exporter'])
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Exporter
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.zones.export.excel', request()->query()) }}">
                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.zones.export.pdf', request()->query()) }}">
                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- Bouton création -->
            @if($perms['creer'])
            <a href="{{ route('admin.zones.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle zone
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Message d'information sur le tri -->
@if($perms['modifier'])
<div class="alert alert-info mb-4">
    <i class="fas fa-arrows-alt me-2"></i>
    Vous pouvez réorganiser l'ordre des zones en les glissant-déposant.
</div>
@endif

<!-- Liste des zones -->
<div id="sortable-zones">
    @forelse($zones as $zone)
    <div class="zone-card" data-id="{{ $zone->id }}" style="border-left-color: {{ $zone->couleur }};">
        <div class="zone-container">
            <!-- Poignée de tri (si permission) -->
            @if($perms['modifier'])
            <div class="handle-container">
                <i class="fas fa-grip-vertical handle"></i>
            </div>
            @endif
            
            <!-- Indicateur de couleur -->
            <div class="color-container">
                <span class="zone-color" style="background-color: {{ $zone->couleur }};"></span>
            </div>
            
            <!-- Informations de la zone -->
            <div class="info-container">
                <!-- Nom et slug -->
                <div class="info-item">
                    <div class="info-label">NOM</div>
                    <div class="info-value text-success">
                        {{ $zone->nom }}
                        @if($zone->slug)
                        <small class="text-muted d-block">{{ $zone->slug }}</small>
                        @endif
                    </div>
                </div>
                
                <!-- Description -->
                <div class="info-item">
                    <div class="info-label">DESCRIPTION</div>
                    <div class="info-value">
                        {{ Str::limit($zone->description_courte, 50) }}
                    </div>
                </div>
                
                <!-- Superficie -->
                <div class="info-item">
                    <div class="info-label">SUPERFICIE</div>
                    <div class="info-value text-primary">
                        {{ $zone->superficie ?? 'Non définie' }}
                    </div>
                </div>
                
                <!-- Arbres -->
                <div class="info-item">
                    <div class="info-label">ARBRES</div>
                    <div class="info-value">
                        <span class="badge-count">{{ $zone->nombre_arbres }}</span>
                    </div>
                </div>
                
                <!-- Statut -->
                <div class="info-item">
                    <div class="info-label">STATUT</div>
                    <div class="info-value">
                        @if($zone->est_active)
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-inactive">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="actions-container">
                @if($perms['voir'])
                <a href="{{ route('admin.zones.show', $zone->id) }}" 
                   class="btn-action btn-outline-primary" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                @endif
                
                @if($perms['modifier'])
                <a href="{{ route('admin.zones.edit', $zone->id) }}" 
                   class="btn-action btn-outline-success" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                @endif
                
                @if($perms['supprimer'])
                    @if($zone->nombre_arbres > 0)
                        <!-- Zone avec arbres : bouton avec alerte SweetAlert -->
                        <button type="button" 
                                class="btn-action btn-outline-danger" 
                                title="Suppression impossible"
                                onclick="showCannotDeleteAlert({{ $zone->nombre_arbres }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    @else
                        <!-- Zone sans arbres : formulaire de suppression avec confirmation -->
                        <form action="{{ route('admin.zones.destroy', $zone->id) }}" 
                              method="POST" class="d-inline form-delete-{{ $zone->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="btn-action btn-outline-danger" 
                                    title="Supprimer"
                                    onclick="confirmDelete({{ $zone->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-map-marked-alt fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Aucune zone trouvée</h5>
        @if($perms['creer'])
        <p class="mb-3">Commencez par créer votre première zone.</p>
        <a href="{{ route('admin.zones.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Nouvelle zone
        </a>
        @endif
    </div>
    @endforelse
</div>

@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if($perms['modifier'])
    $(function() {
        $("#sortable-zones").sortable({
            handle: ".handle",
            placeholder: "zone-card",
            opacity: 0.6,
            update: function(event, ui) {
                updateOrder();
            }
        });
    });

    function updateOrder() {
        var zones = [];
        $('.zone-card').each(function(index) {
            zones.push({
                id: $(this).data('id'),
                ordre: index
            });
        });

        $.ajax({
            url: '{{ route("admin.zones.reorder") }}',
            type: 'POST',
            data: {
                zones: zones,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Afficher une notification de succès (optionnel)
                Swal.fire({
                    icon: 'success',
                    title: 'Ordre mis à jour',
                    text: 'L\'ordre des zones a été réorganisé avec succès.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la réorganisation.',
                });
            }
        });
    }
    @endif

    function confirmDelete(zoneId) {
        Swal.fire({
            title: 'Confirmation de suppression',
            text: 'Êtes-vous sûr de vouloir supprimer cette zone ? Cette action est irréversible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('.form-delete-' + zoneId).submit();
            }
        });
    }

    function showCannotDeleteAlert(nbArbres) {
        Swal.fire({
            title: 'Suppression impossible',
            html: `Cette zone contient <strong>${nbArbres} arbre(s)</strong>.<br><br>Veuillez d'abord déplacer ou supprimer ces arbres avant de pouvoir supprimer la zone.`,
            icon: 'error',
            confirmButtonColor: '#1976D2',
            confirmButtonText: 'J\'ai compris'
        });
    }

    // Message d'information pour les utilisateurs (optionnel)
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ session('error') }}',
    });
    @endif
</script>
@endpush