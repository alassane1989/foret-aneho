<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des espèces - Forêt Urbaine d'Aného</title>
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
        .stats h3 {
            color: #2E7D32;
            margin-top: 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 10px;
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
        }
        td {
            padding: 6px 8px;
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
        .badge-locale {
            background: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .badge-introduite {
            background: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Forêt Urbaine d'Aného</h1>
        <h2>Liste des espèces</h2>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <h3>📊 Statistiques</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="number">{{ $stats['total'] }}</div>
                <div class="label">Total espèces</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $stats['locales'] }}</div>
                <div class="label">Espèces locales</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $stats['introduites'] }}</div>
                <div class="label">Espèces introduites</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom local</th>
                <th>Nom scientifique</th>
                <th>Famille</th>
                <th>Catégorie</th>
                <th>Origine</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($especes as $espece)
            <tr>
                <td>{{ $espece->id }}</td>
                <td><strong>{{ $espece->nom_local }}</strong></td>
                <td><em>{{ $espece->nom_scientifique }}</em></td>
                <td>{{ $espece->famille ?? 'N/A' }}</td>
                <td>{{ $espece->categorie_formatee ?? $espece->categorie }}</td>
                <td>
                    @if($espece->est_locale)
                        <span class="badge-locale">Locale</span>
                    @else
                        <span class="badge-introduite">Introduite</span>
                    @endif
                </td>
                <td>{{ $espece->statut_conservation ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Forêt Urbaine d'Aného</p>
        <p>Document généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>