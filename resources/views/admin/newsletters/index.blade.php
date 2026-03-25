@extends('admin.layouts.admin')

@section('title', 'Gestion de la Newsletter - Administration')
@section('page-title', 'Gestion des abonnés')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    :root {
        --primary: #28a745;
        --secondary: #6c757d;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --success-light: #d4edda;
        --danger-light: #f8d7da;
    }
    
    .table-actions {
        white-space: nowrap;
        width: 1%;
    }
    
    .filter-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid rgba(40, 167, 69, 0.1);
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border: 1px solid rgba(40, 167, 69, 0.1);
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        border-color: var(--primary);
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
    }
    .stat-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .checkbox-column {
        width: 40px;
        text-align: center;
    }
    
    .badge-actif {
        background: transparent;
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid var(--primary);
        display: inline-block;
    }
    .badge-inactif {
        background: transparent;
        color: var(--danger);
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid var(--danger);
        display: inline-block;
    }
    
    /* Boutons */
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #218838;
        border-color: #1e7e34;
    }
    
    .btn-primary {
        background: var(--info);
        border-color: var(--info);
    }
    .btn-primary:hover {
        background: #138496;
        border-color: #117a8b;
    }
    
    .btn-warning {
        background: var(--warning);
        border-color: var(--warning);
        color: #212529;
    }
    .btn-warning:hover {
        background: #e0a800;
        border-color: #d39e00;
        color: #212529;
    }
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #c82333;
        border-color: #bd2130;
    }
    
    .btn-secondary {
        background: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
    }
    
    .btn-info {
        background: var(--info);
        border-color: var(--info);
        color: white;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #117a8b;
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
    .btn-outline-danger:hover {
        background: var(--danger);
        color: white;
    }
    
    .btn-outline-secondary {
        color: var(--secondary);
        border-color: var(--secondary);
        background: transparent;
    }
    .btn-outline-secondary:hover {
        background: var(--secondary);
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
    
    .btn-group .btn-action {
        margin: 0;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
        padding: 12px 8px;
    }
    
    .table tbody tr:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .pagination .page-link {
        color: var(--primary);
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    
    .pagination .active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .pagination .page-link:hover {
        background: rgba(40, 167, 69, 0.1);
        color: #1e7e34;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    #massActionsPanel {
        border: 1px solid var(--primary);
        border-radius: 10px;
    }
    
    #massActionsPanel .bg-light {
        background-color: rgba(40, 167, 69, 0.05) !important;
        border-radius: 10px;
    }
    
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
        
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            min-width: 800px;
        }
    }
    
    /* Style pour le modal */
    .modal-header.bg-success {
        background: var(--primary) !important;
    }
    
    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }
    
    .alert-info {
        background: rgba(23, 162, 184, 0.1);
        border: 1px solid var(--info);
        color: #0c5460;
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('newsletter.voir'),
        'creer' => $user->aPermission('newsletter.creer'),
        'modifier' => $user->aPermission('newsletter.modifier'),
        'supprimer' => $user->aPermission('newsletter.supprimer'),
        'exporter' => $user->aPermission('newsletter.exporter'),
        'envoyer' => $user->aPermission('newsletter.envoyer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de gérer la newsletter.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
        </a>
    </div>
@else

<!-- En-tête -->
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">
            <i class="fas fa-users me-2 text-success"></i>
            Abonnés à la newsletter <span class="badge bg-success ms-2">{{ $abonnes->total() }}</span>
        </h4>
        <div class="mt-2 mt-md-0">
            <!-- Boutons d'export -->
            @if($perms['exporter'])
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Exporter
                </button>
                <ul class="dropdown-menu">
                    {{--<li><a class="dropdown-item" href="{{ route('admin.newsletters.export.excel') }}">Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.newsletters.export.pdf') }}">PDF</a></li>--}}
                    <li><a class="dropdown-item" href="{{ route('admin.newsletters.export.csv') }}">
                        <i class="fas fa-file-csv me-2 text-success"></i>CSV
                    </a></li>
                </ul>
            </div>
            @endif
            
            <!-- Bouton envoi newsletter -->
            @if($perms['envoyer'])
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#sendNewsletterModal">
                <i class="fas fa-paper-plane me-2"></i>Envoyer newsletter
            </button>
            @endif
            
            <!-- Bouton ajout manuel -->
            @if($perms['creer'])
            <a href="{{ route('admin.newsletters.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus-circle me-2"></i>Ajouter manuellement
            </a>
            @endif
            
            <!-- Bouton actions groupées -->
            <button class="btn btn-secondary" id="massActionsBtn" disabled onclick="showMassActions()">
                <i class="fas fa-tasks me-2"></i>Actions groupées
            </button>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['actifs'] }}</div>
            <div class="stat-label">Actifs</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['inactifs'] }}</div>
            <div class="stat-label">Inactifs</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['ce_mois'] }}</div>
            <div class="stat-label">Inscrits ce mois</div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-box">
    <form method="GET" action="{{ route('admin.newsletters.index') }}" id="filterForm" class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-search me-1"></i>Recherche
            </label>
            <input type="text" name="search" class="form-control" placeholder="Nom ou email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-filter me-1"></i>Statut
            </label>
            <select name="statut" class="form-control">
                <option value="">Tous</option>
                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold text-success">
                <i class="fas fa-sort me-1"></i>Tri
            </label>
            <select name="tri" class="form-control">
                <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                <option value="ancien" {{ request('tri') == 'ancien' ? 'selected' : '' }}>Plus ancien</option>
            </select>
        </div>
    </form>
</div>

