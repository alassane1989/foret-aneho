@extends('layouts.app')

@section('title', 'Documentation technique - Forêt Urbaine d\'Aného')

@push('styles')
<style>
    .doc-section {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: var(--card-shadow);
    }
    .doc-section h2 {
        color: var(--primary-color);
        font-size: 1.5rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
    }
    .doc-section h3 {
        font-size: 1.2rem;
        margin-top: 20px;
        margin-bottom: 10px;
        color: var(--text-primary);
    }
    pre {
        background: var(--bg-tertiary);
        padding: 15px;
        border-radius: 10px;
        overflow-x: auto;
        font-size: 0.85rem;
        color: var(--text-primary);
    }
    code {
        background: var(--bg-tertiary);
        padding: 2px 6px;
        border-radius: 5px;
        font-size: 0.85rem;
    }
    .table-of-contents {
        background: var(--bg-secondary);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .table-of-contents ul {
        list-style: none;
        padding-left: 0;
    }
    .table-of-contents li {
        margin-bottom: 8px;
    }
    .table-of-contents a {
        color: var(--primary-color);
        text-decoration: none;
    }
    .table-of-contents a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="table-of-contents sticky-top" style="top: 100px;">
                <h5><i class="fas fa-list me-2 text-success"></i> Sommaire</h5>
                <ul>
                    <li><a href="#presentation">1. Présentation du projet</a></li>
                    <li><a href="#installation">2. Installation locale</a></li>
                    <li><a href="#structure">3. Structure de la BDD</a></li>
                    <li><a href="#architecture">4. Architecture du code</a></li>
                    <li><a href="#commandes">5. Commandes Artisan</a></li>
                    <li><a href="#deploiement">6. Déploiement</a></li>
                    <li><a href="#maintenance">7. Maintenance</a></li>
                    <li><a href="#depannage">8. Dépannage</a></li>
                    <li><a href="#credits">9. Crédits</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-9">
            <h1 class="mb-4" style="color: var(--primary-color);">
                <i class="fas fa-book me-3"></i> Documentation technique
            </h1>
            <p class="lead mb-5">Version 1.0.0 – Dernière mise à jour : 12 Avril 2026</p>
            
            <!-- Section 1 -->
            <div id="presentation" class="doc-section">
                <h2>1. Présentation du projet</h2>
                <p><strong>Forêt Urbaine d'Aného</strong> est une plateforme web de numérisation des arbres, zones et espèces de la forêt urbaine d'Aného (Togo).</p>
                <ul>
                    <li><strong>Public</strong> : Carte interactive (Leaflet), catalogue des espèces, fiches arbres avec QR code</li>
                    <li><strong>Administration</strong> : Dashboard, CRUD complet, exports (Excel/PDF/CSV)</li>
                </ul>
                <p><strong>Technologies :</strong> Laravel 12, PHP 8.2, MySQL, Bootstrap 5, Leaflet.js, Chart.js</p>
            </div>
            
            <!-- Section 2 -->
            <div id="installation" class="doc-section">
                <h2>2. Installation locale</h2>
                <pre><code>git clone https://github.com/foret-aneho/foret-aneho.git
cd foret-aneho
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run production
php artisan serve</code></pre>
                <p><strong>Compte admin par défaut :</strong> admin@foret-aneho.tg / admin@2026</p>
            </div>
            
            <!-- Section 3 -->
            <div id="structure" class="doc-section">
                <h2>3. Structure de la base de données</h2>
                <p><strong>Tables principales :</strong></p>
                <ul>
                    <li><code>zones</code> – GLIDJI, LOLAN, NLESS, YVELINES</li>
                    <li><code>especes</code> – Catalogue des espèces</li>
                    <li><code>arbres</code> – Inventaire des arbres (avec QR code)</li>
                    <li><code>actualites</code> – Articles</li>
                    <li><code>contacts</code> – Messages reçus</li>
                    <li><code>newsletters</code> – Abonnés</li>
                    <li><code>users</code> – Administrateurs</li>
                </ul>
            </div>
            
            <!-- Section 4 -->
            <div id="architecture" class="doc-section">
                <h2>4. Architecture du code</h2>
                <pre><code>app/
├── Http/Controllers/Admin/     # Contrôleurs admin
├── Models/                      # 7 modèles Eloquent
database/
├── migrations/                  # 12 migrations
├── seeders/                     # Données initiales
resources/views/
├── layouts/                     # app.blade.php, admin.blade.php
├── admin/                       # CRUD admin
├── home/, zones/, arbres/...    # Vues publiques
routes/web.php                   # Routes principales</code></pre>
            </div>
            
            <!-- Section 5 -->
            <div id="commandes" class="doc-section">
                <h2>5. Commandes Artisan essentielles</h2>
                <pre><code>php artisan migrate:fresh --seed   # Réinitialiser BDD
php artisan make:model Nom -m      # Créer modèle + migration
php artisan make:controller Admin/NomController --resource
php artisan route:list             # Voir toutes les routes
php artisan optimize               # Optimiser le site
php artisan storage:link           # Lien pour les uploads</code></pre>
            </div>
            
            <!-- Section 6 -->
            <div id="deploiement" class="doc-section">
                <h2>6. Déploiement en production</h2>
                <p><strong>Script de déploiement :</strong></p>
                <pre><code>#!/bin/bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm run production
php artisan migrate --force
php artisan optimize
chmod -R 775 storage bootstrap/cache</code></pre>
                <p><strong>Cron job :</strong> <code>* * * * * cd /home/foret-aneho && php artisan schedule:run >> /dev/null 2>&1</code></p>
            </div>
            
            <!-- Section 7 -->
            <div id="maintenance" class="doc-section">
                <h2>7. Maintenance et sauvegardes</h2>
                <p><strong>Backup automatique BDD :</strong></p>
                <pre><code>php artisan make:command BackupDatabase</code></pre>
                <p>La sauvegarde est programmée chaque jour à 2h du matin dans <code>storage/backups/</code></p>
            </div>
            
            <!-- Section 8 -->
            <div id="depannage" class="doc-section">
                <h2>8. Dépannage (Troubleshooting)</h2>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Problème</th><th>Solution</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Page blanche (500)</td><td><code>APP_DEBUG=true</code> + consulter <code>storage/logs/laravel.log</code></td></tr>
                        <tr><td>Images non affichées</td><td><code>php artisan storage:link</code></td></tr>
                        <tr><td>QR code non généré</td><td>Vérifier le <code>boot()</code> dans <code>Arbre.php</code></td></tr>
                        <tr><td>Erreur de permission</td><td><code>chmod -R 775 storage bootstrap/cache</code></td></tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Section 9 -->
            <div id="credits" class="doc-section">
                <h2>9. Crédits</h2>
                <p><strong>Équipe de développement :</strong></p>
                <ul>
                    <li><strong>Alassane SANT'ANNA</strong> – Chef de projet & Back-end</li>
                    <li><strong>joel Yaovi HOUNKPATI</strong> – Front-end & Intégrateur</li>
                    <li><strong>Pauline Amigan AGBOKOU</strong> – Cartographie & API</li>
                    <li><strong> Koffi Justin  KOUTOWOU</strong> – Administration & CRUD</li>
                </ul>
                <p><strong>Encadrement :</strong></p>
                <ul>
                    <li>Mme Priskila AGBOGBE &  – Service Communitation</li>
                    <li>M. Marthey & M.Blaise – Responsables Informatique</li>
                    <li>M. Alex WILSON – Tuteur pédagogique</li>

                </ul>
                <p><strong>Structure d'accueil :</strong> Mairie d'Aného / Commune des Lacs 1</p>
            </div>
        </div>
    </div>
</div>
@endsection