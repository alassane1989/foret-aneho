@extends('admin.layouts.admin')

@section('title', 'Modifier ' . $user->name)
@section('page-title', 'Modifier : ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Modifier les informations</h5>
                </div>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rôles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    @php
                                        $canAssign = auth()->user()->is_super_admin || 
                                                     (auth()->user()->is_admin && $role->slug !== 'super-admin');
                                    @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               name="roles[]" value="{{ $role->id }}"
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                               {{ !$canAssign ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->nom }}
                                            @if($role->slug === 'super-admin')
                                                <span class="badge bg-danger">Super Admin</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if(!auth()->user()->is_super_admin)
                                <small class="text-muted">Note : Vous ne pouvez pas attribuer le rôle Super Admin.</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="actif" name="actif" 
                                       {{ old('actif', $user->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Compte actif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection