<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des zones - Forêt Urbaine d'Aného</title>
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
        .stats {
            margin-bottom: 30px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
            border-left: 4px solid #2E7D32;
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
        }
        .stat-item .number {
            font-size: 20px;
            font-weight: bold;
            color: #2E7D32;
        }
        .stat-item .label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #2E7D32;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
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
        .badge-active {
            background: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Forêt Urbaine d'Aného</h1>
        <h2>Liste des zones</h2>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <h3>📊 Statistiques</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="number">{{ $stats['total'] }}</div>
                <div class="label">Total zones</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $stats['total_arbres'] }}</div>
                <div class="label">Total arbres</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $stats['total_especes'] }}</div>
                <div class="label">Total espèces</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Superficie</th>
                <th>Arbres</th>
                <th>Espèces</th>
                <th>Couleur</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zones as $zone)
            <tr>
                <td><strong>{{ $zone->nom }}</strong></td>
                <td>{{ Str::limit($zone->description_courte, 40) }}</td>
                <td>{{ $zone->superficie ?? 'N/A' }}</td>
                <td>{{ $zone->nombre_arbres }}</td>
                <td>{{ $zone->nombre_especes }}</td>
                <td>
                    <span style="display: inline-block; width: 15px; height: 15px; background-color: {{ $zone->couleur }}; border-radius: 3px;"></span>
                </td>
                <td>
                    @if($zone->est_active)
                        <span class="badge-active">Active</span>
                    @else
                        <span class="badge-inactive">Inactive</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Forêt Urbaine d'Aného - Document généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>