<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arbre;
use App\Models\Espece;
use App\Models\Actualite;
use App\Models\Zone;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q'));
        
        if (empty($query)) {
            return redirect()->back()->with('info', 'Veuillez saisir un terme de recherche.');
        }
        
        // Recherche dans les arbres
        $arbres = Arbre::where('nom', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('planteur_nom', 'like', "%{$query}%")
            ->get();
            
        // Recherche dans les espèces
        $especes = Espece::where('nom_local', 'like', "%{$query}%")
            ->orWhere('nom_scientifique', 'like', "%{$query}%")
            ->orWhere('description_generale', 'like', "%{$query}%")
            ->get();
            
        // Recherche dans les actualités
        $actualites = Actualite::where('titre', 'like', "%{$query}%")
            ->orWhere('contenu', 'like', "%{$query}%")
            ->orWhere('description_courte', 'like', "%{$query}%")
            ->where('est_publie', true)
            ->get();
            
        // Recherche dans les zones - CORRECTION : retirer la colonne 'description'
        $zones = Zone::where('nom', 'like', "%{$query}%")
            ->get();  // Supprimé le orWhere('description', 'like', ...)
        
        $totalResults = $arbres->count() + $especes->count() + $actualites->count() + $zones->count();
        
        return view('search.results', compact('query', 'arbres', 'especes', 'actualites', 'zones', 'totalResults'));
    }
}