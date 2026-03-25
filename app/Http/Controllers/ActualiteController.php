<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actualite;

class ActualiteController extends Controller
{
    /**
     * Afficher la liste des actualités
     */
    public function index(Request $request)
    {
        $query = Actualite::where('est_publie', true);
        
        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('categorie', $request->categorie);
        }
        
        // Recherche par titre
        if ($request->has('search') && $request->search != '') {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }
        
        // Tri par date
        if ($request->has('date') && $request->date == 'ancien') {
            $query->orderBy('date_publication', 'asc');
        } else {
            $query->orderBy('date_publication', 'desc');
        }
        
        $actualites = $query->paginate(6);
        
        return view('actualites.index', compact('actualites'));
    }

    /**
     * Afficher le détail d'une actualité
     */
    public function show($slug)
    {
        $actualite = Actualite::where('slug', $slug)
                              ->where('est_publie', true)
                              ->firstOrFail();
        
        // Incrémenter le nombre de vues
        $actualite->increment('vues');
        
        // Actualités similaires
        $similaires = Actualite::where('id', '!=', $actualite->id)
                               ->where('categorie', $actualite->categorie)
                               ->where('est_publie', true)
                               ->orderBy('date_publication', 'desc')
                               ->limit(3)
                               ->get();
        
        return view('actualites.show', compact('actualite', 'similaires'));
    }
}