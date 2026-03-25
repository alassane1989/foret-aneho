@extends('admin.layouts.admin')

@section('title', 'Message de ' . $message->nom . ' - Administration')
@section('page-title', 'Détail du message')

@push('styles')
<style>
    :root {
        --primary: #28a745;
        --secondary: #6c757d;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
    }
    
    .card-header.bg-success {
        background: var(--primary) !important;
    }
    
    .card-header.bg-info {
        background: var(--info) !important;
    }
    
    .badge.bg-secondary {
        background: var(--secondary) !important;
        color: white;
    }
    
    .badge.bg-warning {
        background: var(--warning) !important;
        color: #212529;
    }
    
    .btn-primary {
        background: var(--info);
        border-color: var(--info);
    }
    .btn-primary:hover {
        background: #138496;
        border-color: #117a8b;
    }
    
    .btn-info {
        background: var(--info);
        border-color: var(--info);
        color: white;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #117a8b;
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
    
    .btn-outline-secondary {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-outline-secondary:hover {
        background: var(--secondary);
        color: white;
    }
    
    .bg-light.rounded-3 {
        background-color: #f8f9fa !important;
        border-left: 4px solid var(--primary);
        padding: 1.5rem !important;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .table-borderless th {
        font-weight: 600;
        color: var(--secondary);
    }
    
    .table-borderless td {
        color: #333;
    }
    
    .lead {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--primary);
    }
    
    a {
        color: var(--info);
        text-decoration: none;
    }
    
    a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $perms = [
        'voir' => $user->aPermission('contacts.voir'),
        'repondre' => $user->aPermission('contacts.repondre'),
        'modifier' => $user->aPermission('contacts.modifier'),
        'supprimer' => $user->aPermission('contacts.supprimer'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les messages.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Message -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>Message
                </h5>
                <div>
                    @if($message->lu)
                        <span class="badge bg-secondary">
                            <i class="fas fa-check-circle me-1"></i>
                            Lu le {{ $message->date_traitement ? $message->date_traitement->format('d/m/Y H:i') : '' }}
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-envelope me-1"></i>Non lu
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Sujet :</h6>
                    <p class="lead">{{ $message->sujet_formate }}</p>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Message :</h6>
                    <div class="bg-light rounded-3">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>
                
                <div class="text-muted small">
                    <i class="far fa-clock me-1"></i>Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Réponse existante (si elle existe) -->
        @if($message->reponse)
        <div class="card mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-reply me-2"></i>Votre réponse
                </h5>
                <small class="text-white">
                    <i class="far fa-calendar-alt me-1"></i>
                    Envoyée le {{ $message->date_reponse->format('d/m/Y à H:i') }}
                </small>
            </div>
            <div class="card-body">
                <div class="bg-light rounded-3">
                    {!! nl2br(e($message->reponse)) !!}
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Expéditeur -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Expéditeur
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nom :</th>
                        <td class="fw-bold">{{ $message->nom }}</td>
                    </tr>
                    <tr>
                        <th>Email :</th>
                        <td>
                            <a href="mailto:{{ $message->email }}" class="text-info">
                                <i class="fas fa-envelope me-1"></i>{{ $message->email }}
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Actions sécurisées -->
        @if($perms['repondre'] || $perms['modifier'] || $perms['supprimer'])
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    {{-- Répondre par email (toujours visible car lien mailto) --}}
                    <a href="mailto:{{ $message->email }}" class="btn btn-primary">
                        <i class="fas fa-reply me-2"></i>Répondre par email
                    </a>
                    
                    {{-- Répondre via interface - Permission: contacts.repondre --}}
                    @if($perms['repondre'])
                    <a href="{{ route('admin.contacts.reply', $message->id) }}" class="btn btn-info">
                        <i class="fas fa-edit me-2"></i>Répondre (interface)
                    </a>
                    @endif
                    
                    {{-- Marquer comme lu/non lu - Permission: contacts.modifier --}}
                    @if($perms['modifier'])
                        @if($message->lu)
                        <form action="{{ route('admin.contacts.mark-as-unread', $message->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-envelope me-2"></i>Marquer non lu
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.contacts.mark-as-read', $message->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-2"></i>Marquer lu
                            </button>
                        </form>
                        @endif
                    @endif
                    
                    {{-- Supprimer - Permission: contacts.supprimer --}}
                    @if($perms['supprimer'])
                    <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Métadonnées -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informations
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID :</th>
                        <td><span class="badge bg-secondary">#{{ $message->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Reçu le :</th>
                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($message->lu && $message->date_traitement)
                    <tr>
                        <th>Lu le :</th>
                        <td>{{ $message->date_traitement->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($message->reponse && $message->date_reponse)
                    <tr>
                        <th>Répondu le :</th>
                        <td>{{ $message->date_reponse->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush