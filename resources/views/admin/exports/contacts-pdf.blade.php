<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export des messages de contact</title>
    <style>
        /* Styles généraux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 15px;
        }
        
        /* En-tête */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1e3c72;
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        
        .header h1 {
            color: #1e3c72;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #2c5282;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .header .meta {
            font-size: 11px;
            color: #4a5568;
            background: #edf2f7;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 5px;
        }
        
        /* Filtres appliqués */
        .filtres {
            background: #ebf8ff;
            border-left: 6px solid #4299e1;
            padding: 12px 18px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
            font-size: 11px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .filtres strong {
            color: #2c5282;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }
        
        .filtres span {
            background: #fff;
            padding: 3px 10px;
            border-radius: 15px;
            margin-right: 8px;
            display: inline-block;
            margin-bottom: 5px;
            border: 1px solid #bee3f8;
        }
        
        /* Cartes de statistiques */
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 120px;
            background: #fff;
            border-radius: 10px;
            padding: 15px 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        
        .stat-card.total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.non-lus {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: white;
        }
        
        .stat-card.lus {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #1a202c;
        }
        
        .stat-card.repondu {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            color: white;
        }
        
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        
        .stat-card .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        
        /* Tableau principal */
        .table-container {
            margin-bottom: 25px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        th {
            background: #2d3748;
            color: white;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #4a5568;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        tr:hover {
            background-color: #edf2f7;
        }
        
        /* Badges et étiquettes */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .badge-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        
        .badge-warning {
            background: #feebc8;
            color: #744210;
            border: 1px solid #fbd38d;
        }
        
        .badge-info {
            background: #bee3f8;
            color: #2c5282;
            border: 1px solid #90cdf4;
        }
        
        .badge-primary {
            background: #e9d8fd;
            color: #44337a;
            border: 1px solid #d6bcfa;
        }
        
        /* Sujets */
        .sujet-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 500;
        }
        
        .sujet-info { background: #cff4fc; color: #055160; border: 1px solid #9eeaf9; }
        .sujet-visite { background: #d1e7dd; color: #0a3622; border: 1px solid #a3cfbb; }
        .sujet-participation { background: #fff3cd; color: #664d03; border: 1px solid #ffe69c; }
        .sujet-projet { background: #e2d1ff; color: #3a2b5c; border: 1px solid #c7b3ff; }
        .sujet-partenariat { background: #f8d7da; color: #721c24; border: 1px solid #f5c2c7; }
        .sujet-autre { background: #e2e3e5; color: #41464b; border: 1px solid #d3d6d8; }
        
        /* Message preview */
        .message-preview {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #2d3748;
            font-style: italic;
        }
        
        /* Dates */
        .date-cell {
            font-size: 9px;
            color: #718096;
            white-space: nowrap;
        }
        
        /* Email */
        .email-cell {
            color: #2c5282;
            font-size: 10px;
        }
        
        /* Section résumé par sujet */
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #cbd5e0;
        }
        
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .summary-item {
            flex: 1;
            min-width: 150px;
            background: white;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-item .sujet-nom {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .summary-item .sujet-stats {
            display: flex;
            justify-content: space-between;
            color: #718096;
            font-size: 10px;
        }
        
        .summary-item .sujet-count {
            font-size: 18px;
            font-weight: bold;
            color: #1e3c72;
        }
        
        /* Pied de page */
        .footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #a0aec0;
        }
        
        .footer .signature {
            font-size: 10px;
            color: #718096;
            margin-top: 5px;
        }
        
        /* Page break */
        .page-break {
            page-break-after: always;
        }
        
        /* Utilitaires */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mt-2 { margin-top: 8px; }
        .p-2 { padding: 8px; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>OBSERVATOIRE NATIONAL DES VIOLENCES</h1>
        <h1>EN MILIEU SCOLAIRE</h1>
        <div class="subtitle">Export des messages de contact</div>
        <div class="meta">
            <span>📅 Généré le : {{ $date_export }}</span>
            <span style="margin-left: 15px;">👤 Par : {{ $exportateur ?? 'Administrateur' }}</span>
        </div>
    </div>

    <!-- Filtres appliqués -->
    @if(!empty($filtres) && count($filtres) > 0)
    <div class="filtres">
        <strong>🔍 Filtres appliqués :</strong>
        @foreach($filtres as $filtre)
            <span>{{ $filtre }}</span>
        @endforeach
    </div>
    @endif

    <!-- Statistiques globales -->
    <div class="stats-container">
        <div class="stat-card total">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total messages</div>
        </div>
        <div class="stat-card non-lus">
            <div class="stat-value">{{ $stats['non_lus'] }}</div>
            <div class="stat-label">Non lus</div>
        </div>
        <div class="stat-card lus">
            <div class="stat-value">{{ $stats['lus'] }}</div>
            <div class="stat-label">Lus</div>
        </div>
        <div class="stat-card repondu">
            <div class="stat-value">{{ $stats['repondu'] }}</div>
            <div class="stat-label">Répondu</div>
        </div>
    </div>

    <!-- Tableau des messages -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="12%">Nom</th>
                    <th width="15%">Email</th>
                    <th width="12%">Sujet</th>
                    <th width="20%">Message</th>
                    <th width="8%">Statut</th>
                    <th width="10%">Date</th>
                    <th width="8%">Réponse</th>
                    <th width="10%">Date réponse</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td class="font-bold">#{{ $contact->id }}</td>
                    <td>{{ $contact->nom }}</td>
                    <td class="email-cell">{{ $contact->email }}</td>
                    <td>
                        @php
                            $sujetClass = match($contact->sujet) {
                                'info' => 'sujet-info',
                                'visite' => 'sujet-visite',
                                'participation' => 'sujet-participation',
                                'projet' => 'sujet-projet',
                                'partenariat' => 'sujet-partenariat',
                                default => 'sujet-autre'
                            };
                        @endphp
                        <span class="sujet-badge {{ $sujetClass }}">
                            {{ $sujets[$contact->sujet] ?? $contact->sujet }}
                        </span>
                    </td>
                    <td>
                        <div class="message-preview" title="{{ $contact->message }}">
                            {{ Str::limit($contact->message, 60) }}
                        </div>
                    </td>
                    <td>
                        @if($contact->lu)
                            <span class="badge badge-success">✓ Lu</span>
                        @else
                            <span class="badge badge-warning">⏳ Non lu</span>
                        @endif
                    </td>
                    <td class="date-cell">
                        {{ $contact->created_at->format('d/m/Y') }}<br>
                        <small>{{ $contact->created_at->format('H:i') }}</small>
                    </td>
                    <td class="text-center">
                        @if($contact->reponse)
                            <span class="badge badge-success">✓ Oui</span>
                        @else
                            <span class="badge badge-warning">✗ Non</span>
                        @endif
                    </td>
                    <td class="date-cell">
                        @if($contact->date_reponse)
                            {{ $contact->date_reponse->format('d/m/Y') }}<br>
                            <small>{{ $contact->date_reponse->format('H:i') }}</small>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px;">
                        <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">
                            📭 Aucun message trouvé
                        </div>
                        <div style="font-size: 11px; color: #a0aec0;">
                            Aucun message ne correspond aux critères sélectionnés
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Statistiques détaillées par sujet -->
    @if($contacts->count() > 0)
    <div class="summary-section">
        <div class="summary-title">📊 Répartition par sujet</div>
        <div class="summary-grid">
            @foreach($statsParSujet as $key => $count)
                @if($count > 0)
                <div class="summary-item">
                    <div class="sujet-nom">{{ $sujets[$key] ?? $key }}</div>
                    <div class="sujet-stats">
                        <span class="sujet-count">{{ $count }}</span>
                        <span>{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 }}%</span>
                    </div>
                    <div style="font-size: 9px; color: #718096; margin-top: 5px;">
                        Non lus: {{ $contacts->where('sujet', $key)->where('lu', false)->count() }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Graphique en barres ASCII (optionnel) -->
    <div style="margin-top: 25px; padding: 15px; background: #f1f5f9; border-radius: 8px;">
        <div style="font-weight: bold; margin-bottom: 10px; color: #1e3c72;">
            📈 Distribution des messages
        </div>
        @foreach($statsParSujet as $key => $count)
            @if($count > 0)
            <div style="margin-bottom: 8px;">
                <div style="display: flex; align-items: center;">
                    <span style="width: 120px; font-size: 10px;">{{ $sujets[$key] ?? $key }}:</span>
                    <div style="flex: 1;">
                        @php
                            $pourcentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                            $barWidth = min(100, $pourcentage);
                        @endphp
                        <div style="display: flex; align-items: center;">
                            <div style="width: 200px; height: 16px; background: #e2e8f0; border-radius: 8px; overflow: hidden;">
                                <div style="width: {{ $barWidth }}%; height: 100%; background: linear-gradient(90deg, #4299e1, #667eea);"></div>
                            </div>
                            <span style="margin-left: 10px; font-size: 10px; font-weight: bold;">{{ number_format($pourcentage, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Informations complémentaires -->
    <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 9px; color: #718096; background: #f8fafc; padding: 10px; border-radius: 5px;">
        <div>
            <strong>Période couverte :</strong> 
            @if($contacts->count() > 0)
                Du {{ $contacts->min('created_at')->format('d/m/Y') }} au {{ $contacts->max('created_at')->format('d/m/Y') }}
            @else
                Aucune donnée
            @endif
        </div>
        <div>
            <strong>Version :</strong> 1.0
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div>Document généré automatiquement - Observatoire National des Violences en milieu scolaire</div>
        <div class="signature">
            Ce document est confidentiel et destiné à l'usage interne uniquement
        </div>
        <div style="margin-top: 8px;">
            Page 1/1 | {{ $contacts->count() }} message(s) | {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>