<!-- Actions groupées (visible seulement si permissions) -->
@if($perms['modifier'] || $perms['supprimer'])
<div class="card mb-4" id="massActionsPanel" style="display: none;">
    <div class="card-body bg-light">
        <div class="row align-items-center">
            <div class="col-md-6">
                <strong><span id="selectedCount">0</span></strong> abonné(s) sélectionné(s)
            </div>
            <div class="col-md-6 text-end">
                @if($perms['modifier'])
                <form action="{{ route('admin.newsletters.mass-unsubscribe') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="massIdsUnsubscribe" value="">
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Désinscrire tous les abonnés sélectionnés ?')">
                        <i class="fas fa-user-slash me-2"></i>Désinscrire
                    </button>
                </form>
                @endif
                
                @if($perms['supprimer'])
                <form action="{{ route('admin.newsletters.mass-destroy') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="massIdsDelete" value="">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer définitivement tous les abonnés sélectionnés ?')">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
                @endif
                
                <button class="btn btn-secondary" onclick="clearSelection()">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tableau des abonnés -->
<div class="table-container">
    <table class="table table-hover" id="newsletters-table">
        <thead>
            <tr>
                @if($perms['modifier'] || $perms['supprimer'])
                <th class="checkbox-column">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                </th>
                @endif
                <th>Nom</th>
                <th>Email</th>
                <th>Date inscription</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonnes as $abonne)
            <tr>
                @if($perms['modifier'] || $perms['supprimer'])
                <td class="checkbox-column">
                    <input type="checkbox" class="abonne-checkbox" value="{{ $abonne->id }}" onchange="updateMassActions()">
                </td>
                @endif
                <td>
                    <strong class="text-success">{{ $abonne->nom }}</strong>
                </td>
                <td>
                    <a href="mailto:{{ $abonne->email }}" class="text-info">
                        <i class="fas fa-envelope me-1"></i>{{ $abonne->email }}
                    </a>
                </td>
                <td>
                    <i class="far fa-calendar-alt text-success me-1"></i>{{ $abonne->date_inscription->format('d/m/Y') }}
                </td>
                <td>
                    @if($abonne->est_actif)
                        <span class="badge-actif"><i class="fas fa-check-circle me-1"></i>Actif</span>
                    @else
                        <span class="badge-inactif"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                        @if($abonne->date_desinscription)
                            <br><small class="text-muted">désinscrit le {{ $abonne->date_desinscription->format('d/m/Y') }}</small>
                        @endif
                    @endif
                </td>
                <td class="table-actions">
                    <div class="btn-group" role="group">
                        <!-- Voir -->
                        @if($perms['voir'])
                        <a href="{{ route('admin.newsletters.show', $abonne->id) }}" 
                           class="btn btn-outline-primary btn-action" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        
                        <!-- Modifier -->
                        @if($perms['modifier'])
                        <a href="{{ route('admin.newsletters.edit', $abonne->id) }}" 
                           class="btn btn-outline-success btn-action" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        <!-- Désinscrire/Réactiver -->
                        @if($perms['modifier'])
                            @if($abonne->est_actif)
                            <form action="{{ route('admin.newsletters.unsubscribe', $abonne->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-action" title="Désinscrire">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.newsletters.reactivate', $abonne->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-action" title="Réactiver">
                                    <i class="fas fa-user-check"></i>
                                </button>
                            </form>
                            @endif
                        @endif
                        
                        <!-- Supprimer -->
                        @if($perms['supprimer'])
                        <form action="{{ route('admin.newsletters.destroy', $abonne->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-action" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ ($perms['modifier'] || $perms['supprimer']) ? 6 : 5 }}" class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun abonné trouvé</h5>
                    @if($perms['creer'])
                    <p class="mb-3">Commencez par ajouter votre premier abonné.</p>
                    <a href="{{ route('admin.newsletters.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un abonné
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
            Affichage de {{ $abonnes->firstItem() ?? 0 }} à {{ $abonnes->lastItem() ?? 0 }} sur {{ $abonnes->total() }} abonnés
        </div>
        <div>
            {{ $abonnes->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal d'envoi de newsletter -->
@if($perms['envoyer'])
<div class="modal fade" id="sendNewsletterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer une newsletter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.newsletters.send') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette newsletter sera envoyée à <strong>{{ $stats['actifs'] }}</strong> abonnés actifs.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                        <input type="text" name="sujet" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                        <textarea name="contenu" class="form-control" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endif
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#newsletters-table').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            columnDefs: [
                { orderable: false, targets: [0, {{ ($perms['modifier'] || $perms['supprimer']) ? 5 : 4 }}] }
            ]
        });
        
        // Auto-submit des filtres
        $('#statut, #tri').change(function() {
            $('#filterForm').submit();
        });
    });

    let selectedAbonnes = [];

    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Sélection multiple
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll').checked;
        document.querySelectorAll('.abonne-checkbox').forEach(checkbox => {
            checkbox.checked = selectAll;
        });
        updateMassActions();
    }

    function updateMassActions() {
        selectedAbonnes = [];
        document.querySelectorAll('.abonne-checkbox:checked').forEach(checkbox => {
            selectedAbonnes.push(checkbox.value);
        });
        
        const count = selectedAbonnes.length;
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('massIdsUnsubscribe').value = JSON.stringify(selectedAbonnes);
        document.getElementById('massIdsDelete').value = JSON.stringify(selectedAbonnes);
        
        document.getElementById('massActionsBtn').disabled = count === 0;
        document.getElementById('massActionsPanel').style.display = count > 0 ? 'block' : 'none';
    }

    function showMassActions() {
        document.getElementById('massActionsPanel').style.display = 'block';
    }

    function clearSelection() {
        document.querySelectorAll('.abonne-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        if (document.getElementById('selectAll')) {
            document.getElementById('selectAll').checked = false;
        }
        updateMassActions();
    }
</script>
@endpush