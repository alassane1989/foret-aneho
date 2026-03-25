@extends('admin.layouts.admin')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@push('styles')
<style>
    .user-avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }
    .role-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 2px;
        background: #e9ecef;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.02);
    }
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .table thead th {
        background: #2E7D32;
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        margin: 0 2px;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .super-admin-badge {
        background: #dc3545;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 5px;
    }
    .create-button {
        background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
    }
    .create-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête avec bouton d'ajout (NOUVEAU) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Liste des utilisateurs</h4>
                    <p class="text-muted small mb-0">Gérez les comptes et les rôles des utilisateurs</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success create-button">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouvel utilisateur
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Recherche</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Nom, email..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="statut" class="form-label fw-bold">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="role" class="form-label fw-bold">Rôle</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                                    {{ $role->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="mb-3 w-100">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-white p-3 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Total utilisateurs</span>
                        <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 p-3 rounded">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white p-3 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Utilisateurs actifs</span>
                        <h3 class="mb-0">{{ \App\Models\User::where('actif', true)->count() }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white p-3 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Total rôles</span>
                        <h3 class="mb-0">{{ \App\Models\Role::count() }}</h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 p-3 rounded">
                        <i class="fas fa-tags fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Liste des utilisateurs</h5>
            <span class="badge bg-success">{{ $users->total() }} utilisateur(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôles</th>
                        <th>Statut</th>
                        <th>Dernière connexion</th>
                        <th>Inscription</th>
                        <th width="250">Actions</th>  {{-- Largeur augmentée pour plus de boutons --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->is_super_admin)
                                        <span class="super-admin-badge">Super Admin</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="role-badge" title="{{ $role->description ?? '' }}">
                                    {{ $role->nom }}
                                </span>
                            @empty
                                <span class="text-muted fst-italic">Aucun rôle</span>
                            @endforelse
                        </td>
                        <td>
                            @if($user->actif)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Actif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Inactif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->derniere_connexion)
                                {{ $user->derniere_connexion->diffForHumans() }}
                            @else
                                <span class="text-muted">Jamais</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                {{-- Bouton Voir détails (NOUVEAU) --}}
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn-action btn btn-sm btn-info" 
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                {{-- Bouton Gérer les rôles --}}
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn-action btn btn-sm btn-warning" 
                                   title="Gérer les rôles">
                                    <i class="fas fa-user-tag"></i>
                                </a>
                                
                                {{-- Bouton Activer/Désactiver (caché pour soi-même) --}}
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn-action btn btn-sm {{ $user->actif ? 'btn-secondary' : 'btn-success' }}"
                                                title="{{ $user->actif ? 'Désactiver' : 'Activer' }}"
                                                onclick="return confirm('Voulez-vous {{ $user->actif ? 'désactiver' : 'activer' }} le compte de {{ $user->name }} ?')">
                                            <i class="fas {{ $user->actif ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Bouton Supprimer (visible seulement pour Super Admin) (NOUVEAU) --}}
                                    @if(auth()->user()->is_super_admin)
                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-action btn btn-sm btn-danger"
                                                    title="Supprimer définitivement"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement {{ $user->name }} ? Cette action est irréversible.')">
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
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                            <p class="text-muted">Aucun utilisateur ne correspond à vos critères.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-success mt-3">
                                <i class="fas fa-plus"></i> Créer le premier utilisateur
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit lors du changement des filtres
        $('#statut, #role').change(function() {
            $('#filterForm').submit();
        });

        // Confirmation avant désactivation
        function confirmToggle(userName, isActif) {
            const action = isActif ? 'désactiver' : 'activer';
            return confirm(`Voulez-vous vraiment ${action} le compte de ${userName} ?`);
        }

        // Tooltips pour les boutons (si Bootstrap 5)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush