@extends('admin.layouts.admin')

@section('title', 'Ajouter un abonné - Administration')
@section('page-title', 'Ajouter un abonné à la newsletter')

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
    
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    /* Animation pour le formulaire */
    .card {
        transition: all 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.15);
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasCreatePermission = $user->aPermission('newsletter.creer');
@endphp

<!-- Vérification de permission -->
@if(!$hasCreatePermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission d'ajouter des abonnés à la newsletter.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Nouvel abonné
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.newsletters.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user text-success me-2"></i>Nom <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                               value="{{ old('nom') }}" required placeholder="Ex: Jean Dupont">
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-envelope text-success me-2"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required placeholder="Ex: jean.dupont@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="actif" name="actif" value="1" checked>
                        <label class="form-check-label fw-semibold" for="actif">
                            <i class="fas fa-check-circle text-success me-1"></i>Actif (recevra les newsletters)
                        </label>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Ajouter l'abonné
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
                        <h6 class="mb-1">À propos de la newsletter</h6>
                        <p class="text-muted small mb-0">
                            Les abonnés actifs recevront les communications par email. 
                            Vous pouvez gérer leur statut depuis la liste des abonnés.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection