@extends('admin.layouts.admin')

@section('title', 'Modifier une zone - Administration')
@section('page-title', 'Modifier la zone : ' . $zone->nom)

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
    
    .current-image {
        max-width: 200px;
        margin-bottom: 15px;
        border-radius: 10px;
        border: 3px solid var(--primary);
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
    
    /* Badge d'information */
    .badge-id {
        background: var(--secondary);
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
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
    
    /* Responsivité */
    @media (max-width: 768px) {
        .form-section {
            padding: 15px;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        
        .current-image {
            max-width: 100%;
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
    $hasModifierPermission = $user->aPermission('zones.modifier');
@endphp

<!-- Vérification de permission -->
@if(!$hasModifierPermission)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous n'avez pas la permission de modifier les zones.
    </div>
    <div class="text-center">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
@else

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
        <div>
            <span class="badge-id me-2">ID: #{{ $zone->id }}</span>
            <span class="badge bg-success">Arbres: {{ $zone->nombre_arbres }}</span>
        </div>
    </div>
</div>

<!-- En-tête -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <i class="fas fa-map-marked-alt fa-2x text-success me-3"></i>
            <div>
                <h4 class="mb-0">{{ $zone->nom }}</h4>
                <p class="text-muted small mb-0">
                    <i class="fas fa-slug me-1 text-primary"></i>{{ $zone->slug }} | 
                    <i class="fas fa-calendar me-1 text-primary"></i>Créé le {{ $zone->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.zones.update', $zone->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Informations générales -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2"></i>Informations générales
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom de la zone <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $zone->nom) }}" required>
                <small class="form-text">Ex: GLIDJI, LOLAN, NLESS, YVELINES</small>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Couleur de la zone <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-3">
                    <input type="color" name="couleur" class="form-control form-control-color" value="{{ old('couleur', $zone->couleur) }}" style="width: 80px; height: 45px;">
                    <div class="color-preview" style="background-color: {{ old('couleur', $zone->couleur) }};"></div>
                </div>
                @error('couleur')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Superficie</label>
                <input type="text" name="superficie" class="form-control @error('superficie') is-invalid @enderror" value="{{ old('superficie', $zone->superficie) }}" placeholder="Ex: 2.5 ha">
                @error('superficie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Ordre d'affichage</label>
                <input type="number" name="ordre" class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', $zone->ordre) }}" placeholder="Ex: 1, 2, 3...">
                <small class="form-text">Plus le chiffre est petit, plus la zone apparaît en haut</small>
                @error('ordre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description courte <span class="text-danger">*</span></label>
            <textarea name="description_courte" class="form-control @error('description_courte') is-invalid @enderror" rows="2" required maxlength="200">{{ old('description_courte', $zone->description_courte) }}</textarea>
            <small class="form-text">Maximum 200 caractères. S'affiche sur la carte et dans les listes.</small>
            @error('description_courte')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description longue</label>
            <textarea name="description_longue" class="form-control @error('description_longue') is-invalid @enderror" rows="4" placeholder="Description détaillée de la zone, son histoire, ses particularités...">{{ old('description_longue', $zone->description_longue) }}</textarea>
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
                <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $zone->latitude) }}" placeholder="Ex: 6.233">
                <small class="form-text">Coordonnées GPS</small>
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Longitude</label>
                <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $zone->longitude) }}" placeholder="Ex: 1.588">
                <small class="form-text">Coordonnées GPS</small>
                @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Adresse d'accès</label>
                <input type="text" name="adresse_acces" class="form-control @error('adresse_acces') is-invalid @enderror" value="{{ old('adresse_acces', $zone->adresse_acces) }}" placeholder="Ex: Rue des Baobabs, Aného">
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

        @if($zone->image_principale)
        <div class="mb-3">
            <label class="form-label">Image actuelle</label>
            <div>
                <img src="{{ $zone->image_url }}" class="current-image" alt="{{ $zone->nom }}">
                <p class="text-muted small"><i class="fas fa-image text-primary me-1"></i>Image actuelle</p>
            </div>
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Nouvelle image</label>
            <input type="file" name="image_principale" class="form-control @error('image_principale') is-invalid @enderror" accept="image/*">
            <small class="form-text">Laissez vide pour conserver l'image actuelle. Format accepté : JPG, PNG (max 2 Mo)</small>
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
            @if($zone->activites && is_array($zone->activites))
                @foreach($zone->activites as $index => $activite)
                <div class="activite-item" id="activite-{{ $index }}">
                    <i class="fas fa-times remove-activite" onclick="removeActivite({{ $index }})"></i>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Icône</label>
                            <select name="activites[{{ $index }}][icone]" class="form-control">
                                <option value="tree" {{ $activite['icone'] == 'tree' ? 'selected' : '' }}>🌳 Arbre</option>
                                <option value="hiking" {{ $activite['icone'] == 'hiking' ? 'selected' : '' }}>🥾 Randonnée</option>
                                <option value="camera" {{ $activite['icone'] == 'camera' ? 'selected' : '' }}>📷 Photo</option>
                                <option value="book" {{ $activite['icone'] == 'book' ? 'selected' : '' }}>📚 Atelier</option>
                                <option value="users" {{ $activite['icone'] == 'users' ? 'selected' : '' }}>👥 Groupe</option>
                                <option value="meditation" {{ $activite['icone'] == 'meditation' ? 'selected' : '' }}>🧘 Méditation</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nom de l'activité</label>
                            <input type="text" name="activites[{{ $index }}][nom]" class="form-control" value="{{ $activite['nom'] }}" placeholder="Ex: Visite guidée">
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
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
            @php
                $especes = is_array($zone->especes_principales) ? $zone->especes_principales : [];
                for ($i = 0; $i < 5; $i++):
                    $value = $especes[$i] ?? '';
            @endphp
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="especes_principales[]" class="form-control" value="{{ $value }}" placeholder="Ex: Baobab, Fromager, Iroko...">
                </div>
            </div>
            @php endfor; @endphp
        </div>
        <small class="form-text">Vous pouvez ajouter jusqu'à 5 espèces principales présentes dans cette zone</small>
    </div>

    <!-- Statut -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-toggle-on me-2"></i>Statut
        </h5>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="est_active" id="est_active" value="1" {{ $zone->est_active ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="est_active">Zone active (visible sur le site)</label>
        </div>
        <small class="form-text">Si désactivé, la zone n'apparaîtra pas sur le site public</small>
    </div>

    <!-- Boutons -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-2"></i>Mettre à jour
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
    let activiteCount = {{ isset($zone->activites) && is_array($zone->activites) ? count($zone->activites) : 0 }};

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