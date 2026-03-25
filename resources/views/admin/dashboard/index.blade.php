@extends('admin.layouts.admin')

@section('title', 'Tableau de bord - Administration')
@section('page-title', 'Tableau de bord')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- En-tête de bienvenue -->
<div class="welcome-card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Tableau de bord</h2>
            <p class="mb-0">Bienvenue dans l'administration de la Forêt d'Aného</p>
        </div>
        <div class="text-end">
            <small class="opacity-75">
                {{ now()->format('l d F Y') }}
            </small>
        </div>
    </div>
</div>

<!-- Première ligne de graphiques -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-map-marked-alt me-2"></i>Répartition par zone</h5>
            </div>
            <div class="chart-body">
                <canvas id="zoneChart" height="200"></canvas>
            </div>
            <div class="chart-footer">
                <span class="total-badge">Total: <strong>{{ number_format($stats['arbres'] ?? 0) }}</strong> arbres</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-leaf me-2"></i>Top espèces</h5>
            </div>
            <div class="chart-body">
                <canvas id="especeChart" height="200"></canvas>
            </div>
            <div class="chart-footer">
                <span class="total-badge">{{ number_format($stats['especes'] ?? 0) }} espèces différentes</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-calendar-alt me-2"></i>Plantations par mois</h5>
            </div>
            <div class="chart-body">
                <canvas id="plantationsChart" height="200"></canvas>
            </div>
            <div class="chart-footer">
                <span class="total-badge">Évolution mensuelle</span>
            </div>
        </div>
    </div>
</div>

<!-- Deuxième ligne de graphiques -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="mini-chart-card">
            <div class="d-flex align-items-center mb-2">
                <div class="mini-chart-icon" style="background: #e8f5e9;">
                    <i class="fas fa-tree text-success"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Arbres</h6>
                    <h4 class="mb-0">{{ number_format($stats['arbres'] ?? 0) }}</h4>
                </div>
            </div>
            <canvas id="miniArbresChart" height="60"></canvas>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="mini-chart-card">
            <div class="d-flex align-items-center mb-2">
                <div class="mini-chart-icon" style="background: #fff3e0;">
                    <i class="fas fa-map-marked-alt text-warning"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Zones</h6>
                    <h4 class="mb-0">{{ number_format($stats['zones'] ?? 0) }}</h4>
                </div>
            </div>
            <canvas id="miniZonesChart" height="60"></canvas>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="mini-chart-card">
            <div class="d-flex align-items-center mb-2">
                <div class="mini-chart-icon" style="background: #e0f2fe;">
                    <i class="fas fa-leaf text-info"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Espèces</h6>
                    <h4 class="mb-0">{{ number_format($stats['especes'] ?? 0) }}</h4>
                </div>
            </div>
            <canvas id="miniEspecesChart" height="60"></canvas>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="mini-chart-card">
            <div class="d-flex align-items-center mb-2">
                <div class="mini-chart-icon" style="background: #fce4ec;">
                    <i class="fas fa-newspaper text-danger"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Actualités</h6>
                    <h4 class="mb-0">{{ number_format($stats['actualites'] ?? 0) }}</h4>
                </div>
            </div>
            <canvas id="miniActualitesChart" height="60"></canvas>
        </div>
    </div>
</div>

