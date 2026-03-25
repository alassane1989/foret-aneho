@extends('admin.layouts.admin')

@section('title', 'Modifier un abonné - Administration')
@section('page-title', 'Modifier : ' . $abonne->nom)

@push('styles')
<style>
    :root {
        --primary: #28a745;
        --secondary: #6c757d;
        --danger: #dc3545;
    }
    
    .card-header.bg-success {
        background: var(--primary) !important;
    }
    
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #218838;
        border-color: #1e7e34;
    }
    
    .btn-outline-secondary {
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-outline-secondary:hover {
        background: var(--secondary);
        color: white;
    }
    
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
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
        padding: 30px;
    }
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    .badge-info {
        background: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
    }
    
    /* Style pour la date d'inscription */
    .inscription-date {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary);
    }
    
    .inscription-date i {
        color: var(--primary);
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasModifierPermission = $user->aPermission('newsletter.modifier');
@endphp

<!-- Vérification de permission -->
@if(!$hasModifierPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de modifier les abonnés.
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
            <span class="badge-info me-2">
                <i class="fas fa-calendar-alt me-1"></i>
                Inscrit le {{ $abonne->date_inscription->format('d/m/Y') }}
            </span>
            <span class="badge-info">
                <i class="fas fa-envelope me-1"></i>
                ID: #{{ $abonne->id }}
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Modifier l'abonné
                </h5>
            </div>
            <div class="card-body">
                <!-- Information sur la date d'inscription -->
                <div class="inscription-date">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Inscrit le {{ $abonne->date_inscription->format('d/m/Y à H:i') }}</strong>
                            @if($abonne->date_desinscription)
                            <br><span class="text-danger">Désinscrit le {{ $abonne->date_desinscription->format('d/m/Y à H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.newsletters.update', $abonne->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user text-success me-2"></i>Nom <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                               value="{{ old('nom', $abonne->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-envelope text-success me-2"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $abonne->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="est_actif" class="form-check-input" id="est_actif" value="1" {{ $abonne->est_actif ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="est_actif">
                                <i class="fas fa-check-circle text-success me-1"></i>Abonné actif (recevra les newsletters)
                            </label>
                        </div>
                        <small class="text-muted ms-4">Décochez pour désactiver temporairement l'abonnement</small>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information supplémentaire -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x text-success me-3"></i>
                    <div>
                        <h6 class="mb-1">Gestion des abonnés</h6>
                        <p class="text-muted small mb-0">
                            Les abonnés actifs recevront les communications par email. 
                            Vous pouvez également désinscrire ou supprimer un abonné depuis la liste.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection