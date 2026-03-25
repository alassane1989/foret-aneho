<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Espece;

class EspeceController extends Controller
{
    /**
     * Afficher la liste des espèces (frontend public)
     */
    public function index(Request $request)
    {
        $query = Espece::query();
        
        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('categorie', $request->categorie);
        }
        
        // Filtre par origine (locale/introduite)
        if ($request->has('origine') && $request->origine != '') {
            $estLocale = $request->origine == 'locale';
            $query->where('est_locale', $estLocale);
        }
        
        // Recherche par nom
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nom_scientifique', 'like', '%' . $request->search . '%')
                  ->orWhere('nom_local', 'like', '%' . $request->search . '%');
            });
        }
        
        // Tri par défaut
        $query->orderBy('nom_local');
        
        // Pagination avec conservation des paramètres de filtre
        $especes = $query->paginate(12)->withQueryString();
        
        return view('especes.index', compact('especes'));
    }

    /**
     * Afficher le détail d'une espèce
     */
    public function show($slug)
    {
        $espece = Espece::where('slug', $slug)->firstOrFail();
        $arbres = $espece->arbres()->with('zone')->get();
        
        return view('especes.show', compact('espece', 'arbres'));
    }
}