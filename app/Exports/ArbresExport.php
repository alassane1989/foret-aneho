<?php

namespace App\Exports;

use App\Models\Arbre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArbresExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filtres;

    public function __construct($filtres = [])
    {
        $this->filtres = $filtres;
    }

    /**
     * Récupérer les données avec filtres
     */
    public function collection()
    {
        $query = Arbre::with(['zone', 'espece']);

        if (!empty($this->filtres['zone'])) {
            $query->where('zone_id', $this->filtres['zone']);
        }

        if (!empty($this->filtres['espece'])) {
            $query->where('espece_id', $this->filtres['espece']);
        }

        if (!empty($this->filtres['search'])) {
            $query->where('nom', 'like', '%' . $this->filtres['search'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Espèce',
            'Nom scientifique',
            'Zone',
            'Date plantation',
            'Planteur',
            'Hauteur',
            'Circonférence',
            'Latitude',
            'Longitude',
            'État de santé',
            'Vues',
            'QR Code',
            'Créé le',
            'Mis à jour le'
        ];
    }

    /**
     * Mapping des données
     */
    public function map($arbre): array
    {
        return [
            $arbre->id,
            $arbre->nom,
            $arbre->espece->nom_local ?? 'N/A',
            $arbre->espece->nom_scientifique ?? 'N/A',
            $arbre->zone->nom ?? 'N/A',
            $arbre->date_plantation->format('d/m/Y'),
            $arbre->planteur_nom,
            $arbre->hauteur ?? 'N/A',
            $arbre->circonference ?? 'N/A',
            $arbre->latitude,
            $arbre->longitude,
            ucfirst($arbre->etat_sante),
            $arbre->vues,
            $arbre->qr_code ?? 'N/A',
            $arbre->created_at->format('d/m/Y H:i'),
            $arbre->updated_at->format('d/m/Y H:i')
        ];
    }

    /**
     * Styles des cellules
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E7D32']]],
        ];
    }
}