<!-- Troisième ligne : messages et newsletter -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-envelope me-2"></i>Messages</h5>
                <div class="header-actions">
                    <span class="badge bg-danger">{{ number_format($stats['messages_non_lus'] ?? 0) }} non lus</span>
                </div>
            </div>
            <div class="chart-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <canvas id="messagesChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-summary">
                            <div class="summary-item">
                                <span class="label">Total messages</span>
                                <span class="value">{{ number_format($stats['total_messages'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Messages lus</span>
                                <span class="value">{{ number_format($stats['messages_lus'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Messages non lus</span>
                                <span class="value text-danger">{{ number_format($stats['messages_non_lus'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Répondus</span>
                                <span class="value">{{ number_format($stats['messages_repondus'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-users me-2"></i>Newsletter</h5>
                <div class="header-actions">
                    <span class="badge bg-success">{{ number_format($stats['newsletters_actifs'] ?? 0) }} actifs</span>
                </div>
            </div>
            <div class="chart-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <canvas id="newsletterChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-summary">
                            <div class="summary-item">
                                <span class="label">Total abonnés</span>
                                <span class="value">{{ number_format($stats['newsletters'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Abonnés actifs</span>
                                <span class="value text-success">{{ number_format($stats['newsletters_actifs'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Inactifs</span>
                                <span class="value text-muted">{{ number_format($stats['newsletters_inactifs'] ?? 0) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Taux d'engagement</span>
                                <span class="value">
                                    @if(($stats['newsletters'] ?? 0) > 0)
                                        {{ round(($stats['newsletters_actifs'] ?? 0) * 100 / ($stats['newsletters'] ?? 1), 1) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Santé des arbres (en graphique) -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="chart-card">
            <div class="chart-header">
                <h5><i class="fas fa-heartbeat me-2"></i>État de santé des arbres</h5>
            </div>
            <div class="chart-body">
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="santeChart" height="200"></canvas>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-summary">
                            <div class="summary-item">
                                <span class="label">Excellent</span>
                                <span class="value text-success">{{ $santeArbres['excellent'] ?? 0 }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Bon</span>
                                <span class="value text-info">{{ $santeArbres['bon'] ?? 0 }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Moyen</span>
                                <span class="value text-warning">{{ $santeArbres['moyen'] ?? 0 }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Surveillé</span>
                                <span class="value text-danger">{{ $santeArbres['surveille'] ?? 0 }}</span>
                            </div>
                            <div class="summary-item total">
                                <span class="label">Total</span>
                                <span class="value">{{ ($santeArbres['excellent'] ?? 0) + ($santeArbres['bon'] ?? 0) + ($santeArbres['moyen'] ?? 0) + ($santeArbres['surveille'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableaux récents -->
<div class="row g-4">
    <!-- Derniers arbres -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Derniers arbres ajoutés</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($derniersArbres) && $derniersArbres->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($derniersArbres as $arbre)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $arbre->nom ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">
                                            {{ $arbre->zone->nom ?? 'N/A' }} | 
                                            {{ $arbre->espece->nom_local ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $arbre->created_at ? $arbre->created_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.arbres.index') }}" class="btn btn-sm btn-outline-success">Voir tous les arbres</a>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Aucun arbre ajouté</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Derniers messages -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Derniers messages</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($derniersMessages) && $derniersMessages->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($derniersMessages as $message)
                            <div class="list-group-item {{ isset($message->lu) && !$message->lu ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $message->nom ?? 'N/A' }}</strong>
                                        @if(isset($message->lu) && !$message->lu)
                                            <span class="badge bg-danger ms-2">Nouveau</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ isset($message->message) ? Str::limit($message->message, 30) : 'N/A' }}</small>
                                    </div>
                                    <small class="text-muted">{{ $message->created_at ? $message->created_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-success">Voir tous les messages</a>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Aucun message</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Dernières actualités -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Dernières actualités</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($dernieresActualites) && $dernieresActualites->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($dernieresActualites as $actualite)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ isset($actualite->titre) ? Str::limit($actualite->titre, 30) : 'N/A' }}</strong><br>
                                        <small class="text-muted">
                                            {{ $actualite->vues ?? 0 }} vues
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $actualite->created_at ? $actualite->created_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.actualites.index') }}" class="btn btn-sm btn-outline-success">Voir toutes les actualités</a>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Aucune actualité</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.welcome-card {
    background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
    color: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.2);
}

.welcome-card h2 {
    margin: 0;
    font-weight: 600;
    font-size: 1.8rem;
}

.welcome-card small {
    opacity: 0.9;
}

/* Cartes de graphiques */
.chart-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 20px;
    height: 100%;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.chart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(46,125,50,0.15);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 2px solid #e8f5e9;
    padding-bottom: 10px;
}

.chart-header h5 {
    color: #2E7D32;
    font-weight: 600;
    margin: 0;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.chart-header h5 i {
    font-size: 1.1rem;
}

.chart-body {
    margin-bottom: 15px;
}

.chart-footer {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #e8f5e9;
}

.total-badge {
    background: #e8f5e9;
    color: #2E7D32;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Mini cartes */
.mini-chart-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 15px;
    height: 100%;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.mini-chart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(46,125,50,0.1);
}

.mini-chart-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.mini-chart-icon i {
    font-size: 1.3rem;
}

/* Résumé statistiques */
.stats-summary {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #dee2e6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item.total {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 2px solid #2E7D32;
    font-weight: 700;
}

.summary-item .label {
    color: #6c757d;
    font-size: 0.9rem;
}

.summary-item .value {
    font-weight: 600;
    color: #2E7D32;
}

/* Badges */
.badge.bg-danger {
    background: #dc3545 !important;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 20px;
}

.badge.bg-success {
    background: #28a745 !important;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 20px;
}

/* Cartes des listes */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    border: 1px solid rgba(0,0,0,0.05);
}

.card-header {
    background: white;
    border-bottom: 2px solid #e8f5e9;
    padding: 15px 20px;
    border-radius: 15px 15px 0 0 !important;
}

.card-header h5 {
    color: #2E7D32;
    font-weight: 600;
    margin: 0;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 12px 20px;
    border-color: rgba(0,0,0,0.05);
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.card-footer {
    background: white;
    border-top: 1px solid #e8f5e9;
    padding: 12px;
    border-radius: 0 0 15px 15px !important;
}

.btn-outline-success {
    color: #2E7D32;
    border-color: #2E7D32;
    border-width: 2px;
    font-weight: 600;
    padding: 0.4rem 1.2rem;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.btn-outline-success:hover {
    background: #2E7D32;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,125,50,0.3);
}

/* Animation */
.row > [class*="col-"] {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.row > [class*="col-"]:nth-child(1) { animation-delay: 0.1s; }
.row > [class*="col-"]:nth-child(2) { animation-delay: 0.2s; }
.row > [class*="col-"]:nth-child(3) { animation-delay: 0.3s; }
.row > [class*="col-"]:nth-child(4) { animation-delay: 0.4s; }

/* Responsive */
@media (max-width: 768px) {
    .chart-card .row {
        flex-direction: column;
    }
    
    .stats-summary {
        margin-top: 15px;
    }
    
    .mini-chart-card .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .mini-chart-icon {
        margin-bottom: 10px;
    }
    
    .ms-3 {
        margin-left: 0 !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration commune des couleurs
    Chart.defaults.font.family = "'Segoe UI', 'Arial', sans-serif";
    Chart.defaults.color = '#495057';
    
    // 1. Graphique des zones (donut) avec couleurs variées
    @if(isset($arbresParZone) && $arbresParZone->count() > 0)
    try {
        const zoneCtx = document.getElementById('zoneChart').getContext('2d');
        new Chart(zoneCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($arbresParZone->pluck('nom')->values()->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($arbresParZone->pluck('count')->values()->toArray()) !!},
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FFCD56',
                        '#36A2EB', '#9966FF', '#FF8A80', '#81C784', '#64B5F6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} arbres (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    } catch (e) { console.log('Erreur zoneChart:', e); }
    @endif

    // 2. Graphique des espèces (barres horizontales) avec couleurs jaune, rouge, vert
    @if(isset($arbresParEspece) && $arbresParEspece->count() > 0)
    try {
        const especes = {!! json_encode($arbresParEspece->pluck('nom')->values()->toArray()) !!};
        const counts = {!! json_encode($arbresParEspece->pluck('count')->values()->toArray()) !!};
        
        // Générer des couleurs alternées : jaune, rouge, vert
        const colorPalette = ['#f1c40f', '#e74c3c', '#27ae60', '#f39c12', '#c0392b', '#2ecc71', '#f1c40f', '#e74c3c', '#27ae60'];
        
        const backgroundColors = counts.map((_, index) => {
            return colorPalette[index % colorPalette.length];
        });
        
        const especeCtx = document.getElementById('especeChart').getContext('2d');
        new Chart(especeCtx, {
            type: 'bar',
            data: {
                labels: especes,
                datasets: [{
                    label: 'Nombre d\'arbres',
                    data: counts,
                    backgroundColor: backgroundColors,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' arbre' + (context.raw > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } catch (e) { console.log('Erreur especeChart:', e); }
    @endif

    // 3. Graphique des plantations (ligne)
    @if(isset($plantationsParMois) && count($plantationsParMois) > 0)
    try {
        const plantationsCtx = document.getElementById('plantationsChart').getContext('2d');
        new Chart(plantationsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($plantationsParMois, 'mois')) !!},
                datasets: [{
                    label: 'Plantations',
                    data: {!! json_encode(array_column($plantationsParMois, 'count')) !!},
                    borderColor: '#2E7D32',
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2E7D32',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' plantation' + (context.raw > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } catch (e) { console.log('Erreur plantationsChart:', e); }
    @endif

    // 4. Mini graphiques sparkline
    const miniCharts = [
        { id: 'miniArbresChart', data: [5, 8, 12, 9, 15, 20, 18, 22, 25, 30, 28, 35], color: '#2E7D32' },
        { id: 'miniZonesChart', data: [2, 3, 3, 4, 4, 5, 5, 5, 6, 6, 7, 7], color: '#FF9800' },
        { id: 'miniEspecesChart', data: [3, 4, 5, 7, 8, 10, 12, 12, 13, 14, 15, 16], color: '#03A9F4' },
        { id: 'miniActualitesChart', data: [2, 1, 3, 4, 2, 5, 6, 4, 3, 7, 5, 8], color: '#F44336' }
    ];
    
    miniCharts.forEach(chart => {
        const ctx = document.getElementById(chart.id);
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
                    datasets: [{
                        data: chart.data,
                        borderColor: chart.color,
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { line: { borderWidth: 2 } }
                }
            });
        }
    });

    // 5. Graphique des messages (donut)
    @if(isset($stats['messages_lus']) || isset($stats['messages_non_lus']))
    try {
        const messagesCtx = document.getElementById('messagesChart').getContext('2d');
        new Chart(messagesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lus', 'Non lus'],
                datasets: [{
                    data: [
                        {{ $stats['messages_lus'] ?? 0 }},
                        {{ $stats['messages_non_lus'] ?? 0 }}
                    ],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                cutout: '70%'
            }
        });
    } catch (e) { console.log('Erreur messagesChart:', e); }
    @endif

    // 6. Graphique de la newsletter (donut)
    @if(isset($stats['newsletters_actifs']) || isset($stats['newsletters_inactifs']))
    try {
        const newsletterCtx = document.getElementById('newsletterChart').getContext('2d');
        new Chart(newsletterCtx, {
            type: 'doughnut',
            data: {
                labels: ['Actifs', 'Inactifs'],
                datasets: [{
                    data: [
                        {{ $stats['newsletters_actifs'] ?? 0 }},
                        {{ $stats['newsletters_inactifs'] ?? 0 }}
                    ],
                    backgroundColor: ['#4CAF50', '#9E9E9E'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                cutout: '70%'
            }
        });
    } catch (e) { console.log('Erreur newsletterChart:', e); }
    @endif

    // 7. Graphique de santé des arbres (barres)
    @if(isset($santeArbres))
    try {
        const santeCtx = document.getElementById('santeChart').getContext('2d');
        new Chart(santeCtx, {
            type: 'bar',
            data: {
                labels: ['Excellent', 'Bon', 'Moyen', 'Surveillé'],
                datasets: [{
                    data: [
                        {{ $santeArbres['excellent'] ?? 0 }},
                        {{ $santeArbres['bon'] ?? 0 }},
                        {{ $santeArbres['moyen'] ?? 0 }},
                        {{ $santeArbres['surveille'] ?? 0 }}
                    ],
                    backgroundColor: ['#4CAF50', '#8BC34A', '#FFC107', '#F44336'],
                    borderRadius: 6,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' arbre' + (context.raw > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } catch (e) { console.log('Erreur santeChart:', e); }
    @endif
});
</script>
@endpush