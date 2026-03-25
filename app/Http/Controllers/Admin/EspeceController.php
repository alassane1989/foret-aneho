<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Espece;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EspecesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class EspeceController extends Controller
{
    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.especes.index')
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login')
                ->with('error', 'Veuillez vous connecter.');
        }

        if (!$user->aPermission($permission)) {
            return redirect()->route($redirectRoute)
                ->with('error', 'Action non autorisée.');
        }

        return null;
    }

    /**
     * Afficher la liste des espèces
     * Permission: especes.voir
     */
    public function index(Request $request)
    {
        if ($response = $this->authorizePermission('especes.voir')) {
            return $response;
        }

        $query = Espece::query();

        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par origine
        if ($request->has('origine') && $request->origine != '') {
            $estLocale = $request->origine == 'locale';
            $query->where('est_locale', $estLocale);
        }

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nom_scientifique', 'like', '%' . $request->search . '%')
                  ->orWhere('nom_local', 'like', '%' . $request->search . '%');
            });
        }

        $especes = $query->orderBy('nom_local')->paginate(15);
        
        // Statistiques pour les filtres
        $stats = [
            'total' => Espece::count(),
            'locales' => Espece::where('est_locale', true)->count(),
            'introduites' => Espece::where('est_locale', false)->count(),
            'fruitier' => Espece::where('categorie', 'fruitier')->count(),
            'ornemental' => Espece::where('categorie', 'ornemental')->count(),
            'foret' => Espece::where('categorie', 'foret')->count(),
            'sacre' => Espece::where('categorie', 'sacre')->count(),
            'medicinal' => Espece::where('categorie', 'medicinal')->count(),
        ];

        return view('admin.especes.index', compact('especes', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     * Permission: especes.creer
     */
    public function create()
    {
        if ($response = $this->authorizePermission('especes.creer')) {
            return $response;
        }

        return view('admin.especes.create');
    }

    /**
     * Enregistrer une nouvelle espèce
     * Permission: especes.creer
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizePermission('especes.creer')) {
            return $response;
        }

        $validated = $request->validate([
            'nom_scientifique' => 'required|string|max:255|unique:especes',
            'nom_local' => 'required|string|max:255',
            'famille' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'origine' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'hauteur_max' => 'nullable|string|max:100',
            'longevite' => 'nullable|string|max:100',
            'categorie' => 'required|in:fruitier,ornemental,foret,sacre,medicinal',
            'description_generale' => 'required|string',
            'description_botanique' => 'nullable|string',
            'utilisation' => 'nullable|string',
            'importance_culturelle' => 'nullable|string',
            'statut_conservation' => 'nullable|string|max:255',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'est_locale' => 'boolean'
        ]);

        // Gérer l'upload de l'image principale
        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('especes', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer les conseils de plantation (JSON)
        if ($request->has('conseils_plantation')) {
            $conseils = [];
            foreach ($request->conseils_plantation as $key => $value) {
                if (!empty($value)) {
                    $conseils[$key] = $value;
                }
            }
            $validated['conseils_plantation'] = json_encode($conseils);
        }

        // Gérer les périodes (JSON)
        if ($request->has('periodes')) {
            $periodes = [];
            foreach ($request->periodes as $key => $value) {
                if (!empty($value)) {
                    $periodes[$key] = $value;
                }
            }
            $validated['periodes'] = json_encode($periodes);
        }

        // Gérer la galerie (JSON)
        if ($request->hasFile('galerie')) {
            $galeriePaths = [];
            foreach ($request->file('galerie') as $image) {
                $path = $image->store('especes/galerie', 'public');
                $galeriePaths[] = $path;
            }
            $validated['galerie'] = json_encode($galeriePaths);
        }

        // Est locale par défaut à true si non fourni
        $validated['est_locale'] = $request->has('est_locale');

        Espece::create($validated);

        return redirect()->route('admin.especes.index')
            ->with('success', 'Espèce créée avec succès.');
    }

    /**
     * Afficher le détail d'une espèce
     * Permission: especes.voir
     */
    public function show($id)
    {
        if ($response = $this->authorizePermission('especes.voir')) {
            return $response;
        }

        $espece = Espece::with('arbres')->findOrFail($id);
        
        return view('admin.especes.show', compact('espece'));
    }

    /**
     * Afficher le formulaire d'édition
     * Permission: especes.modifier
     */
    public function edit($id)
    {
        if ($response = $this->authorizePermission('especes.modifier')) {
            return $response;
        }

        $espece = Espece::findOrFail($id);
        
        // Décoder les JSON pour le formulaire
        $espece->conseils_plantation = is_string($espece->conseils_plantation) 
            ? json_decode($espece->conseils_plantation, true) 
            : $espece->conseils_plantation;
            
        $espece->periodes = is_string($espece->periodes) 
            ? json_decode($espece->periodes, true) 
            : $espece->periodes;
            
        $espece->galerie = is_string($espece->galerie) 
            ? json_decode($espece->galerie, true) 
            : $espece->galerie;
        
        return view('admin.especes.edit', compact('espece'));
    }

    /**
     * Mettre à jour une espèce
     * Permission: especes.modifier
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizePermission('especes.modifier')) {
            return $response;
        }

        $espece = Espece::findOrFail($id);

        $validated = $request->validate([
            'nom_scientifique' => 'required|string|max:255|unique:especes,nom_scientifique,' . $id,
            'nom_local' => 'required|string|max:255',
            'famille' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'origine' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'hauteur_max' => 'nullable|string|max:100',
            'longevite' => 'nullable|string|max:100',
            'categorie' => 'required|in:fruitier,ornemental,foret,sacre,medicinal',
            'description_generale' => 'required|string',
            'description_botanique' => 'nullable|string',
            'utilisation' => 'nullable|string',
            'importance_culturelle' => 'nullable|string',
            'statut_conservation' => 'nullable|string|max:255',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'est_locale' => 'boolean'
        ]);

        // Gérer l'upload de l'image principale
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($espece->image_principale) {
                Storage::disk('public')->delete($espece->image_principale);
            }
            $path = $request->file('image_principale')->store('especes', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer les conseils de plantation (JSON)
        if ($request->has('conseils_plantation')) {
            $conseils = [];
            foreach ($request->conseils_plantation as $key => $value) {
                if (!empty($value)) {
                    $conseils[$key] = $value;
                }
            }
            $validated['conseils_plantation'] = json_encode($conseils);
        }

        // Gérer les périodes (JSON)
        if ($request->has('periodes')) {
            $periodes = [];
            foreach ($request->periodes as $key => $value) {
                if (!empty($value)) {
                    $periodes[$key] = $value;
                }
            }
            $validated['periodes'] = json_encode($periodes);
        }

        // Gérer la galerie (JSON)
        if ($request->hasFile('galerie')) {
            // Supprimer les anciennes images
            if ($espece->galerie) {
                $oldGalerie = is_string($espece->galerie) ? json_decode($espece->galerie, true) : $espece->galerie;
                if (is_array($oldGalerie)) {
                    foreach ($oldGalerie as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $galeriePaths = [];
            foreach ($request->file('galerie') as $image) {
                $path = $image->store('especes/galerie', 'public');
                $galeriePaths[] = $path;
            }
            $validated['galerie'] = json_encode($galeriePaths);
        }

        $validated['est_locale'] = $request->has('est_locale');

        $espece->update($validated);

        return redirect()->route('admin.especes.index')
            ->with('success', 'Espèce mise à jour avec succès.');
    }

    /**
     * Supprimer une espèce
     * Permission: especes.supprimer
     */
    public function destroy($id)
    {
        if ($response = $this->authorizePermission('especes.supprimer')) {
            return $response;
        }

        $espece = Espece::findOrFail($id);

        // Vérifier si l'espèce est utilisée par des arbres
        if ($espece->arbres()->count() > 0) {
            return redirect()->route('admin.especes.index')
                ->with('error', 'Impossible de supprimer une espèce qui est utilisée par des arbres.');
        }

        // Supprimer l'image principale
        if ($espece->image_principale) {
            Storage::disk('public')->delete($espece->image_principale);
        }

        // Supprimer les images de la galerie
        if ($espece->galerie) {
            $galerie = is_string($espece->galerie) ? json_decode($espece->galerie, true) : $espece->galerie;
            if (is_array($galerie)) {
                foreach ($galerie as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $espece->delete();

        return redirect()->route('admin.especes.index')
            ->with('success', 'Espèce supprimée avec succès.');
    }

    /**
     * Exporter en Excel
     * Permission: especes.exporter
     */
    public function exportExcel()
    {
        if ($response = $this->authorizePermission('especes.exporter')) {
            return $response;
        }

        return Excel::download(new \App\Exports\EspecesExport(), 'especes-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Exporter en PDF
     * Permission: especes.exporter
     */
    public function exportPdf()
    {
        if ($response = $this->authorizePermission('especes.exporter')) {
            return $response;
        }

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