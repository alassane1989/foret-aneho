@extends('admin.layouts.admin')

@section('title', 'Gérer les rôles de ' . $user->name)
@section('page-title', 'Gérer les rôles de : ' . $user->name)

@push('styles')
<style>
    .user-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto;
    }
    .role-card {
        transition: all 0.3s;
        border: 2px solid transparent;
        cursor: pointer;
    }
    .role-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .role-card.selected {
        border-color: #2E7D32;
        background-color: #f0f9f0;
    }
    .role-card input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .permission-tag {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        margin: 2px;
        background: #e9ecef;
        color: #495057;
    }
    .warning-message {
        background-color: #fff3cd;
        border: 1px solid #ffe69c;
        color: #856404;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            {{-- Message d'avertissement pour les Admins --}}
            @if(!auth()->user()->is_super_admin && $user->is_super_admin)
                <div class="warning-message">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Vous ne pouvez pas modifier un Super Admin.
                </div>
            @endif

            {{-- Carte utilisateur --}}
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="user-avatar-lg mb-3">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    
                    @if($user->is_super_admin)
                        <span class="badge bg-danger">Super Admin</span>
                    @endif
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $user->actif ? 'success' : 'danger' }} me-2">
                            <i class="fas fa-circle me-1"></i>
                            {{ $user->actif ? 'Actif' : 'Inactif' }}
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-calendar me-1"></i>
                            Inscrit le {{ $user->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Formulaire d'assignation des rôles --}}
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2 text-success"></i>
                        Assigner des rôles à {{ $user->name }}
                    </h5>
                </div>
                
                <form action="{{ route('admin.users.update', $user) }}" method="POST" id="roleForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Sélectionnez les rôles à attribuer à cet utilisateur. 
                            Un utilisateur peut avoir plusieurs rôles.
                        </p>

                        <div class="row">
                            @forelse($roles as $role)
                                @php
                                    $canAssign = auth()->user()->is_super_admin || 
                                                 (auth()->user()->is_admin && $role->slug !== 'super-admin');
                                    $isChecked = in_array($role->id, $userRoles);
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class="card role-card {{ $isChecked ? 'selected' : '' }} 
                                         {{ !$canAssign ? 'opacity-50' : '' }}"
                                         onclick="{{ $canAssign ? 'toggleRole(' . $role->id . ')' : '' }}">
                                        
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           class="form-check-input role-checkbox" 
                                                           name="roles[]" 
                                                           value="{{ $role->id }}"
                                                           id="role_{{ $role->id }}"
                                                           {{ $isChecked ? 'checked' : '' }}
                                                           {{ !$canAssign ? 'disabled' : '' }}>
                                                    <label class="form-check-label fw-bold" 
                                                           for="role_{{ $role->id }}">
                                                        {{ $role->nom }}
                                                    </label>
                                                </div>
                                                
                                                @if($role->slug === 'super-admin')
                                                    <span class="badge bg-danger">Super Admin</span>
                                                @endif
                                            </div>

                                            @if($role->description)
                                                <p class="small text-muted mt-2 mb-2">
                                                    {{ $role->description }}
                                                </p>
                                            @endif

                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $role->users_count ?? 0 }} utilisateur(s)
                                                </small>
                                                <small class="text-muted ms-3">
                                                    <i class="fas fa-key me-1"></i>
                                                    {{ $role->permissions_count ?? 0 }} permission(s)
                                                </small>
                                            </div>

                                            {{-- Aperçu des permissions --}}
                                            @if($role->permissions->count() > 0)
                                                <div class="mt-2">
                                                    @foreach($role->permissions->take(3) as $permission)
                                                        <span class="permission-tag">
                                                            {{ $permission->nom }}
                                                        </span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 3)
                                                        <span class="permission-tag">
                                                            +{{ $role->permissions->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                                    <p>Aucun rôle disponible. Veuillez d'abord créer des rôles.</p>
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Créer un rôle
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        {{-- Récapitulatif des permissions --}}
                        @if(count($userRoles) > 0)
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Récapitulatif des permissions
                                </h6>
                                @php
                                    $allPermissions = collect();
                                    foreach($userRoles as $roleId) {
                                        $role = $roles->find($roleId);
                                        if ($role) {
                                            $allPermissions = $allPermissions->merge($role->permissions);
                                        }
                                    }
                                    $permissionsByGroup = $allPermissions->groupBy('groupe');
                                @endphp

                                @if($permissionsByGroup->isNotEmpty())
                                    @foreach($permissionsByGroup as $groupe => $permissions)
                                        <div class="mb-3">
                                            <span class="badge bg-secondary mb-2">{{ ucfirst($groupe) }}</span>
                                            <div>
                                                @foreach($permissions->unique('id') as $permission)
                                                    <span class="permission-tag bg-white border">
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        {{ $permission->nom }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Retour à la liste
                            </a>
                            
                            @if(!(!auth()->user()->is_super_admin && $user->is_super_admin))
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les rôles
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleRole(roleId) {
        const checkbox = document.getElementById('role_' + roleId);
        if (checkbox && !checkbox.disabled) {
            checkbox.checked = !checkbox.checked;
            // Mettre à jour la classe selected sur la carte
            const card = checkbox.closest('.role-card');
            if (card) {
                if (checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
        }
    }

    // Mettre à jour l'apparence des cartes quand on coche/décoche
    document.querySelectorAll('.role-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.role-card');
            if (card) {
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
        });
    });

    // Empêcher la propagation du clic depuis le label
    document.querySelectorAll('.form-check-label').forEach(label => {
        label.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Confirmation avant soumission pour les actions sensibles
    document.getElementById('roleForm')?.addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.role-checkbox:checked');
        if (checkboxes.length === 0) {
            if (!confirm('Aucun rôle sélectionné. Voulez-vous vraiment enlever tous les rôles de cet utilisateur ?')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush