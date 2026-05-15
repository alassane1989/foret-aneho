@extends('admin.layouts.admin')

@section('title', 'Gestion des Actualités - Administration')
@section('page-title', 'Gestion des Actualités')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --purple: #9C27B0;        /* Violet pour partenariat */
        --info: #17a2b8;          /* Bleu info pour newsletter */
    }
    
    /* ===== STYLES GÉNÉRAUX ===== */
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
    
    /* ===== STATISTIQUES ===== */
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid rgba(46, 125, 50, 0.1);
        transition: all 0.3s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(46,125,50,0.1);
        border-color: var(--primary);
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
    }
    .stat-label {
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ===== BADGES CATÉGORIE ===== */
    .badge-categorie {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid;
        background: transparent;
    }
    .badge-plantation { color: var(--primary); border-color: var(--primary); }
    .badge-education { color: var(--secondary); border-color: var(--secondary); }
    .badge-infrastructure { color: var(--warning); border-color: var(--warning); }
    .badge-partenariat { color: var(--purple); border-color: var(--purple); }
    .badge-evenement { color: var(--danger); border-color: var(--danger); }
    
    /* ===== BADGES STATUT ===== */
    .status-badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid;
        background: transparent;
    }
    .status-publie { color: var(--primary); border-color: var(--primary); }
    .status-brouillon { color: var(--danger); border-color: var(--danger); }
    
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
    
    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
    }
    
    /* ===== BOUTON NEWSLETTER ===== */
    .btn-newsletter {
        color: var(--info);
        border-color: var(--info);
    }
    .btn-newsletter:hover {
        background: var(--info);
        color: white;
    }
    
    /* ===== BOUTONS D'ACTION - FORMAT 2x2 ===== */
    .actions-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 68px;
    }
    
    .actions-row {
        display: flex;
        justify-content: center;
        gap: 4px;
    }
    
    .btn-action, .btn-publish, .btn-newsletter-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid;
        background: transparent;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    
    /* Styles spécifiques pour chaque type de bouton */
    .btn-action.btn-voir {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-action.btn-voir:hover {
        background: var(--secondary);
        color: white;
    }
    
    .btn-action.btn-modifier {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-action.btn-modifier:hover {
        background: var(--primary);
        color: white;
    }
    
    .btn-action.btn-supprimer {
        color: var(--danger);
        border-color: var(--danger);
    }
    .btn-action.btn-supprimer:hover {
        background: var(--danger);
        color: white;
    }
    
    /* Bouton Newsletter */
    .btn-newsletter-action {
        color: var(--info);
        border-color: var(--info);
    }
    .btn-newsletter-action:hover {
        background: var(--info);
        color: white;
    }
    
    /* Bouton Publier/Dépublier */
    .btn-publish.published {
        color: var(--warning);
        border-color: var(--warning);
    }
    .btn-publish.published:hover {
        background: var(--warning);
        color: white;
    }
    
    .btn-publish.draft {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-publish.draft:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Pour les formulaires inline */
    .d-inline {
        display: inline-flex !important;
    }
    
    /* ===== COULEURS DE TEXTE ===== */
    .text-success { color: var(--primary) !important; }
    .text-primary { color: var(--secondary) !important; }
    .text-danger { color: var(--danger) !important; }
    .text-warning { color: var(--warning) !important; }
    .text-info { color: var(--info) !important; }
    
    /* ===== TABLEAU ===== */
    .table thead th {
        background: var(--primary) !important;
        color: white !important;
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 10px;
    }
    
    .table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
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
    
    /* ===== BADGE COMPTEUR VUES ===== */
    .badge-vues {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid var(--secondary);
        color: var(--secondary);
        background: transparent;
    }
    
    /* ===== EN-TÊTE DE PAGE ===== */
    .page-header {
        margin-bottom: 20px;
    }
    
    .page-header h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    /* ===== RESPONSIVITÉ ===== */
    @media (max-width: 1200px) {
        .btn-action, .btn-publish, .btn-newsletter-action {
            width: 30px;
            height: 30px;
        }
    }
    
    @media (max-width: 992px) {
        .btn-action, .btn-publish, .btn-newsletter-action {
            width: 28px;
            height: 28px;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 15px;
        }
        
        .btn-action, .btn-publish, .btn-newsletter-action {
            width: 26px;
            height: 26px;
            font-size: 0.8rem;
        }
        
        .page-header .col-12 {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .page-header .col-12 > div {
            margin-top: 10px;
            width: 100%;
        }
        
        .page-header .btn {
            width: 100%;
            margin: 2px 0 !important;
        }
        
        .filter-box .row > div {
            margin-bottom: 10px;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            min-width: 900px;
        }
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
        'voir' => $user->aPermission('actualites.voir'),
        'creer' => $user->aPermission('actualites.creer'),
        'modifier' => $user->aPermission('actualites.modifier'),
        'publier' => $user->aPermission('actualites.publier'),
        'supprimer' => $user->aPermission('actualites.supprimer'),
        'exporter' => $user->aPermission('actualites.exporter'),
    ];
@endphp

<!-- En-tête -->
<div class="row mb-4 page-header">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">
            <i class="fas fa-newspaper me-2 text-success"></i>
            Liste des actualités <span class="badge bg-success ms-2">{{ $actualites->total() }}</span>
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
                        <a class="dropdown-item" href="{{ route('admin.actualites.export.excel', request()->query()) }}">
                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.actualites.export.pdf', request()->query()) }}">
                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- Bouton création -->
            @if($perms['creer'])
            <a href="{{ route('admin.actualites.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle actualité
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total actualités</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['publiees'] }}</div>
            <div class="stat-label">Publiées</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['brouillons'] }}</div>
            <div class="stat-label">Brouillons</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['plantation'] }}</div>
            <div class="stat-label">Plantations</div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-box">
    <form method="GET" action="{{ route('admin.actualites.index') }}" class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-search me-1"></i>Recherche
            </label>
            <input type="text" name="search" class="form-control" placeholder="Titre, description..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-tag me-1"></i>Catégorie
            </label>
            <select name="categorie" class="form-control">
                <option value="">Toutes</option>
                <option value="plantation" {{ request('categorie') == 'plantation' ? 'selected' : '' }}>Plantation</option>
                <option value="education" {{ request('categorie') == 'education' ? 'selected' : '' }}>Éducation</option>
                <option value="infrastructure" {{ request('categorie') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                <option value="partenariat" {{ request('categorie') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                <option value="evenement" {{ request('categorie') == 'evenement' ? 'selected' : '' }}>Événement</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-circle me-1"></i>Statut
            </label>
            <select name="statut" class="form-control">
                <option value="">Tous</option>
                <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter me-2"></i>Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Tableau des actualités -->
<div class="table-container">
    <table class="table table-hover" id="actualites-table">
        <thead>
            32
                <th>Image</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Vues</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actualites as $actualite)
            <tr>
                <td>
                    @if($actualite->image_principale)
                        <img src="{{ $actualite->image_url }}" class="thumbnail" alt="{{ $actualite->titre }}">
                    @else
                        <div class="thumbnail bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-success opacity-50"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <strong class="text-success">{{ $actualite->titre }}</strong><br>
                    <small class="text-muted">{{ Str::limit($actualite->description_courte, 50) }}</small>
                </td>
                <td>
                    <span class="badge-categorie badge-{{ $actualite->categorie }}">
                        {{ $actualite->categorie_formatee }}
                    </span>
                </td>
                <td>
                    <span class="text-primary">{{ $actualite->auteur_nom }}</span>
                </td>
                <td>
                    <i class="far fa-calendar me-1 text-primary"></i>
                    <span>{{ $actualite->date_publication->format('d/m/Y') }}</span>
                </td>
                <td>
                    <span class="badge-vues">{{ $actualite->vues }}</span>
                </td>
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
                <td class="table-actions">
                    <div class="actions-wrapper">
                        <!-- Première ligne : 2 boutons -->
                        <div class="actions-row">
                            @if($perms['voir'])
                            <a href="{{ route('admin.actualites.show', $actualite->id) }}" 
                               class="btn-action btn-voir" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            
                            @if($perms['modifier'])
                            <a href="{{ route('admin.actualites.edit', $actualite->id) }}" 
                               class="btn-action btn-modifier" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </div>
                        
                        <!-- Deuxième ligne : 3 boutons (ajout du bouton newsletter) -->
                        <div class="actions-row">
                            @if($perms['publier'])
                            <form action="{{ route('admin.actualites.toggle-status', $actualite->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn-publish {{ $actualite->est_publie ? 'published' : 'draft' }}" 
                                        title="{{ $actualite->est_publie ? 'Dépublier' : 'Publier' }}">
                                    <i class="fas fa-{{ $actualite->est_publie ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            @endif
                            
                            <!-- AJOUT : BOUTON ENVOI NEWSLETTER -->
                            @if($perms['modifier'] && $actualite->est_publie)
                            <form action="{{ route('admin.actualites.send-to-newsletter', $actualite->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn-newsletter-action" 
                                        title="Envoyer aux abonnés de la newsletter"
                                        onclick="return confirm('Envoyer cette actualité à tous les abonnés de la newsletter ?\n\n{{ $actualite->titre }}\n\nCette action enverra un email à tous les abonnés actifs.')">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                            @endif
                            <!-- FIN AJOUT -->
                            
                            @if($perms['supprimer'])
                            <form action="{{ route('admin.actualites.destroy', $actualite->id) }}" 
                                  method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-supprimer" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-success mb-3 opacity-50"></i>
                    <h5 class="text-muted">Aucune actualité trouvée</h5>
                    @if($perms['creer'])
                    <p class="mb-3">Commencez par créer votre première actualité.</p>
                    <a href="{{ route('admin.actualites.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle actualité
                    </a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Affichage de {{ $actualites->firstItem() ?? 0 }} à {{ $actualites->lastItem() ?? 0 }} sur {{ $actualites->total() }} actualités
        </div>
        <div>
            {{ $actualites->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#actualites-table').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ]
        });
        
        // Auto-submit des filtres
        $('#categorie, #statut').change(function() {
            $(this).closest('form').submit();
        });
    });

    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush