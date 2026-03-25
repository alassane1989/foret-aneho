@extends('admin.layouts.admin')

@section('title', 'Gestion des Arbres - Administration')
@section('page-title', 'Gestion des Arbres')

@push('styles')
<style>
    /* ===== STYLES GÉNÉRAUX ===== */
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
    }
    
    .table-actions {
        white-space: nowrap;
        width: 1%;
    }
    
    .thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--primary);
    }
    
    .filter-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid rgba(46, 125, 50, 0.1);
    }
    
    /* ===== BADGES DE SANTÉ ===== */
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
    
    /* ===== BOUTONS ===== */
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #1B5E20;
        border-color: #1B5E20;
    }
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #B71C1C;
        border-color: #B71C1C;
    }
    
    .btn-primary, .btn-info {
        background: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-primary:hover, .btn-info:hover {
        background: #0D47A1;
        border-color: #0D47A1;
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
    
    .btn-outline-success {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-outline-success:hover {
        background: var(--primary);
        color: white;
    }
    
    .btn-outline-primary {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-outline-primary:hover {
        background: var(--secondary);
        color: white;
    }
    
    .btn-outline-danger {
        color: var(--danger);
        border-color: var(--danger);
    }
    .btn-outline-danger:hover {
        background: var(--danger);
        color: white;
    }
    
    .btn-action {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        border-radius: 8px;
    }
    
    /* ===== BADGES ===== */
    .badge.bg-primary {
        background: var(--secondary) !important;
        color: white;
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .badge.bg-success {
        background: var(--primary) !important;
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
    
    /* ===== COULEURS DE TEXTE ===== */
    .text-success { color: var(--primary) !important; }
    .text-primary { color: var(--secondary) !important; }
    .text-danger { color: var(--danger) !important; }
    .text-warning { color: var(--warning) !important; }
    
    /* ===== TABLEAU ===== */
    .table thead th {
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
    }
    
    .table tbody tr:hover {
        background: rgba(46, 125, 50, 0.05);
    }
    
    /* ===== PAGINATION ===== */
    .pagination .page-link {
        color: var(--primary);
        border: 1px solid rgba(46, 125, 50, 0.2);
    }
    
    .pagination .active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .pagination .page-link:hover {
        background: rgba(46, 125, 50, 0.1);
        color: #1B5E20;
    }
    
    /* ===== FORMULAIRES ===== */
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    /* ===== BOUTONS D'EXPORT ===== */
    .btn-group .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .dropdown-item:hover {
        background: rgba(46, 125, 50, 0.1);
    }
    
    .dropdown-item i {
        width: 20px;
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('arbres.voir'),
        'creer' => $user->aPermission('arbres.creer'),
        'modifier' => $user->aPermission('arbres.modifier'),
        'supprimer' => $user->aPermission('arbres.supprimer'),
        'exporter' => $user->aPermission('arbres.exporter'),
    ];
@endphp

<!-- En-tête -->
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                Liste des arbres <span class="badge bg-success ms-2">{{ $arbres->total() }}</span>
            </h4>
            <p class="text-muted small mb-0">Gérez l'inventaire des arbres de la forêt</p>
        </div>
        <div>
            <!-- Boutons d'export - visible seulement avec permission -->
            @if($perms['exporter'])
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Exporter
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.arbres.export.excel', request()->query()) }}">
                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.arbres.export.pdf', request()->query()) }}">
                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- Bouton création - visible seulement avec permission -->
            @if($perms['creer'])
            <a href="{{ route('admin.arbres.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Nouvel arbre
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-box">
    <form method="GET" action="{{ route('admin.arbres.index') }}" class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-search me-1"></i>Recherche
            </label>
            <input type="text" name="search" class="form-control" 
                   placeholder="Nom de l'arbre..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-map-marked-alt me-1"></i>Zone
            </label>
            <select name="zone" class="form-control">
                <option value="">Toutes les zones</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ request('zone') == $zone->id ? 'selected' : '' }}>
                        {{ $zone->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-leaf me-1"></i>Espèce
            </label>
            <select name="espece" class="form-control">
                <option value="">Toutes les espèces</option>
                @foreach($especes as $espece)
                    <option value="{{ $espece->id }}" {{ request('espece') == $espece->id ? 'selected' : '' }}>
                        {{ $espece->nom_local }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter me-2"></i>Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Tableau des arbres -->
<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover" id="arbres-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Zone</th>
                    <th>Plantation</th>
                    <th>Planteur</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arbres as $arbre)
                <tr>
                    <td>
                        <img src="{{ $arbre->photo_url }}" class="thumbnail" alt="{{ $arbre->nom }}">
                    </td>
                    <td>
                        <strong class="text-success">{{ $arbre->nom }}</strong><br>
                        <small class="text-muted">{{ $arbre->slug }}</small>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $arbre->espece->nom_local }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background-color: {{ $arbre->zone->couleur }}; color: white;">
                            {{ $arbre->zone->nom }}
                        </span>
                    </td>
                    <td>
                        <span class="text-primary">{{ $arbre->date_plantation->format('d/m/Y') }}</span><br>
                        <small class="text-muted">{{ $arbre->age }}</small>
                    </td>
                    <td>
                        @if($arbre->planteur_photo)
                            <img src="{{ $arbre->planteur_photo_url }}" class="rounded-circle me-1" style="width: 25px; height: 25px; object-fit: cover; border: 2px solid var(--primary);">
                        @else
                            <i class="fas fa-user-circle text-success me-1"></i>
                        @endif
                        <span class="text-primary">{{ $arbre->planteur_nom }}</span>
                    </td>
                    <td>
                        <span class="badge-sante badge-{{ $arbre->etat_sante }}">
                            {{ ucfirst($arbre->etat_sante) }}
                        </span>
                    </td>
                    <td class="table-actions">
                        <div class="btn-group" role="group">
                            <!-- Voir - toujours visible si on a la permission de voir -->
                            @if($perms['voir'])
                            <a href="{{ route('admin.arbres.show', $arbre->id) }}" 
                               class="btn btn-outline-primary btn-action" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif

                            
                         <!-- GÉRER LES IMAGES - NOUVEAU BOUTON -->
        <a href="{{ route('admin.arbres.images.index', $arbre) }}" 
           class="btn btn-outline-info btn-action" 
           title="Gérer les images"
           style="color: #17a2b8; border-color: #17a2b8;">
            <i class="fas fa-images"></i>
        </a>
        

                            <!-- Modifier - visible seulement avec permission -->
                            @if($perms['modifier'])
                            <a href="{{ route('admin.arbres.edit', $arbre->id) }}" 
                               class="btn btn-outline-success btn-action" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            
                            <!-- Supprimer - visible seulement avec permission -->
                            @if($perms['supprimer'])
                            <form action="{{ route('admin.arbres.destroy', $arbre->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-action" 
                                        title="Supprimer"
                                        onclick="return confirm('Supprimer cet arbre ? Cette action est irréversible.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="text-success mb-3">
                            <i class="fas fa-tree fa-4x"></i>
                        </div>
                        <h5 class="text-muted">Aucun arbre trouvé</h5>
                        @if($perms['creer'])
                        <p class="mb-3">Commencez par ajouter votre premier arbre.</p>
                        <a href="{{ route('admin.arbres.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter un arbre
                        </a>
                        @else
                        <p class="mb-0 text-muted">Aucun arbre ne correspond aux critères</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Affichage de {{ $arbres->firstItem() ?? 0 }} à {{ $arbres->lastItem() ?? 0 }} sur {{ $arbres->total() }} arbres
        </div>
        <div>
            {{ $arbres->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit des filtres
        $('#zone, #espece').change(function() {
            $(this).closest('form').submit();
        });
    });

    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet arbre ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush