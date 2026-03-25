@extends('admin.layouts.admin')

@section('title', 'Gestion des Espèces - Administration')
@section('page-title', 'Gestion des Espèces')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
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
        transition: transform 0.3s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(46, 125, 50, 0.2);
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
    .badge-fruitier { color: var(--primary); border-color: var(--primary); }
    .badge-ornemental { color: var(--secondary); border-color: var(--secondary); }
    .badge-foret { color: var(--primary); border-color: var(--primary); }
    .badge-sacre { color: var(--warning); border-color: var(--warning); }
    .badge-medicinal { color: var(--danger); border-color: var(--danger); }
    
    /* ===== BADGES ORIGINE ===== */
    .badge-origine {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid;
        background: transparent;
    }
    .badge-origine.locale {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .badge-origine.introduite {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    /* ===== BADGES STATUT ===== */
    .badge-statut {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid;
        background: transparent;
    }
    .badge-statut.vulnerable {
        color: var(--danger);
        border-color: var(--danger);
    }
    .badge-statut.menace {
        color: var(--warning);
        border-color: var(--warning);
    }
    .badge-statut.non-defini {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    /* ===== BADGE COMPTEUR ===== */
    .badge-compteur {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid var(--primary);
        color: var(--primary);
        background: transparent;
    }
    
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
    
    .btn-outline-warning {
        color: var(--warning);
        border-color: var(--warning);
    }
    .btn-outline-warning:hover {
        background: var(--warning);
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
    
    /* ===== COULEURS DE TEXTE ===== */
    .text-success { color: var(--primary) !important; }
    .text-primary { color: var(--secondary) !important; }
    .text-danger { color: var(--danger) !important; }
    .text-warning { color: var(--warning) !important; }
    
    /* ===== TABLEAU ===== */
    .table thead th {
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 10px;
        color: var(--primary);
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
    
    /* ===== THUMBNAIL PAR DÉFAUT ===== */
    .thumbnail.bg-light {
        background: #f8f9fa !important;
        border: 2px dashed rgba(46, 125, 50, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* ===== BOUTON DISABLED ===== */
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 15px;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
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
            min-width: 800px;
        }
        
        .badge-compteur {
            display: inline-block;
            margin: 2px 0;
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
        'voir' => $user->aPermission('especes.voir'),
        'creer' => $user->aPermission('especes.creer'),
        'modifier' => $user->aPermission('especes.modifier'),
        'supprimer' => $user->aPermission('especes.supprimer'),
        'exporter' => $user->aPermission('especes.exporter'),
    ];
@endphp

<!-- En-tête -->
<div class="row mb-4 page-header">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">
            <i class="fas fa-leaf me-2 text-success"></i>
            Liste des espèces <span class="badge bg-success ms-2">{{ $especes->total() }}</span>
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
                        <a class="dropdown-item" href="{{ route('admin.especes.export.excel', request()->query()) }}">
                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.especes.export.pdf', request()->query()) }}">
                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- Bouton création -->
            @if($perms['creer'])
            <a href="{{ route('admin.especes.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle espèce
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
            <div class="stat-label">Total espèces</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['locales'] }}</div>
            <div class="stat-label">Espèces locales</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['introduites'] }}</div>
            <div class="stat-label">Espèces introduites</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['fruitier'] }}</div>
            <div class="stat-label">Espèces fruitières</div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-box">
    <form method="GET" action="{{ route('admin.especes.index') }}" class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-search me-1"></i>Recherche
            </label>
            <input type="text" name="search" class="form-control" placeholder="Nom scientifique ou local..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-tag me-1"></i>Catégorie
            </label>
            <select name="categorie" class="form-control">
                <option value="">Toutes</option>
                <option value="fruitier" {{ request('categorie') == 'fruitier' ? 'selected' : '' }}>Fruitier</option>
                <option value="ornemental" {{ request('categorie') == 'ornemental' ? 'selected' : '' }}>Ornemental</option>
                <option value="foret" {{ request('categorie') == 'foret' ? 'selected' : '' }}>Forêt</option>
                <option value="sacre" {{ request('categorie') == 'sacre' ? 'selected' : '' }}>Sacré</option>
                <option value="medicinal" {{ request('categorie') == 'medicinal' ? 'selected' : '' }}>Médicinal</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold text-primary">
                <i class="fas fa-globe-africa me-1"></i>Origine
            </label>
            <select name="origine" class="form-control">
                <option value="">Toutes</option>
                <option value="locale" {{ request('origine') == 'locale' ? 'selected' : '' }}>Locale</option>
                <option value="introduite" {{ request('origine') == 'introduite' ? 'selected' : '' }}>Introduite</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter me-2"></i>Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Tableau des espèces -->
<div class="table-container">
    <table class="table table-hover" id="especes-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Nom local</th>
                <th>Nom scientifique</th>
                <th>Catégorie</th>
                <th>Origine</th>
                <th>Statut</th>
                <th>Arbres</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($especes as $espece)
            <tr>
                <td>
                    @if($espece->image_principale)
                       <img src="{{ $espece->image_url }}" class="thumbnail" alt="{{ $espece->nom_local }}">
                    @else
                        <div class="thumbnail bg-light">
                            <i class="fas fa-leaf text-success opacity-50"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <strong class="text-success">{{ $espece->nom_local }}</strong>
                </td>
                <td>
                    <em class="text-primary">{{ $espece->nom_scientifique }}</em>
                </td>
                <td>
                    <span class="badge-categorie badge-{{ $espece->categorie }}">
                        {{ $espece->categorie_formatee }}
                    </span>
                </td>
                <td>
                    @if($espece->est_locale)
                        <span class="badge-origine locale">Locale</span>
                    @else
                        <span class="badge-origine introduite">Introduite</span>
                    @endif
                </td>
                <td>
                    @if($espece->statut_conservation)
                        @php
                            $statutClass = match($espece->statut_conservation) {
                                'Vulnérable', 'En danger' => 'vulnerable',
                                'Quasi menacé' => 'menace',
                                default => 'non-defini'
                            };
                        @endphp
                        <span class="badge-statut {{ $statutClass }}">
                            {{ $espece->statut_conservation }}
                        </span>
                    @else
                        <span class="badge-statut non-defini">Non défini</span>
                    @endif
                </td>
                <td>
                    <span class="badge-compteur">{{ $espece->nombre_arbres }}</span>
                </td>
                <td class="table-actions">
                    <div class="btn-group" role="group">
                        <!-- Voir -->
                        @if($perms['voir'])
                        <a href="{{ route('admin.especes.show', $espece->id) }}" 
                           class="btn btn-outline-primary btn-action" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        
                        <!-- Modifier -->
                        @if($perms['modifier'])
                        <a href="{{ route('admin.especes.edit', $espece->id) }}" 
                           class="btn btn-outline-success btn-action" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        <!-- Supprimer -->
                        @if($perms['supprimer'])
                            @if($espece->nombre_arbres > 0)
                                <!-- Espèce avec arbres : bouton avec message d'information -->
                                <button type="button" 
                                        class="btn btn-outline-danger btn-action" 
                                        title="Suppression impossible"
                                        onclick="showCannotDeleteAlert({{ $espece->nombre_arbres }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <!-- Espèce sans arbres : formulaire de suppression avec confirmation -->
                                <form action="{{ route('admin.especes.destroy', $espece->id) }}" 
                                      method="POST" class="d-inline form-delete-{{ $espece->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-action" 
                                            title="Supprimer"
                                            onclick="confirmDelete({{ $espece->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-leaf fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune espèce trouvée</h5>
                    @if($perms['creer'])
                    <p class="mb-3">Commencez par ajouter votre première espèce.</p>
                    <a href="{{ route('admin.especes.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une espèce
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
            Affichage de {{ $especes->firstItem() ?? 0 }} à {{ $especes->lastItem() ?? 0 }} sur {{ $especes->total() }} espèces
        </div>
        <div>
            {{ $especes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#especes-table').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ]
        });
        
        // Auto-submit des filtres
        $('#categorie, #origine').change(function() {
            $(this).closest('form').submit();
        });
    });

    function confirmDelete(especeId) {
        Swal.fire({
            title: 'Confirmation de suppression',
            text: 'Êtes-vous sûr de vouloir supprimer cette espèce ? Cette action est irréversible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('.form-delete-' + especeId).submit();
            }
        });
        
        return false;
    }

    function showCannotDeleteAlert(nbArbres) {
        Swal.fire({
            title: 'Suppression impossible',
            html: `Cette espèce est utilisée par <strong>${nbArbres} arbre(s)</strong>.<br><br>Veuillez d'abord supprimer ou modifier ces arbres avant de pouvoir supprimer l'espèce.`,
            icon: 'error',
            confirmButtonColor: '#1976D2',
            confirmButtonText: 'J\'ai compris'
        });
    }

    // Messages flash
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