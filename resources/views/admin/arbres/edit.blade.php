@extends('admin.layouts.admin')

@section('title', 'Modifier un arbre - Administration')
@section('page-title', 'Modifier un arbre')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
    }
    
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid rgba(46, 125, 50, 0.1);
    }
    
    .section-title {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8f5e9;
    }
    
    .section-title i {
        color: var(--secondary);
    }
    
    .preview-image {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 10px;
        border: 2px solid var(--primary);
    }
    
    .current-image {
        margin-bottom: 10px;
        text-align: center;
    }
    
    .current-image img {
        max-width: 150px;
        border-radius: 10px;
        border: 2px solid var(--primary);
    }
    
    .current-image p {
        color: var(--secondary);
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    /* Boutons */
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background: #1B5E20;
        border-color: #1B5E20;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }
    
    /* Formulaires */
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    .text-danger {
        color: var(--danger) !important;
    }
    
    .text-success {
        color: var(--primary) !important;
    }
    
    .text-primary {
        color: var(--secondary) !important;
    }
    
    /* Select2 personnalisation */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #dee2e6;
    }
    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    /* Badge pour l'ID */
    .badge-id {
        background: var(--secondary);
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasModifierPermission = $user->aPermission('arbres.modifier');
@endphp

<!-- Vérification de permission -->
@if(!$hasModifierPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de modifier les arbres.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.arbres.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.arbres.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<!-- En-tête avec ID -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <span class="badge-id me-2">#{{ $arbre->id }}</span>
                {{ $arbre->nom }}
            </h4>
            <div class="text-muted small">
                Créé le {{ $arbre->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.arbres.update', $arbre->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Informations générales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2"></i>Informations générales
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom de l'arbre <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $arbre->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Espèce <span class="text-danger">*</span></label>
                <select name="espece_id" class="form-control select2 @error('espece_id') is-invalid @enderror" required>
                    <option value="">Sélectionner une espèce</option>
                    @foreach($especes as $espece)
                        <option value="{{ $espece->id }}" {{ old('espece_id', $arbre->espece_id) == $espece->id ? 'selected' : '' }}>
                            {{ $espece->nom_local }} ({{ $espece->nom_scientifique }})
                        </option>
                    @endforeach
                </select>
                @error('espece_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Zone <span class="text-danger">*</span></label>
                <select name="zone_id" class="form-control select2 @error('zone_id') is-invalid @enderror" required>
                    <option value="">Sélectionner une zone</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ old('zone_id', $arbre->zone_id) == $zone->id ? 'selected' : '' }}>
                            {{ $zone->nom }}
                        </option>
                    @endforeach
                </select>
                @error('zone_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Date de plantation <span class="text-danger">*</span></label>
                <input type="date" name="date_plantation" class="form-control @error('date_plantation') is-invalid @enderror" value="{{ old('date_plantation', $arbre->date_plantation->format('Y-m-d')) }}" required>
                @error('date_plantation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Photos -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-camera me-2"></i>Photos
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Photo de l'arbre</label>
                @if($arbre->photo_arbre)
                <div class="current-image">
                    <img src="{{ $arbre->photo_url }}" alt="Photo actuelle">
                    <p><i class="fas fa-image text-primary me-1"></i>Photo actuelle</p>
                </div>
                @endif
                <input type="file" name="photo_arbre" class="form-control @error('photo_arbre') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'preview-arbre')">
                <div class="mt-2">
                    <img id="preview-arbre" class="preview-image" src="#" alt="Aperçu" style="display: none;">
                </div>
                <small class="text-muted">Laissez vide pour conserver la photo actuelle</small>
                @error('photo_arbre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Photo du planteur</label>
                @if($arbre->planteur_photo)
                <div class="current-image">
                    <img src="{{ $arbre->planteur_photo_url }}" alt="Photo du planteur">
                    <p><i class="fas fa-image text-primary me-1"></i>Photo actuelle</p>
                </div>
                @endif
                <input type="file" name="planteur_photo" class="form-control @error('planteur_photo') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'preview-planteur')">
                <div class="mt-2">
                    <img id="preview-planteur" class="preview-image" src="#" alt="Aperçu" style="display: none;">
                </div>
                <small class="text-muted">Laissez vide pour conserver la photo actuelle</small>
                @error('planteur_photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Planteur et description -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-user me-2"></i>Planteur et description
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom du planteur <span class="text-danger">*</span></label>
                <input type="text" name="planteur_nom" class="form-control @error('planteur_nom') is-invalid @enderror" value="{{ old('planteur_nom', $arbre->planteur_nom) }}" required>
                @error('planteur_nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">État de santé</label>
                <select name="etat_sante" class="form-control @error('etat_sante') is-invalid @enderror">
                    <option value="excellent" {{ old('etat_sante', $arbre->etat_sante) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                    <option value="bon" {{ old('etat_sante', $arbre->etat_sante) == 'bon' ? 'selected' : '' }}>Bon</option>
                    <option value="moyen" {{ old('etat_sante', $arbre->etat_sante) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                    <option value="surveille" {{ old('etat_sante', $arbre->etat_sante) == 'surveille' ? 'selected' : '' }}>Surveillé</option>
                </select>
                @error('etat_sante')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $arbre->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Localisation -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-map-marker-alt me-2"></i>Localisation GPS
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Latitude <span class="text-danger">*</span></label>
                <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $arbre->latitude) }}" required>
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Longitude <span class="text-danger">*</span></label>
                <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $arbre->longitude) }}" required>
                @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Hauteur</label>
                <input type="text" name="hauteur" class="form-control" value="{{ old('hauteur', $arbre->hauteur) }}" placeholder="Ex: 8.5 m">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Circonférence</label>
                <input type="text" name="circonference" class="form-control" value="{{ old('circonference', $arbre->circonference) }}" placeholder="Ex: 2.3 m">
            </div>
        </div>
    </div>

    <!-- Boutons -->
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-2"></i>Mettre à jour
            </button>
            <a href="{{ route('admin.arbres.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            theme: 'bootstrap-5'
        });
    });

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush