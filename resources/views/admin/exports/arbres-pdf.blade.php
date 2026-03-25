<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des arbres - Forêt Urbaine d'Aného</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        h1 {
            color: #2E7D32;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2E7D32;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #1B5E20;
            margin: 5px 0;
        }
        .header p {
            color: #666;
            font-size: 11px;
            margin: 0;
        }
        .stats {
            margin-bottom: 30px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
            border-left: 4px solid #2E7D32;
        }
        .stats h3 {
            color: #2E7D32;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .stat-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-item .number {
            font-size: 20px;
            font-weight: bold;
            color: #2E7D32;
        }
        .stat-item .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        th {
            background: #2E7D32;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 9px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .page-break {
            page-break-after: always;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-excellent {
            background: #d4edda;
            color: #155724;
        }
        .badge-bon {
            background: #cce5ff;
            color: #004085;
        }
        .badge-moyen {
            background: #fff3cd;
            color: #856404;
        }
        .badge-surveille {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Forêt Urbaine d'Aného</h1>
        <h2>Liste des arbres</h2>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <h3>📊 Statistiques</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="number">{{ $stats['total'] }}</div>
                <div class="label">Total arbres</div>
            </div>
            
            @foreach($stats['par_zone'] as $zone => $count)
            <div class="stat-item">
                <div class="number">{{ $count }}</div>
                <div class="label">Zone {{ $zone }}</div>
            </div>
            @endforeach
        </div>
        
        <div style="margin-top: 15px;">
            <strong>Répartition par état de santé :</strong>
            <div style="display: flex; gap: 10px; margin-top: 5px;">
                @foreach($stats['par_sante'] as $etat => $count)
                <span class="badge badge-{{ $etat }}">
                    {{ ucfirst($etat) }} : {{ $count }}
                </span>
                @endforeach
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Espèce</th>
                <th>Zone</th>
                <th>Date plantation</th>
                <th>Planteur</th>
                <th>Hauteur</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arbres as $arbre)
            <tr>
                <td>{{ $arbre->id }}</td>
                <td><strong>{{ $arbre->nom }}</strong></td>
                <td>{{ $arbre->espece->nom_local ?? 'N/A' }}</td>
                <td>{{ $arbre->zone->nom ?? 'N/A' }}</td>
                <td>{{ $arbre->date_plantation->format('d/m/Y') }}</td>
                <td>{{ $arbre->planteur_nom }}</td>
                <td>{{ $arbre->hauteur ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $arbre->etat_sante }}">
                        {{ ucfirst($arbre->etat_sante) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Forêt Urbaine d'Aného - Tous droits réservés</p>
        <p>Document généré automatiquement - {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>