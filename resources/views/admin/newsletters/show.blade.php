@extends('admin.layouts.admin')

@section('title', $abonne->nom . ' - Administration')
@section('page-title', 'Détail de l\'abonné')

@push('styles')
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
    
    .card-header.bg-success {
        background: var(--primary) !important;
    }
    
    .btn-outline-secondary {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-outline-secondary:hover {
        background: var(--secondary);
        color: white;
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
    
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #218838;
        border-color: #1e7e34;
    }
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #c82333;
        border-color: #bd2130;
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
    
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.15);
    }
    
    .card-body {
        padding: 25px;
    }
    
    .card-footer.bg-light {
        background-color: #f8f9fa !important;
        border-top: 1px solid rgba(40, 167, 69, 0.1);
        padding: 20px;
    }
    
    .table-borderless th {
        font-weight: 600;
        color: var(--secondary);
        width: 35%;
    }
    
    .table-borderless td {
        color: #333;
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
    
    a {
        color: var(--info);
        text-decoration: none;
    }
    a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    
    /* Style pour la date */
    .date-info {
        background: rgba(40, 167, 69, 0.05);
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .date-info i {
        color: var(--primary);
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('newsletter.voir'),
        'modifier' => $user->aPermission('newsletter.modifier'),
        'supprimer' => $user->aPermission('newsletter.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les détails des abonnés.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge bg-success me-2">ID: #{{ $abonne->id }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>{{ $abonne->nom }}
                </h5>
                <span class="badge bg-light text-success">
                    <i class="far fa-clock me-1"></i>
                    {{ $abonne->date_inscription->diffForHumans() }}
                </span>
            </div>
            <div class="card-body">
                <!-- Information compacte sur la date -->
                <div class="date-info">
                    <div class="row">
                        <div class="col-md-6">
                            <i class="fas fa-calendar-check me-2"></i>
                            <strong>Inscrit le :</strong> {{ $abonne->date_inscription->format('d/m/Y à H:i') }}
                        </div>
                        @if($abonne->date_desinscription)
                        <div class="col-md-6">
                            <i class="fas fa-calendar-times text-danger me-2"></i>
                            <strong>Désinscrit le :</strong> {{ $abonne->date_desinscription->format('d/m/Y à H:i') }}
                        </div>
                        @endif
                    </div>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <th><i class="fas fa-user text-success me-2"></i>Nom :</th>
                        <td class="fw-bold">{{ $abonne->nom }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-envelope text-success me-2"></i>Email :</th>
                        <td>
                            <a href="mailto:{{ $abonne->email }}" class="text-info">
                                <i class="fas fa-envelope me-1"></i>{{ $abonne->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-check-circle text-success me-2"></i>Statut :</th>
                        <td>
                            @if($abonne->est_actif)
                                <span class="badge-actif"><i class="fas fa-check-circle me-1"></i>Actif</span>
                                <small class="text-muted ms-2">(reçoit les newsletters)</small>
                            @else
                                <span class="badge-inactif"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                                <small class="text-muted ms-2">(ne reçoit pas les newsletters)</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar text-success me-2"></i>Inscrit depuis :</th>
                        <td>{{ $abonne->date_inscription->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
            
            @if($perms['modifier'] || $perms['supprimer'])
            <div class="card-footer bg-light">
                <div class="text-center">
                    @if($perms['modifier'])
                    <a href="{{ route('admin.newsletters.edit', $abonne->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    @endif
                    
                    @if($perms['modifier'])
                        @if($abonne->est_actif)
                        <form action="{{ route('admin.newsletters.unsubscribe', $abonne->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning me-2">
                                <i class="fas fa-user-slash me-2"></i>Désinscrire
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.newsletters.reactivate', $abonne->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-user-check me-2"></i>Réactiver
                            </button>
                        </form>
                        @endif
                    @endif
                    
                    @if($perms['supprimer'])
                    <form action="{{ route('admin.newsletters.destroy', $abonne->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Information supplémentaire -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x text-success me-3"></i>
                    <div>
                        <h6 class="mb-1">Statut de l'abonnement</h6>
                        <p class="text-muted small mb-0">
                            Les abonnés actifs reçoivent les newsletters. Vous pouvez modifier le statut depuis cette page.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet abonné ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush