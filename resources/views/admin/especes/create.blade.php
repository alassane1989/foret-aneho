@extends('admin.layouts.admin')

@section('title', 'Ajouter une espèce - Administration')
@section('page-title', 'Ajouter une espèce')

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
        display: none;
    }
    
    .galerie-item {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    
    .galerie-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--primary);
    }
    
    .remove-galerie {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--danger);
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s;
    }
    
    .remove-galerie:hover {
        transform: scale(1.1);
        background: #B71C1C;
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
    
    .btn-primary {
        background: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-primary:hover {
        background: #0D47A1;
        border-color: #0D47A1;
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
    
    .text-warning {
        color: var(--warning) !important;
    }
    
    /* Select2 personnalisation */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #dee2e6;
    }
    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    /* Message d'aide */
    .form-text {
        color: var(--secondary);
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    /* En-tête */
    .page-header {
        margin-bottom: 20px;
    }
    
    .page-header h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    /* Responsivité */
    @media (max-width: 768px) {
        .form-section {
            padding: 15px;
        }
        
        .preview-image {
            max-width: 100%;
        }
        
        .galerie-item img {
            width: 70px;
            height: 70px;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        
        .row.mb-4 .col-12 {
            margin-bottom: 10px;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasCreerPermission = $user->aPermission('especes.creer');
@endphp

<!-- Vérification de permission -->
@if(!$hasCreerPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de créer des espèces.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.especes.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4 page-header">
    <div class="col-12">
        <a href="{{ route('admin.especes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<!-- En-tête -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <i class="fas fa-leaf fa-2x text-success me-3"></i>
            <div>
                <h4 class="mb-0">Nouvelle espèce</h4>
                <p class="text-muted small mb-0">Ajoutez une nouvelle espèce à l'inventaire de la forêt</p>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.especes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Informations générales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2"></i>Informations générales
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom scientifique <span class="text-danger">*</span></label>
                <input type="text" name="nom_scientifique" class="form-control @error('nom_scientifique') is-invalid @enderror" value="{{ old('nom_scientifique') }}" required placeholder="Ex: Adansonia digitata">
                @error('nom_scientifique')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nom local <span class="text-danger">*</span></label>
                <input type="text" name="nom_local" class="form-control @error('nom_local') is-invalid @enderror" value="{{ old('nom_local') }}" required placeholder="Ex: Baobab">
                @error('nom_local')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Famille</label>
                <input type="text" name="famille" class="form-control @error('famille') is-invalid @enderror" value="{{ old('famille') }}" placeholder="Ex: Fabaceae">
                @error('famille')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Genre</label>
                <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre') }}" placeholder="Ex: Adansonia">
                @error('genre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Origine géographique</label>
                <input type="text" name="origine" class="form-control @error('origine') is-invalid @enderror" value="{{ old('origine') }}" placeholder="Ex: Afrique de l'Ouest">
                @error('origine')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Type de feuillage</label>
                <select name="type" class="form-control @error('type') is-invalid @enderror">
                    <option value="">Sélectionner</option>
                    <option value="caduques" {{ old('type') == 'caduques' ? 'selected' : '' }}>Caduques</option>
                    <option value="persistant" {{ old('type') == 'persistant' ? 'selected' : '' }}>Persistant</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Hauteur maximale</label>
                <input type="text" name="hauteur_max" class="form-control @error('hauteur_max') is-invalid @enderror" value="{{ old('hauteur_max') }}" placeholder="Ex: 25 mètres">
                @error('hauteur_max')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Longévité</label>
                <input type="text" name="longevite" class="form-control @error('longevite') is-invalid @enderror" value="{{ old('longevite') }}" placeholder="Ex: 200-300 ans">
                @error('longevite')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                <select name="categorie" class="form-control @error('categorie') is-invalid @enderror" required>
                    <option value="">Sélectionner</option>
                    <option value="fruitier" {{ old('categorie') == 'fruitier' ? 'selected' : '' }}>🌳 Fruitier</option>
                    <option value="ornemental" {{ old('categorie') == 'ornemental' ? 'selected' : '' }}>🌸 Ornemental</option>
                    <option value="foret" {{ old('categorie') == 'foret' ? 'selected' : '' }}>🌲 Forêt</option>
                    <option value="sacre" {{ old('categorie') == 'sacre' ? 'selected' : '' }}>🕊️ Sacré</option>
                    <option value="medicinal" {{ old('categorie') == 'medicinal' ? 'selected' : '' }}>🌿 Médicinal</option>
                </select>
                @error('categorie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Statut de conservation</label>
                <input type="text" name="statut_conservation" class="form-control @error('statut_conservation') is-invalid @enderror" value="{{ old('statut_conservation') }}" placeholder="Ex: Vulnérable">
                @error('statut_conservation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="est_locale" id="est_locale" value="1" {{ old('est_locale', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="est_locale">
                        Espèce locale du Togo
                    </label>
                    <small class="d-block text-muted">Cocher si l'espèce est indigène</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Descriptions -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-align-left me-2"></i>Descriptions
        </h5>

        <div class="mb-3">
            <label class="form-label">Description générale <span class="text-danger">*</span></label>
            <textarea name="description_generale" class="form-control @error('description_generale') is-invalid @enderror" rows="4" required placeholder="Description générale de l'espèce, ses caractéristiques principales...">{{ old('description_generale') }}</textarea>
            @error('description_generale')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description botanique</label>
            <textarea name="description_botanique" class="form-control @error('description_botanique') is-invalid @enderror" rows="4" placeholder="Détails botaniques : feuilles, fleurs, fruits...">{{ old('description_botanique') }}</textarea>
            @error('description_botanique')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Utilisations</label>
                <textarea name="utilisation" class="form-control @error('utilisation') is-invalid @enderror" rows="3" placeholder="Utilisations traditionnelles, médicinales, alimentaires...">{{ old('utilisation') }}</textarea>
                @error('utilisation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Importance culturelle</label>
                <textarea name="importance_culturelle" class="form-control @error('importance_culturelle') is-invalid @enderror" rows="3" placeholder="Signification dans les traditions, croyances...">{{ old('importance_culturelle') }}</textarea>
                @error('importance_culturelle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Conseils de plantation -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-seedling me-2"></i>Conseils de plantation
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ensoleillement</label>
                <input type="text" name="conseils_plantation[soleil]" class="form-control" value="{{ old('conseils_plantation.soleil') }}" placeholder="Ex: Plein soleil, mi-ombre">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Arrosage</label>
                <input type="text" name="conseils_plantation[eau]" class="form-control" value="{{ old('conseils_plantation.eau') }}" placeholder="Ex: Arrosage régulier, modéré">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Type de sol</label>
                <input type="text" name="conseils_plantation[sol]" class="form-control" value="{{ old('conseils_plantation.sol') }}" placeholder="Ex: Bien drainé, sableux, argileux">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Température</label>
                <input type="text" name="conseils_plantation[temperature]" class="form-control" value="{{ old('conseils_plantation.temperature') }}" placeholder="Ex: Minimum 15°C, tropical">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Espacement</label>
                <input type="text" name="conseils_plantation[espace]" class="form-control" value="{{ old('conseils_plantation.espace') }}" placeholder="Ex: 8-10m entre les arbres">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Entretien</label>
                <input type="text" name="conseils_plantation[entretien]" class="form-control" value="{{ old('conseils_plantation.entretien') }}" placeholder="Ex: Taille annuelle, paillage">
            </div>
        </div>
    </div>

    <!-- Périodes -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-calendar-alt me-2"></i>Cycles et périodes
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Période de floraison</label>
                <input type="text" name="periodes[floraison]" class="form-control" value="{{ old('periodes.floraison') }}" placeholder="Ex: Octobre à décembre">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Période de fructification</label>
                <input type="text" name="periodes[fructification]" class="form-control" value="{{ old('periodes.fructification') }}" placeholder="Ex: Janvier à mars">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Chute des feuilles</label>
                <input type="text" name="periodes[chute_feuilles]" class="form-control" value="{{ old('periodes.chute_feuilles') }}" placeholder="Ex: Saison sèche">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Période de repousse</label>
                <input type="text" name="periodes[repousse]" class="form-control" value="{{ old('periodes.repousse') }}" placeholder="Ex: Début saison des pluies">
            </div>
        </div>
    </div>

    <!-- Images -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-camera me-2"></i>Images
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Image principale</label>
                <input type="file" name="image_principale" class="form-control @error('image_principale') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'preview-principale')">
                <div class="mt-2">
                    <img id="preview-principale" class="preview-image" src="#" alt="Aperçu">
                </div>
                <small class="form-text">Format accepté : JPG, PNG (max 2 Mo)</small>
                @error('image_principale')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Galerie d'images</label>
                <input type="file" name="galerie[]" class="form-control" accept="image/*" multiple onchange="previewGalerie(this)">
                <div id="galerie-preview" class="mt-2 d-flex flex-wrap"></div>
                <small class="form-text">Vous pouvez sélectionner plusieurs images</small>
            </div>
        </div>
    </div>

    <!-- Boutons -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.especes.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGalerie(input) {
        $('#galerie-preview').empty();
        if (input.files) {
            for (var i = 0; i < input.files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#galerie-preview').append(`
                        <div class="galerie-item">
                            <img src="${e.target.result}">
                            <div class="remove-galerie" onclick="this.parentElement.remove()">×</div>
                        </div>
                    `);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
</script>
@endpush