<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arbre;
use App\Models\Zone;
use App\Models\Espece;
use App\Models\Actualite;

class HomeController extends Controller
{
    public function index()
    {
        $totalArbres = Arbre::count();
        $totalZones = Zone::count();
        $totalEspeces = Espece::count();
        $totalPlanteurs = Arbre::distinct('planteur_nom')->count('planteur_nom');
        
        $zones = Zone::orderBy('ordre')->get();
        $actualites = Actualite::where('est_publie', true)
                               ->orderBy('date_publication', 'desc')
                               ->take(3)
                               ->get();
        
        return view('home.index', compact(
            'totalArbres', 
            'totalZones', 
            'totalEspeces', 
            'totalPlanteurs',
            'zones',
            'actualites'
        ));
    }
}