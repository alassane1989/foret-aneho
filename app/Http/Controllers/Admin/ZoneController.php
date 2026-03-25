<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ZonesExport;
use App\Exports\ZonesPdfExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.zones.index')
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
     * Afficher la liste des zones
     * Permission: zones.voir
     */
    public function index()
    {
        if ($response = $this->authorizePermission('zones.voir')) {
            return $response;
        }

        $zones = Zone::orderBy('ordre')->orderBy('nom')->get();
        
        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Afficher le formulaire de création
     * Permission: zones.creer
     */
    public function create()
    {
        if ($response = $this->authorizePermission('zones.creer')) {
            return $response;
        }

        return view('admin.zones.create');
    }

    /**
     * Enregistrer une nouvelle zone
     * Permission: zones.creer
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizePermission('zones.creer')) {
            return $response;
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:zones',
            'description_courte' => 'required|string',
            'description_longue' => 'nullable|string',
            'superficie' => 'nullable|string|max:50',
            'couleur' => 'required|string|max:20',
            'adresse_acces' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ordre' => 'nullable|integer',
            'est_active' => 'boolean'
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('zones', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer les activités (JSON)
        if ($request->has('activites')) {
            $activites = [];
            foreach ($request->activites as $activite) {
                if (!empty($activite['nom'])) {
                    $activites[] = [
                        'icone' => $activite['icone'] ?? 'tree',
                        'nom' => $activite['nom']
                    ];
                }
            }
            $validated['activites'] = json_encode($activites);
        }

        // Gérer les espèces principales (JSON)
        if ($request->has('especes_principales')) {
            $especes = array_filter($request->especes_principales);
            $validated['especes_principales'] = json_encode($especes);
        }

        // Slug généré automatiquement par le modèle
        Zone::create($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone créée avec succès.');
    }

    /**
     * Afficher le détail d'une zone
     * Permission: zones.voir
     */
    public function show($id)
    {
        if ($response = $this->authorizePermission('zones.voir')) {
            return $response;
        }

        $zone = Zone::with('arbres')->findOrFail($id);
        
        return view('admin.zones.show', compact('zone'));
    }

    /**
     * Afficher le formulaire d'édition
     * Permission: zones.modifier
     */
    public function edit($id)
    {
        if ($response = $this->authorizePermission('zones.modifier')) {
            return $response;
        }

        $zone = Zone::findOrFail($id);
        
        // Décoder les JSON pour le formulaire
        $zone->activites = is_string($zone->activites) ? json_decode($zone->activites, true) : $zone->activites;
        $zone->especes_principales = is_string($zone->especes_principales) ? json_decode($zone->especes_principales, true) : $zone->especes_principales;
        
        return view('admin.zones.edit', compact('zone'));
    }

    /**
     * Mettre à jour une zone
     * Permission: zones.modifier
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizePermission('zones.modifier')) {
            return $response;
        }

        $zone = Zone::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:zones,nom,' . $id,
            'description_courte' => 'required|string',
            'description_longue' => 'nullable|string',
            'superficie' => 'nullable|string|max:50',
            'couleur' => 'required|string|max:20',
            'adresse_acces' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ordre' => 'nullable|integer',
            'est_active' => 'boolean'
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($zone->image_principale) {
                Storage::disk('public')->delete($zone->image_principale);
            }
            $path = $request->file('image_principale')->store('zones', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer les activités (JSON)
        if ($request->has('activites')) {
            $activites = [];
            foreach ($request->activites as $activite) {
                if (!empty($activite['nom'])) {
                    $activites[] = [
                        'icone' => $activite['icone'] ?? 'tree',
                        'nom' => $activite['nom']
                    ];
                }
            }
            $validated['activites'] = json_encode($activites);
        } else {
            $validated['activites'] = null;
        }

        // Gérer les espèces principales (JSON)
        if ($request->has('especes_principales')) {
            $especes = array_filter($request->especes_principales);
            $validated['especes_principales'] = json_encode($especes);
        } else {
            $validated['especes_principales'] = null;
        }

        $zone->update($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone mise à jour avec succès.');
    }

    /**
     * Supprimer une zone
     * Permission: zones.supprimer
     */
    public function destroy($id)
    {
        if ($response = $this->authorizePermission('zones.supprimer')) {
            return $response;
        }

        $zone = Zone::findOrFail($id);

        // Vérifier si la zone contient des arbres
        if ($zone->arbres()->count() > 0) {
            return redirect()->route('admin.zones.index')
                ->with('error', 'Impossible de supprimer une zone qui contient des arbres.');
        }

        // Supprimer l'image
        if ($zone->image_principale) {
            Storage::disk('public')->delete($zone->image_principale);
        }

        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone supprimée avec succès.');
    }

    /**
     * Changer l'ordre des zones
     * Permission: zones.modifier (car on modifie l'ordre)
     */
    public function reorder(Request $request)
    {
        if ($response = $this->authorizePermission('zones.modifier')) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        $request->validate([
            'zones' => 'required|array',
            'zones.*.id' => 'required|exists:zones,id',
            'zones.*.ordre' => 'required|integer'
        ]);

        foreach ($request->zones as $item) {
            Zone::where('id', $item['id'])->update(['ordre' => $item['ordre']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Exporter en Excel
     * Permission: zones.exporter
     */
    public function exportExcel()
    {
        if ($response = $this->authorizePermission('zones.exporter')) {
            return $response;
        }

        return Excel::download(new ZonesExport(), 'zones-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Exporter en PDF
     * Permission: zones.exporter
     */
    public function exportPdf()
    {
        if ($response = $this->authorizePermission('zones.exporter')) {
            return $response;
        }

        return (new ZonesPdfExport())->download();
    }
}