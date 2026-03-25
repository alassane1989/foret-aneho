@extends('admin.layouts.admin')

@section('title', 'Gestion des messages de contact')
@section('page-title', 'Messages de contact')

@push('styles')
<style>
    /* Styles généraux */
    .stat-card {
        transition: all 0.3s;
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        background: white;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    /* Badges de sujet - version simplifiée */
    .badge-sujet {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .filter-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Boutons d'action */
    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
        background: transparent;
    }
    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }
    
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
        background: transparent;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
        background: transparent;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
        background: transparent;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
    
    /* Badges de statut - seulement 2 couleurs */
    .badge-success {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .badge-warning {
        background-color: #6c757d;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
    }
    
    /* Aperçu message */
    .message-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Tableau */
    .table-container {
        overflow-x: auto;
        max-width: 100%;
        border-radius: 10px;
        background: white;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .table {
        min-width: 1200px;
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #28a745;
        padding: 12px 8px;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .table-actions {
        white-space: nowrap;
        width: 180px;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
        gap: 3px;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        border-radius: 4px;
    }
    
    /* Correction pour les formulaires inline */
    .btn-group form {
        margin: 0;
        padding: 0;
        line-height: 0;
    }
    
    .btn-group form button {
        margin: 0;
        border-radius: 4px;
    }
    
    /* Pagination */
    .pagination-info {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        color: #6c757d;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 15px;
        }
        .table-container {
            overflow-x: auto;
        }
        .table {
            min-width: 1000px;
        }
        .btn-group {
            flex-wrap: wrap;
        }
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
        'exporter' => $user->aPermission('contacts.exporter'),
    ];
@endphp

<!-- Vérification de permission -->
@if(!$perms['voir'])
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de voir les messages de contact.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
        </a>
    </div>
@else

<div class="container-fluid">
    <!-- Statistiques simplifiées - sans icônes -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div>
                        <h6 class="card-title text-muted">Total messages</h6>
                        <h2 class="mb-0">{{ $stats['total'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div>
                        <h6 class="card-title text-muted">Non lus</h6>
                        <h2 class="mb-0">{{ $stats['non_lus'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div>
                        <h6 class="card-title text-muted">Lus</h6>
                        <h2 class="mb-0">{{ $stats['lus'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="card-body">
                    <div>
                        <h6 class="card-title text-muted">Répondu</h6>
                        <h2 class="mb-0">{{ $stats['repondu'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.contacts.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statut">Statut</label>
                        <select name="statut" id="statut" class="form-control">
                            <option value="">Tous</option>
                            <option value="non_lu" {{ request('statut') == 'non_lu' ? 'selected' : '' }}>Non lus</option>
                            <option value="lu" {{ request('statut') == 'lu' ? 'selected' : '' }}>Lus</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sujet">Sujet</label>
                        <select name="sujet" id="sujet" class="form-control">
                            <option value="">Tous</option>
                            <option value="info" {{ request('sujet') == 'info' ? 'selected' : '' }}>Information</option>
                            <option value="visite" {{ request('sujet') == 'visite' ? 'selected' : '' }}>Visite</option>
                            <option value="participation" {{ request('sujet') == 'participation' ? 'selected' : '' }}>Participation</option>
                            <option value="projet" {{ request('sujet') == 'projet' ? 'selected' : '' }}>Projet</option>
                            <option value="partenariat" {{ request('sujet') == 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                            <option value="autre" {{ request('sujet') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Recherche</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Nom, email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tri">Tri</label>
                        <select name="tri" id="tri" class="form-control">
                            <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>Récents</option>
                            <option value="ancien" {{ request('tri') == 'ancien' ? 'selected' : '' }}>Anciens</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </a>
                    
                    <!-- Boutons d'export - seulement avec permission -->
                    @if($perms['exporter'])
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Exporter
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.contacts.export.excel', request()->query()) }}">
                                    <i class="fas fa-file-excel text-success me-2"></i> Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.contacts.export.pdf', request()->query()) }}">
                                    <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des messages -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Statut</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="{{ !$message->lu ? 'fw-bold' : '' }}">
                    <td>#{{ $message->id }}</td>
                    <td>
                        @if($message->lu)
                            <span class="badge badge-success">Lu</span>
                        @else
                            <span class="badge badge-warning">Non lu</span>
                        @endif
                    </td>
                    <td>{{ $message->nom }}</td>
                    <td>
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </td>
                    <td>
                        <span class="badge-sujet">
                            {{ $message->sujet_label }}
                        </span>
                    </td>
                    <td>
                        <div class="message-preview" title="{{ $message->message }}">
                            {{ Str::limit($message->message, 50) }}
                        </div>
                    </td>
                    <td>
                        {{ $message->created_at->format('d/m/Y') }}
                    </td>
                    <td class="table-actions">
                        <div class="btn-group" role="group">
                            <!-- Voir - toujours visible si on a la permission de voir -->
                            @if($perms['voir'])
                            <a href="{{ route('admin.contacts.show', $message->id) }}" 
                               class="btn btn-outline-primary btn-sm" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            
                            <!-- Répondre - seulement avec permission -->
                            @if($perms['repondre'])
                            <a href="{{ route('admin.contacts.reply', $message->id) }}" 
                               class="btn btn-outline-success btn-sm" title="Répondre">
                                <i class="fas fa-reply"></i>
                            </a>
                            @endif
                            
                            <!-- Marquer lu/non lu - seulement avec permission -->
                            @if($perms['modifier'])
                                @if($message->lu)
                                    <a href="{{ route('admin.contacts.mark-as-unread', $message->id) }}" 
                                       class="btn btn-outline-secondary btn-sm" title="Marquer non lu">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.contacts.mark-as-read', $message->id) }}" 
                                       class="btn btn-outline-success btn-sm" title="Marquer lu">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endif
                            @endif
                            
                            <!-- Supprimer - seulement avec permission -->
                            @if($perms['supprimer'])
                            <a href="#" 
                               class="btn btn-outline-danger btn-sm" 
                               title="Supprimer"
                               onclick="event.preventDefault(); if(confirm('Supprimer ce message ?')) document.getElementById('delete-form-{{ $message->id }}').submit();">
                                <i class="fas fa-trash"></i>
                            </a>
                            <form id="delete-form-{{ $message->id }}" 
                                  action="{{ route('admin.contacts.destroy', $message->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun message</h5>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="pagination-info">
                    {{ $messages->firstItem() ?? 0 }} - {{ $messages->lastItem() ?? 0 }} sur {{ $messages->total() }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="float-end">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit des filtres
    $('#statut, #sujet, #tri').change(function() {
        $('#filterForm').submit();
    });
});
</script>
@endpush