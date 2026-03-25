<?php

namespace App\Exports;

use App\Models\Arbre;
use Barryvdh\DomPDF\Facade\Pdf;

class ArbresPdfExport
{
    protected $filtres;

    public function __construct($filtres = [])
    {
        $this->filtres = $filtres;
    }

    public function download()
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

        $arbres = $query->orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => $arbres->count(),
            'par_zone' => $arbres->groupBy('zone.nom')->map->count(),
            'par_sante' => $arbres->groupBy('etat_sante')->map->count(),
        ];

        $pdf = Pdf::loadView('admin.exports.arbres-pdf', compact('arbres', 'stats'));
        
        return $pdf->download('arbres-' . date('Y-m-d') . '.pdf');
    }
}