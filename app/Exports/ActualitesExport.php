<?php

namespace App\Exports;

use App\Models\Actualite;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActualitesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Actualite::orderBy('date_publication', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Titre',
            'Slug',
            'Catégorie',
            'Auteur',
            'Date publication',
            'Vues',
            'Statut',
            'Tags',
            'Créé le',
            'Mis à jour le'
        ];
    }

    public function map($actualite): array
    {
        $tags = '';
        if ($actualite->tags) {
            $tagsArray = is_array($actualite->tags) ? $actualite->tags : json_decode($actualite->tags, true);
            $tags = is_array($tagsArray) ? implode(', ', $tagsArray) : '';
        }

        return [
            $actualite->id,
            $actualite->titre,
            $actualite->slug,
            $actualite->categorie_formatee ?? $actualite->categorie,
            $actualite->auteur_nom,
            $actualite->date_publication->format('d/m/Y'),
            $actualite->vues,
            $actualite->est_publie ? 'Publié' : 'Brouillon',
            $tags,
            $actualite->created_at->format('d/m/Y H:i'),
            $actualite->updated_at->format('d/m/Y H:i')
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