<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arbre;
use App\Models\Zone;
use App\Models\Espece;

class ArbreController extends Controller
{
    /**
     * Afficher la liste des arbres
     */
    public function index(Request $request)
    {
        $query = Arbre::with(['zone', 'espece']);
        
        // Filtre par zone
        if ($request->has('zone') && $request->zone != '') {
            $query->where('zone_id', $request->zone);
        }
        
        // Filtre par espèce
        if ($request->has('espece') && $request->espece != '') {
            $query->where('espece_id', $request->espece);
        }
        
        // Recherche par nom
        if ($request->has('search') && $request->search != '') {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }
        
        $arbres = $query->paginate(9);
        $zones = Zone::all();
        $especes = Espece::all();
        
        return view('arbres.index', compact('arbres', 'zones', 'especes'));
    }

    /**
     * Afficher le détail d'un arbre
     */
    public function show($slug)
    {
        $arbre = Arbre::where('slug', $slug)
                      ->with(['zone', 'espece','images'])
                      ->firstOrFail();
        
        // Incrémenter le nombre de vues
        $arbre->increment('vues');
        
        // Arbres similaires (même zone ou même espèce)
        $similaires = Arbre::where('id', '!=', $arbre->id)
                           ->where(function($query) use ($arbre) {
                               $query->where('zone_id', $arbre->zone_id)
                                     ->orWhere('espece_id', $arbre->espece_id);
                           })
                           ->with(['zone', 'espece'])
                           ->limit(3)
                           ->get();
        
        return view('arbres.show', compact('arbre', 'similaires'));
    }

    
}