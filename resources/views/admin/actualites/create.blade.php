@extends('admin.layouts.admin')

@section('title', 'Ajouter une actualité - Administration')
@section('page-title', 'Ajouter une actualité')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --purple: #9C27B0;        /* Violet pour partenariat */
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
    
    .btn-warning {
        background: var(--warning);
        border-color: var(--warning);
        color: white;
    }
    .btn-warning:hover {
        background: #EF6C00;
        border-color: #EF6C00;
        color: white;
    }
    
    .btn-danger {
        background: var(--danger);
        border-color: var(--danger);
    }
    .btn-danger:hover {
        background: #B71C1C;
        border-color: #B71C1C;
    }
    
    /* Formulaires */
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
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
    
    /* Message d'aide */
    .form-text {
        color: var(--secondary);
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    .text-muted {
        color: #6c757d !important;
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
    }
    
    /* Personnalisation Summernote */
    .note-editor.note-frame {
        border: 1px solid rgba(46, 125, 50, 0.2);
        border-radius: 10px;
    }
    
    .note-editor.note-frame .note-toolbar {
        background: rgba(46, 125, 50, 0.05);
        border-bottom: 1px solid rgba(46, 125, 50, 0.2);
        border-radius: 10px 10px 0 0;
    }
    
    .note-btn {
        color: var(--primary);
    }
    
    .note-btn:hover {
        background: rgba(46, 125, 50, 0.1);
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasCreerPermission = $user->aPermission('actualites.creer');
    $hasPublierPermission = $user->aPermission('actualites.publier');
@endphp

<!-- Vérification de permission -->
@if(!$hasCreerPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de créer des actualités.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4 page-header">
    <div class="col-12">
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<!-- En-tête -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <i class="fas fa-newspaper fa-2x text-success me-3"></i>
            <div>
                <h4 class="mb-0">Nouvelle actualité</h4>
                <p class="text-muted small mb-0">Créez un nouvel article pour informer sur les activités de la forêt</p>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.actualites.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Informations principales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2"></i>Informations principales
        </h5>

        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required placeholder="Ex: Nouvelle campagne de plantation 2024">
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                <select name="categorie" class="form-control @error('categorie') is-invalid @enderror" required>
                    <option value="">Sélectionner</option>
                    <option value="plantation" {{ old('categorie') == 'plantation' ? 'selected' : '' }}>🌱 Plantation</option>
                    <option value="education" {{ old('categorie') == 'education' ? 'selected' : '' }}>📚 Éducation</option>
                    <option value="infrastructure" {{ old('categorie') == 'infrastructure' ? 'selected' : '' }}>🏗️ Infrastructure</option>
                    <option value="partenariat" {{ old('categorie') == 'partenariat' ? 'selected' : '' }}>🤝 Partenariat</option>
                    <option value="evenement" {{ old('categorie') == 'evenement' ? 'selected' : '' }}>🎉 Événement</option>
                </select>
                @error('categorie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Date de publication <span class="text-danger">*</span></label>
                <input type="date" name="date_publication" class="form-control @error('date_publication') is-invalid @enderror" value="{{ old('date_publication', date('Y-m-d')) }}" required>
                <small class="form-text">Date à laquelle l'article sera publié</small>
                @error('date_publication')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="Ex: plantation, bénévolat, environnement">
                <small class="form-text">Exemple: forêt, plantation, bénévolat, environnement</small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description courte <span class="text-danger">*</span></label>
            <textarea name="description_courte" class="form-control @error('description_courte') is-invalid @enderror" rows="3" required maxlength="500" placeholder="Résumé de l'article...">{{ old('description_courte') }}</textarea>
            <small class="form-text">Maximum 500 caractères. C'est le texte qui apparaîtra dans la liste des actualités.</small>
            @error('description_courte')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Contenu -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-align-left me-2"></i>Contenu
        </h5>

        <div class="mb-3">
            <label class="form-label">Contenu de l'article <span class="text-danger">*</span></label>
            <textarea name="contenu" id="summernote" class="form-control @error('contenu') is-invalid @enderror" rows="10" required>{{ old('contenu') }}</textarea>
            @error('contenu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Images -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-camera me-2"></i>Images
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Image principale <span class="text-danger">*</span></label>
                <input type="file" name="image_principale" class="form-control @error('image_principale') is-invalid @enderror" accept="image/*" required onchange="previewImage(this, 'preview-principale')">
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
                <small class="form-text">Vous pouvez sélectionner plusieurs images (optionnel)</small>
            </div>
        </div>
    </div>

    <!-- Publication -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-toggle-on me-2"></i>Publication
        </h5>

        @if($hasPublierPermission)
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="est_publie" id="est_publie" value="1" {{ old('est_publie', false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="est_publie">Publier immédiatement</label>
        </div>
        <small class="form-text">Si décoché, l'article sera enregistré comme brouillon.</small>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Vous n'avez pas la permission de publier directement. L'article sera enregistré comme brouillon.
            <input type="hidden" name="est_publie" value="0">
        </div>
        @endif
    </div>

    <!-- Boutons -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.actualites.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Écrivez votre article ici...',
            lang: 'fr-FR',
            callbacks: {
                onImageUpload: function(files) {
                    // Pour une version avancée, on pourrait uploader via AJAX
                    alert('Pour insérer une image, utilisez l\'URL de l\'image ou téléchargez-la via le gestionnaire de fichiers');
                }
            }
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