@extends('admin.layouts.admin')

@section('title', 'Détails de ' . $user->name)
@section('page-title', 'Détails de : ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de l'utilisateur</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="user-avatar-lg mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #2E7D32, #1B5E20); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h3>{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>

                    <table class="table">
                        <tr>
                            <th width="200">ID</th>
                            <td>#{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($user->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Super Admin</th>
                            <td>
                                @if($user->is_super_admin)
                                    <span class="badge bg-danger">Oui</span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Rôles</th>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-info me-1">{{ $role->nom }}</span>
                                @empty
                                    <span class="text-muted">Aucun rôle</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Date d'inscription</th>
                            <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière mise à jour</th>
                            <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        @if($user->derniere_connexion)
                        <tr>
                            <th>Dernière connexion</th>
                            <td>{{ $user->derniere_connexion->format('d/m/Y à H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection