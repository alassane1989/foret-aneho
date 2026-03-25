<?php

namespace App\Exports;

use App\Models\Zone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ZonesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Zone::orderBy('ordre')->orderBy('nom')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Slug',
            'Description courte',
            'Superficie',
            'Nombre d\'arbres',
            'Nombre d\'espèces',
            'Couleur',
            'Latitude',
            'Longitude',
            'Adresse accès',
            'Ordre',
            'Active',
            'Créé le',
            'Mis à jour le'
        ];
    }

    public function map($zone): array
    {
        return [
            $zone->id,
            $zone->nom,
            $zone->slug,
            $zone->description_courte,
            $zone->superficie ?? 'N/A',
            $zone->nombre_arbres,
            $zone->nombre_especes,
            $zone->couleur,
            $zone->latitude ?? 'N/A',
            $zone->longitude ?? 'N/A',
            $zone->adresse_acces ?? 'N/A',
            $zone->ordre,
            $zone->est_active ? 'Oui' : 'Non',
            $zone->created_at->format('d/m/Y H:i'),
            $zone->updated_at->format('d/m/Y H:i')
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