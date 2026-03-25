@extends('admin.layouts.admin')

@section('title', 'Ajouter une zone - Administration')
@section('page-title', 'Ajouter une zone')

@push('styles')
<style>
    :root {
        --primary: #2E7D32;      /* Vert principal */
        --secondary: #1976D2;     /* Bleu secondaire */
        --danger: #C62828;        /* Rouge pour suppression */
        --warning: #F57C00;       /* Orange pour avertissement */
        --info: #17a2b8;           /* Info */
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
    
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .activite-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        border-left: 4px solid var(--primary);
    }
    
    .remove-activite {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: var(--danger);
        font-size: 1.2rem;
        transition: all 0.3s;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .remove-activite:hover {
        background: var(--danger);
        color: white;
        transform: scale(1.1);
    }
    
    .add-activite {
        cursor: pointer;
        color: var(--primary);
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
    
    .btn-outline-success {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-outline-success:hover {
        background: var(--primary);
        color: white;
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
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        
        .color-preview {
            margin-top: 10px;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $hasCreerPermission = $user->aPermission('zones.creer');
@endphp

<!-- Vérification de permission -->
@if(!$hasCreerPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de créer des zones.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4 page-header">
    <div class="col-12">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<!-- En-tête -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <i class="fas fa-map-marked-alt fa-2x text-success me-3"></i>
            <div>
                <h4 class="mb-0">Nouvelle zone</h4>
                <p class="text-muted small mb-0">Ajoutez une nouvelle zone à la forêt urbaine</p>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.zones.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Informations générales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2"></i>Informations générales
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom de la zone <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required placeholder="Ex: GLIDJI, LOLAN, NLESS, YVELINES">
                <small class="form-text">Le nom doit être unique</small>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Couleur de la zone <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-3">
                    <input type="color" name="couleur" class="form-control form-control-color" value="{{ old('couleur', '#4CAF50') }}" style="width: 80px; height: 45px;">
                    <div class="color-preview" style="background-color: {{ old('couleur', '#4CAF50') }};"></div>
                </div>
                <small class="form-text">Couleur représentative de la zone sur la carte</small>
                @error('couleur')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Superficie</label>
                <input type="text" name="superficie" class="form-control @error('superficie') is-invalid @enderror" value="{{ old('superficie') }}" placeholder="Ex: 2.5 ha">
                <small class="form-text">Surface de la zone (hectares, m²...)</small>
                @error('superficie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Ordre d'affichage</label>
                <input type="number" name="ordre" class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', 0) }}" placeholder="Ex: 1, 2, 3...">
                <small class="form-text">Plus le chiffre est petit, plus la zone apparaît en haut</small>
                @error('ordre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description courte <span class="text-danger">*</span></label>
            <textarea name="description_courte" class="form-control @error('description_courte') is-invalid @enderror" rows="2" required maxlength="200" placeholder="Brève présentation de la zone...">{{ old('description_courte') }}</textarea>
            <small class="form-text">Maximum 200 caractères. S'affiche sur la carte et dans les listes.</small>
            @error('description_courte')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description longue</label>
            <textarea name="description_longue" class="form-control @error('description_longue') is-invalid @enderror" rows="4" placeholder="Description détaillée de la zone, son histoire, ses particularités...">{{ old('description_longue') }}</textarea>
            @error('description_longue')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Localisation -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-map-marker-alt me-2"></i>Localisation
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Latitude</label>
                <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" placeholder="Ex: 6.233">
                <small class="form-text">Coordonnées GPS (point d'entrée de la zone)</small>
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Longitude</label>
                <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" placeholder="Ex: 1.588">
                <small class="form-text">Coordonnées GPS (point d'entrée de la zone)</small>
                @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Adresse d'accès</label>
                <input type="text" name="adresse_acces" class="form-control @error('adresse_acces') is-invalid @enderror" value="{{ old('adresse_acces') }}" placeholder="Ex: Rue des Baobabs, Aného">
                <small class="form-text">Indications pour trouver la zone</small>
                @error('adresse_acces')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Image -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-image me-2"></i>Image de la zone
        </h5>

        <div class="mb-3">
            <label class="form-label">Image principale</label>
            <input type="file" name="image_principale" class="form-control @error('image_principale') is-invalid @enderror" accept="image/*">
            <small class="form-text">Format accepté : JPG, PNG (max 2 Mo). Image représentative de la zone.</small>
            @error('image_principale')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Activités -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-tasks me-2"></i>Activités proposées
        </h5>

        <div id="activites-container">
            <!-- Les activités seront ajoutées ici dynamiquement -->
        </div>

        <button type="button" class="btn btn-outline-success btn-sm" onclick="addActivite()">
            <i class="fas fa-plus me-2"></i>Ajouter une activité
        </button>
        <small class="form-text ms-2">Les activités apparaîtront sur la page de la zone</small>
    </div>

    <!-- Espèces principales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-leaf me-2"></i>Espèces principales
        </h5>

        <div class="mb-3">
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control mb-2" placeholder="Ex: Baobab">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control mb-2" placeholder="Ex: Fromager">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control mb-2" placeholder="Ex: Acajou">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control mb-2" placeholder="Ex: Iroko">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control mb-2" placeholder="Ex: Palmier">
                </div>
            </div>
        </div>
        <small class="form-text">Vous pouvez ajouter jusqu'à 5 espèces principales présentes dans cette zone</small>
    </div>

    <!-- Statut -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-toggle-on me-2"></i>Statut
        </h5>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="est_active" id="est_active" value="1" checked>
            <label class="form-check-label fw-semibold" for="est_active">Zone active (visible sur le site)</label>
        </div>
        <small class="form-text">Si désactivé, la zone n'apparaîtra pas sur le site public</small>
    </div>

    <!-- Boutons -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
    let activiteCount = 0;

    function addActivite() {
        const container = document.getElementById('activites-container');
        const html = `
            <div class="activite-item" id="activite-${activiteCount}">
                <i class="fas fa-times remove-activite" onclick="removeActivite(${activiteCount})"></i>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Icône</label>
                        <select name="activites[${activiteCount}][icone]" class="form-control">
                            <option value="tree">🌳 Arbre</option>
                            <option value="hiking">🥾 Randonnée</option>
                            <option value="camera">📷 Photo</option>
                            <option value="book">📚 Atelier</option>
                            <option value="users">👥 Groupe</option>
                            <option value="meditation">🧘 Méditation</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nom de l'activité</label>
                        <input type="text" name="activites[${activiteCount}][nom]" class="form-control" placeholder="Ex: Visite guidée">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        activiteCount++;
    }

    function removeActivite(id) {
        if (confirm('Supprimer cette activité ?')) {
            document.getElementById(`activite-${id}`).remove();
        }
    }

    // Mettre à jour la prévisualisation de la couleur
    document.querySelector('input[name="couleur"]').addEventListener('input', function(e) {
        document.querySelector('.color-preview').style.backgroundColor = e.target.value;
    });
</script>
@endpush