<?php

namespace App\Exports;

use App\Models\Espece;
use Barryvdh\DomPDF\Facade\Pdf;

class EspecesPdfExport
{
    public function download()
    {
        $especes = Espece::orderBy('nom_local')->get();
        
        $stats = [
            'total' => $especes->count(),
            'locales' => $especes->where('est_locale', true)->count(),
            'introduites' => $especes->where('est_locale', false)->count(),
        ];

        $pdf = Pdf::loadView('admin.exports.especes-pdf', compact('especes', 'stats'));
        
        return $pdf->download('especes-' . date('Y-m-d') . '.pdf');
    }
}