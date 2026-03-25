@extends('admin.layouts.admin')

@section('title', 'Paramètres - Administration')
@section('page-title', 'Paramètres du site')

@push('styles')
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #2E7D32;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8f5e9;
    }
    .action-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid #e8f5e9;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(46,125,50,0.1);
        border-color: #2E7D32;
        text-decoration: none;
        color: inherit;
    }
    .action-card i {
        font-size: 2rem;
        color: #2E7D32;
        margin-bottom: 10px;
    }
    .action-card h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    .action-card small {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Vérification que $settings existe -->
@if(!isset($settings) || empty($settings))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Les paramètres ne sont pas encore configurés. Utilisation des valeurs par défaut.
    </div>
    @php
        // Valeurs par défaut si $settings n'est pas défini
        $settings = [
            'site_name' => 'Forêt d\'Aného',
            'site_email' => 'contact@foret-aneho.tg',
            'site_phone' => '+228 XX XX XX XX',
            'site_address' => 'Mairie d\'Aného, Togo',
            'facebook_url' => '#',
            'twitter_url' => '#',
            'instagram_url' => '#',
            'youtube_url' => '#',
        ];
    @endphp
@endif

<!-- Paramètres généraux -->
<div class="form-section">
    <h5 class="section-title">
        <i class="fas fa-globe me-2"></i>Paramètres généraux
    </h5>

    <form action="{{ route('admin.settings.general') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom du site</label>
                <input type="text" 
                       name="site_name" 
                       class="form-control @error('site_name') is-invalid @enderror" 
                       value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" 
                       required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email de contact</label>
                <input type="email" 
                       name="site_email" 
                       class="form-control @error('site_email') is-invalid @enderror" 
                       value="{{ old('site_email', $settings['site_email'] ?? config('mail.from.address')) }}" 
                       required>
                @error('site_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" 
                       name="site_phone" 
                       class="form-control @error('site_phone') is-invalid @enderror" 
                       value="{{ old('site_phone', $settings['site_phone'] ?? '') }}">
                @error('site_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" 
                       name="site_address" 
                       class="form-control @error('site_address') is-invalid @enderror" 
                       value="{{ old('site_address', $settings['site_address'] ?? '') }}">
                @error('site_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<!-- Réseaux sociaux -->
<div class="form-section">
    <h5 class="section-title">
        <i class="fas fa-share-alt me-2"></i>Réseaux sociaux
    </h5>

    <form action="{{ route('admin.settings.social') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fab fa-facebook text-primary me-2"></i>Facebook
                </label>
                <input type="url" 
                       name="facebook_url" 
                       class="form-control @error('facebook_url') is-invalid @enderror" 
                       value="{{ old('facebook_url', $settings['facebook_url'] ?? '#') }}">
                @error('facebook_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fab fa-twitter text-info me-2"></i>Twitter
                </label>
                <input type="url" 
                       name="twitter_url" 
                       class="form-control @error('twitter_url') is-invalid @enderror" 
                       value="{{ old('twitter_url', $settings['twitter_url'] ?? '#') }}">
                @error('twitter_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fab fa-instagram text-danger me-2"></i>Instagram
                </label>
                <input type="url" 
                       name="instagram_url" 
                       class="form-control @error('instagram_url') is-invalid @enderror" 
                       value="{{ old('instagram_url', $settings['instagram_url'] ?? '#') }}">
                @error('instagram_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fab fa-youtube text-danger me-2"></i>YouTube
                </label>
                <input type="url" 
                       name="youtube_url" 
                       class="form-control @error('youtube_url') is-invalid @enderror" 
                       value="{{ old('youtube_url', $settings['youtube_url'] ?? '#') }}">
                @error('youtube_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<!-- Actions système -->
<div class="form-section">
    <h5 class="section-title">
        <i class="fas fa-tools me-2"></i>Actions système
    </h5>

    <div class="row">
        <div class="col-md-3 mb-3">
            <form action="{{ route('admin.settings.clear-cache') }}" method="POST" onsubmit="return confirm('Nettoyer tous les caches ?')">
                @csrf
                <button type="submit" class="action-card w-100 border-0 bg-transparent">
                    <i class="fas fa-broom"></i>
                    <h6>Nettoyer le cache</h6>
                    <small class="text-muted">Cache, vues, routes</small>
                </button>
            </form>
        </div>

        <div class="col-md-3 mb-3">
            <form action="{{ route('admin.settings.optimize') }}" method="POST" onsubmit="return confirm('Optimiser le site ?')">
                @csrf
                <button type="submit" class="action-card w-100 border-0 bg-transparent">
                    <i class="fas fa-rocket"></i>
                    <h6>Optimiser</h6>
                    <small class="text-muted">Optimisation Laravel</small>
                </button>
            </form>
        </div>

        <div class="col-md-3 mb-3">
            <form action="{{ route('admin.settings.test-email') }}" method="POST">
                @csrf
                <button type="submit" class="action-card w-100 border-0 bg-transparent">
                    <i class="fas fa-envelope"></i>
                    <h6>Tester email</h6>
                    <small class="text-muted">Envoyer un test</small>
                </button>
            </form>
        </div>

        <div class="col-md-3 mb-3">
            <a href="#" class="action-card w-100 border-0 bg-transparent" onclick="alert('Fonctionnalité à venir'); return false;">
                <i class="fas fa-database"></i>
                <h6>Sauvegarde</h6>
                <small class="text-muted">Base de données</small>
            </a>
        </div>
    </div>
</div>

<!-- Informations système -->
<div class="form-section">
    <h5 class="section-title">
        <i class="fas fa-info-circle me-2"></i>Informations système
    </h5>

    <div class="row">
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Version Laravel :</th>
                    <td>{{ app()->version() }}</td>
                </tr>
                <tr>
                    <th>Version PHP :</th>
                    <td>{{ phpversion() }}</td>
                </tr>
                <tr>
                    <th>Environnement :</th>
                    <td>{{ app()->environment() }}</td>
                </tr>
                <tr>
                    <th>URL du site :</th>
                    <td>{{ config('app.url') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Debug mode :</th>
                    <td>
                        @if(config('app.debug'))
                            <span class="badge bg-warning">Activé</span>
                        @else
                            <span class="badge bg-success">Désactivé</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Cache :</th>
                    <td>
                        @if(config('cache.default') === 'file')
                            <span class="badge bg-info">Fichier</span>
                        @else
                            <span class="badge bg-info">{{ config('cache.default') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Session :</th>
                    <td>
                        <span class="badge bg-info">{{ config('session.driver') }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Timezone :</th>
                    <td>{{ config('app.timezone') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Zone de débogage (à supprimer en production) -->
@if(config('app.debug'))
<div class="alert alert-secondary mt-3">
    <small>
        <i class="fas fa-bug me-2"></i>
        <strong>Débogage :</strong> 
        Variables disponibles dans la vue : 
        {{ implode(', ', array_keys(get_defined_vars())) }}
    </small>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Confirmation pour les actions sensibles
    function confirmAction(event, message) {
        if (!confirm(message)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Animation au survol des cartes d'action
    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // Validation des URLs
    document.querySelectorAll('input[type="url"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && this.value !== '#' && !this.value.startsWith('http')) {
                this.value = 'https://' + this.value;
            }
        });
    });

    // Protection contre l'envoi multiple des formulaires
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
            }
        });
    });
</script>
@endpush