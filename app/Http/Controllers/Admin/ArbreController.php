<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arbre;
use App\Models\Zone;
use App\Models\Espece;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\ArbresExport;
use App\Exports\ArbresPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ArbreController extends Controller
{
    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.arbres.index')
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
     * Afficher la liste des arbres
     * Permission: arbres.voir
     */
    public function index(Request $request)
    {
        if ($response = $this->authorizePermission('arbres.voir')) {
            return $response;
        }

        $query = Arbre::with(['zone', 'espece']);

        // Filtre par zone
        if ($request->has('zone') && $request->zone != '') {
            $query->where('zone_id', $request->zone);
        }

        // Filtre par espèce
        if ($request->has('espece') && $request->espece != '') {
            $query->where('espece_id', $request->espece);
        }

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $arbres = $query->orderBy('created_at', 'desc')->paginate(15);
        $zones = Zone::all();
        $especes = Espece::all();

        return view('admin.arbres.index', compact('arbres', 'zones', 'especes'));
    }

    /**
     * Afficher le formulaire de création
     * Permission: arbres.creer
     */
    public function create()
    {
        if ($response = $this->authorizePermission('arbres.creer')) {
            return $response;
        }

        $zones = Zone::all();
        $especes = Espece::all();
        
        return view('admin.arbres.create', compact('zones', 'especes'));
    }

    /**
     * Enregistrer un nouvel arbre
     * Permission: arbres.creer
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizePermission('arbres.creer')) {
            return $response;
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
            'espece_id' => 'required|exists:especes,id',
            'date_plantation' => 'required|date',
            'planteur_nom' => 'required|string|max:255',
            'planteur_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo_arbre' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'etat_sante' => 'required|in:excellent,bon,moyen,surveille',
            'hauteur' => 'nullable|string|max:50',
            'circonference' => 'nullable|string|max:50',
        ]);

        // Gérer l'upload de la photo du planteur
        if ($request->file('planteur_photo') && $request->file('planteur_photo')->isValid()) {
            $path = $request->file('planteur_photo')->store('planteurs', 'public');
            $validated['planteur_photo'] = $path;
        }

        // Gérer l'upload de la photo de l'arbre
        if ($request->hasFile('photo_arbre')) {
            $path = $request->file('photo_arbre')->store('arbres', 'public');
            $validated['photo_arbre'] = $path;
        }

        // Créer l'arbre
        $arbre = Arbre::create($validated);

        return redirect()->route('admin.arbres.index')
            ->with('success', 'Arbre ajouté avec succès.');
    }

    /**
     * Afficher le détail d'un arbre
     * Permission: arbres.voir
     */
    public function show($id)
    {
        if ($response = $this->authorizePermission('arbres.voir')) {
            return $response;
        }

        $arbre = Arbre::with(['zone', 'espece'])->findOrFail($id);
        
        return view('admin.arbres.show', compact('arbre'));
    }

    /**
     * Afficher le formulaire d'édition
     * Permission: arbres.modifier
     */
    public function edit($id)
    {
        if ($response = $this->authorizePermission('arbres.modifier')) {
            return $response;
        }

        $arbre = Arbre::findOrFail($id);
        $zones = Zone::all();
        $especes = Espece::all();
        
        return view('admin.arbres.edit', compact('arbre', 'zones', 'especes'));
    }

    /**
     * Mettre à jour un arbre
     * Permission: arbres.modifier
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizePermission('arbres.modifier')) {
            return $response;
        }

        $arbre = Arbre::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
            'espece_id' => 'required|exists:especes,id',
            'date_plantation' => 'required|date',
            'planteur_nom' => 'required|string|max:255',
            'planteur_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo_arbre' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'etat_sante' => 'required|in:excellent,bon,moyen,surveille',
            'hauteur' => 'nullable|string|max:50',
            'circonference' => 'nullable|string|max:50',
        ]);

        // Gérer l'upload de la photo du planteur
        if ($request->hasFile('planteur_photo')) {
            // Supprimer l'ancienne photo
            if ($arbre->planteur_photo) {
                Storage::disk('public')->delete($arbre->planteur_photo);
            }
            $path = $request->file('planteur_photo')->store('planteurs', 'public');
            $validated['planteur_photo'] = $path;
        }

        // Gérer l'upload de la photo de l'arbre
        if ($request->hasFile('photo_arbre')) {
            // Supprimer l'ancienne photo
            if ($arbre->photo_arbre) {
                Storage::disk('public')->delete($arbre->photo_arbre);
            }
            $path = $request->file('photo_arbre')->store('arbres', 'public');
            $validated['photo_arbre'] = $path;
        }

        $arbre->update($validated);

        return redirect()->route('admin.arbres.index')
            ->with('success', 'Arbre mis à jour avec succès.');
    }

    /**
     * Supprimer un arbre
     * Permission: arbres.supprimer
     */
    public function destroy($id)
    {
        if ($response = $this->authorizePermission('arbres.supprimer')) {
            return $response;
        }

        $arbre = Arbre::findOrFail($id);

        // Supprimer les photos
        if ($arbre->planteur_photo) {
            Storage::disk('public')->delete($arbre->planteur_photo);
        }
        if ($arbre->photo_arbre) {
            Storage::disk('public')->delete($arbre->photo_arbre);
        }
        if ($arbre->qr_code) {
            Storage::disk('public')->delete('qrcodes/' . $arbre->qr_code . '.svg');
        }

        $arbre->delete();

        return redirect()->route('admin.arbres.index')
            ->with('success', 'Arbre supprimé avec succès.');
    }

    /**
     * Exporter en Excel
     * Permission: arbres.exporter
     */
    public function exportExcel(Request $request)
    {
        if ($response = $this->authorizePermission('arbres.exporter')) {
            return $response;
        }

        $filtres = [
            'zone' => $request->zone,
            'espece' => $request->espece,
            'search' => $request->search
        ];

        return Excel::download(new ArbresExport($filtres), 'arbres-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Exporter en PDF
     * Permission: arbres.exporter
     */
    public function exportPdf(Request $request)
    {
        if ($response = $this->authorizePermission('arbres.exporter')) {
            return $response;
        }

        $filtres = [
            'zone' => $request->zone,
            'espece' => $request->espece,
            'search' => $request->search
        ];

        return (new ArbresPdfExport($filtres))->download();
    }
}