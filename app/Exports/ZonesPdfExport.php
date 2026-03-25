<?php

namespace App\Exports;

use App\Models\Zone;
use Barryvdh\DomPDF\Facade\Pdf;

class ZonesPdfExport
{
    public function download()
    {
        $zones = Zone::orderBy('ordre')->orderBy('nom')->get();
        
        $stats = [
            'total' => $zones->count(),
            'total_arbres' => $zones->sum('nombre_arbres'),
            'total_especes' => $zones->sum('nombre_especes'),
        ];

        $pdf = Pdf::loadView('admin.exports.zones-pdf', compact('zones', 'stats'));
        
        return $pdf->download('zones-' . date('Y-m-d') . '.pdf');
    }
}