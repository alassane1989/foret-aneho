@extends('admin.layouts.admin')

@section('title', 'Gestion des rôles')
@section('page-title', 'Gestion des rôles')

@push('styles')
<style>
    .role-card {
        transition: all 0.3s;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(46,125,50,0.1);
    }
    .role-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    .permission-badge {
        background: #e9ecef;
        color: #495057;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 2px;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header avec bouton d'ajout -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Liste des rôles</h4>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouveau rôle
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des rôles -->
    <div class="row">
        @forelse($roles as $role)
        <div class="col-md-4 mb-4">
            <div class="card role-card">
                <div class="role-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $role->nom }}</h5>
                            <p class="mb-0 small opacity-75">{{ $role->slug }}</p>
                        </div>
                        @if($role->est_defaut)
                            <span class="badge bg-warning">Par défaut</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">{{ $role->description ?? 'Aucune description' }}</p>
                    
                    <div class="mb-3">
                        <strong>Statistiques :</strong>
                        <div class="d-flex gap-3 mt-2">
                            <div>
                                <span class="badge bg-info">{{ $role->users_count }} utilisateur(s)</span>
                            </div>
                            <div>
                                <span class="badge bg-success">{{ $role->permissions_count }} permission(s)</span>
                            </div>
                            <div>
                                <span class="badge bg-secondary">Niv. {{ $role->niveau }}</span>
                            </div>
                        </div>
                    </div>

                    @if($role->permissions->count() > 0)
                    <div class="mb-3">
                        <strong>Aperçu des permissions :</strong>
                        <div class="mt-2">
                            @foreach($role->permissions->take(3) as $permission)
                                <span class="permission-badge">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    {{ $permission->nom }}
                                </span>
                            @endforeach
                            @if($role->permissions->count() > 3)
                                <span class="permission-badge">+{{ $role->permissions->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    {{ $role->users_count > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-users-cog fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun rôle créé</h5>
                <p>Commencez par créer votre premier rôle.</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un rôle
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection