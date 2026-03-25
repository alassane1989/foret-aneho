@extends('admin.layouts.admin')

@section('title', 'Répondre à ' . $message->nom . ' - Administration')
@section('page-title', 'Répondre au message')

@push('styles')
<style>
    :root {
        --primary: #28a745;
        --secondary: #6c757d;
        --info: #17a2b8;
        --danger: #dc3545;
    }
    
    .card-header.bg-success {
        background: var(--primary) !important;
    }
    
    .card-header.bg-secondary {
        background: var(--secondary) !important;
    }
    
    .card-header.bg-info {
        background: var(--info) !important;
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
    
    .bg-light.rounded-3 {
        background-color: #f8f9fa !important;
        border-left: 4px solid var(--primary);
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
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasReplyPermission = $user->aPermission('contacts.repondre');
@endphp

<!-- Vérification de permission -->
@if(!$hasReplyPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de répondre aux messages.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour au message
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Formulaire de réponse -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-reply me-2"></i>Répondre à {{ $message->nom }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.send-reply', $message->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Destinataire</label>
                        <input type="email" class="form-control bg-light" value="{{ $message->email }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sujet <span class="text-danger">*</span></label>
                        <input type="text" name="sujet_reponse" class="form-control @error('sujet_reponse') is-invalid @enderror" value="Re: {{ $message->sujet_formate }}" required>
                        @error('sujet_reponse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Votre réponse <span class="text-danger">*</span></label>
                        <textarea name="reponse" class="form-control @error('reponse') is-invalid @enderror" rows="8" required placeholder="Rédigez votre réponse ici..."></textarea>
                        @error('reponse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="garder_copie" name="garder_copie" checked>
                        <label class="form-check-label" for="garder_copie">
                            <i class="fas fa-save text-success me-1"></i>Garder une copie de la réponse dans la base de données
                        </label>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer la réponse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Message original -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>Message original
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>De :</strong> 
                    <span class="text-success">{{ $message->nom }}</span> 
                    <small class="text-muted">({{ $message->email }})</small>
                </div>
                <div class="mb-3">
                    <strong>Sujet :</strong> 
                    <span class="text-primary">{{ $message->sujet_formate }}</span>
                </div>
                <div class="mb-3">
                    <strong>Date :</strong> 
                    <span class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="p-3 bg-light rounded-3">
                    <strong>Message :</strong>
                    <p class="mt-2 mb-0" style="white-space: pre-line;">{{ $message->message }}</p>
                </div>
            </div>
        </div>

        <!-- Conseils -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Conseils
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0 ps-3">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Soyez poli et professionnel
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Répondez de manière claire et concise
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Si nécessaire, redirigez vers la personne compétente
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        N'oubliez pas de signer votre message
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
    // Optionnel : ajouter un compteur de caractères
    document.querySelector('textarea[name="reponse"]')?.addEventListener('input', function() {
        // Vous pouvez ajouter un compteur si nécessaire
    });
</script>
@endpush