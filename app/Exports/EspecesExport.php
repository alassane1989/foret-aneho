<?php

namespace App\Exports;

use App\Models\Espece;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EspecesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Espece::orderBy('nom_local')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom scientifique',
            'Nom local',
            'Famille',
            'Genre',
            'Origine',
            'Type',
            'Hauteur max',
            'Longévité',
            'Catégorie',
            'Statut conservation',
            'Locale',
            'Nombre d\'arbres',
            'Créé le',
            'Mis à jour le'
        ];
    }

    public function map($espece): array
    {
        return [
            $espece->id,
            $espece->nom_scientifique,
            $espece->nom_local,
            $espece->famille ?? 'N/A',
            $espece->genre ?? 'N/A',
            $espece->origine ?? 'N/A',
            $espece->type ?? 'N/A',
            $espece->hauteur_max ?? 'N/A',
            $espece->longevite ?? 'N/A',
            $espece->categorie_formatee ?? $espece->categorie,
            $espece->statut_conservation ?? 'N/A',
            $espece->est_locale ? 'Locale' : 'Introduite',
            $espece->nombre_arbres,
            $espece->created_at->format('d/m/Y H:i'),
            $espece->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E7D32']]],
        ];
    }
}