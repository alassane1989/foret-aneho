<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actualite;
use App\Models\User;
use App\Traits\NewsletterTrait; // AJOUTÉ
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActualitesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ActualiteController extends Controller
{
    use NewsletterTrait; // AJOUTÉ

    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.actualites.index')
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
     * Afficher la liste des actualités
     * Permission: actualites.voir
     */
    public function index(Request $request)
    {
        if ($response = $this->authorizePermission('actualites.voir')) {
            return $response;
        }

        $query = Actualite::with('user');

        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie != '') {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par statut
        if ($request->has('statut') && $request->statut != '') {
            $estPublie = $request->statut == 'publie';
            $query->where('est_publie', $estPublie);
        }

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('description_courte', 'like', '%' . $request->search . '%');
            });
        }

        $actualites = $query->orderBy('date_publication', 'desc')->paginate(15);
        
        // Statistiques pour les filtres
        $stats = [
            'total' => Actualite::count(),
            'publiees' => Actualite::where('est_publie', true)->count(),
            'brouillons' => Actualite::where('est_publie', false)->count(),
            'plantation' => Actualite::where('categorie', 'plantation')->count(),
            'education' => Actualite::where('categorie', 'education')->count(),
            'infrastructure' => Actualite::where('categorie', 'infrastructure')->count(),
            'partenariat' => Actualite::where('categorie', 'partenariat')->count(),
            'evenement' => Actualite::where('categorie', 'evenement')->count(),
        ];

        return view('admin.actualites.index', compact('actualites', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     * Permission: actualites.creer
     */
    public function create()
    {
        if ($response = $this->authorizePermission('actualites.creer')) {
            return $response;
        }

        return view('admin.actualites.create');
    }

    /**
     * Enregistrer une nouvelle actualité
     * Permission: actualites.creer
     * MODIFIÉ - Ajout de l'envoi newsletter
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizePermission('actualites.creer')) {
            return $response;
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string|max:500',
            'contenu' => 'required|string',
            'categorie' => 'required|in:plantation,education,infrastructure,partenariat,evenement',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'date_publication' => 'required|date',
            'est_publie' => 'boolean',
            'tags' => 'nullable|string',
            'send_newsletter' => 'nullable|boolean', // AJOUTÉ
        ]);

        // Gérer l'upload de l'image principale
        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('actualites', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer la galerie
        if ($request->hasFile('galerie')) {
            $galeriePaths = [];
            foreach ($request->file('galerie') as $image) {
                $path = $image->store('actualites/galerie', 'public');
                $galeriePaths[] = $path;
            }
            $validated['galerie'] = json_encode($galeriePaths);
        }

        // Gérer les tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = json_encode($tags);
        }

        // Ajouter l'utilisateur connecté
        $validated['user_id'] = Auth::id();
        $validated['auteur_nom'] = Auth::user()->name;
        
        // Slug généré automatiquement par le modèle
        // Est_publie par défaut à false si non fourni
        $validated['est_publie'] = $request->has('est_publie');

        $actualite = Actualite::create($validated);

        // AJOUTÉ - Envoyer la newsletter SI l'actualité est publiée ET que l'option est cochée
        if ($actualite->est_publie && $request->has('send_newsletter') && $request->send_newsletter == '1') {
            $result = $this->sendActualiteToSubscribers($actualite);
            
            if ($result['success']) {
                return redirect()->route('admin.actualites.index')
                    ->with('success', 'Actualité créée et envoyée avec succès! ' . $result['message']);
            } else {
                return redirect()->route('admin.actualites.index')
                    ->with('warning', 'Actualité créée mais l\'envoi a échoué: ' . $result['message']);
            }
        }

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité créée avec succès.');
    }

    /**
     * Afficher le détail d'une actualité
     * Permission: actualites.voir
     */
    public function show($id)
    {
        if ($response = $this->authorizePermission('actualites.voir')) {
            return $response;
        }

        $actualite = Actualite::with('user')->findOrFail($id);
        
        return view('admin.actualites.show', compact('actualite'));
    }

    /**
     * Afficher le formulaire d'édition
     * Permission: actualites.modifier
     */
    public function edit($id)
    {
        if ($response = $this->authorizePermission('actualites.modifier')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);
        
        // Décoder les JSON pour le formulaire
        $actualite->tags = is_string($actualite->tags) 
            ? implode(', ', json_decode($actualite->tags, true) ?? [])
            : '';
            
        $actualite->galerie = is_string($actualite->galerie) 
            ? json_decode($actualite->galerie, true) 
            : $actualite->galerie;
        
        return view('admin.actualites.edit', compact('actualite'));
    }

    /**
     * Mettre à jour une actualité
     * Permission: actualites.modifier
     * MODIFIÉ - Ajout de l'envoi newsletter
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizePermission('actualites.modifier')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string|max:500',
            'contenu' => 'required|string',
            'categorie' => 'required|in:plantation,education,infrastructure,partenariat,evenement',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_publication' => 'required|date',
            'est_publie' => 'boolean',
            'tags' => 'nullable|string',
            'send_newsletter' => 'nullable|boolean', // AJOUTÉ
        ]);

        $oldStatus = $actualite->est_publie; // AJOUTÉ
        $newStatus = $request->has('est_publie'); // AJOUTÉ

        // Gérer l'upload de l'image principale
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($actualite->image_principale) {
                Storage::disk('public')->delete($actualite->image_principale);
            }
            $path = $request->file('image_principale')->store('actualites', 'public');
            $validated['image_principale'] = $path;
        }

        // Gérer la galerie
        if ($request->hasFile('galerie')) {
            // Supprimer les anciennes images
            if ($actualite->galerie) {
                $oldGalerie = is_string($actualite->galerie) ? json_decode($actualite->galerie, true) : $actualite->galerie;
                if (is_array($oldGalerie)) {
                    foreach ($oldGalerie as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $galeriePaths = [];
            foreach ($request->file('galerie') as $image) {
                $path = $image->store('actualites/galerie', 'public');
                $galeriePaths[] = $path;
            }
            $validated['galerie'] = json_encode($galeriePaths);
        }

        // Gérer les tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = json_encode($tags);
        } else {
            $validated['tags'] = null;
        }

        $validated['est_publie'] = $newStatus;

        $actualite->update($validated);

        // AJOUTÉ - Envoyer la newsletter SI l'actualité vient d'être publiée ET que l'option est cochée
        if ($newStatus && !$oldStatus && $request->has('send_newsletter') && $request->send_newsletter == '1') {
            $result = $this->sendActualiteToSubscribers($actualite);
            
            if ($result['success']) {
                return redirect()->route('admin.actualites.index')
                    ->with('success', 'Actualité mise à jour et envoyée avec succès! ' . $result['message']);
            } else {
                return redirect()->route('admin.actualites.index')
                    ->with('warning', 'Actualité mise à jour mais l\'envoi a échoué: ' . $result['message']);
            }
        }

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité mise à jour avec succès.');
    }

    /**
     * Supprimer une actualité
     * Permission: actualites.supprimer
     */
    public function destroy($id)
    {
        if ($response = $this->authorizePermission('actualites.supprimer')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);

        // Supprimer l'image principale
        if ($actualite->image_principale) {
            Storage::disk('public')->delete($actualite->image_principale);
        }

        // Supprimer les images de la galerie
        if ($actualite->galerie) {
            $galerie = is_string($actualite->galerie) ? json_decode($actualite->galerie, true) : $actualite->galerie;
            if (is_array($galerie)) {
                foreach ($galerie as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $actualite->delete();

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité supprimée avec succès.');
    }

    /**
     * Changer le statut (publier/dépublier)
     * Permission: actualites.publier
     * MODIFIÉ - Ajout de l'option d'envoi
     */
    public function toggleStatus($id, Request $request)
    {
        if ($response = $this->authorizePermission('actualites.publier')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);
        $oldStatus = $actualite->est_publie;
        $actualite->est_publie = !$actualite->est_publie;
        $actualite->save();

        $status = $actualite->est_publie ? 'publiée' : 'dépubliée';
        
        $message = "Actualité {$status} avec succès.";
        
        // AJOUTÉ - Envoyer la newsletter si l'actualité vient d'être publiée
        if ($actualite->est_publie && !$oldStatus && $request->has('send_newsletter') && $request->send_newsletter == '1') {
            $result = $this->sendActualiteToSubscribers($actualite);
            
            if ($result['success']) {
                $message .= ' ' . $result['message'];
            } else {
                $message .= ' Mais l\'envoi a échoué: ' . $result['message'];
            }
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Dupliquer une actualité
     * Permission: actualites.creer (car on crée une copie)
     */
    public function duplicate($id)
    {
        if ($response = $this->authorizePermission('actualites.creer')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);
        
        $nouvelle = $actualite->replicate();
        $nouvelle->titre = $actualite->titre . ' (copie)';
        $nouvelle->slug = Str::slug($nouvelle->titre);
        $nouvelle->date_publication = now();
        $nouvelle->est_publie = false;
        $nouvelle->vues = 0;
        $nouvelle->save();

        return redirect()->route('admin.actualites.edit', $nouvelle->id)
            ->with('success', 'Actualité dupliquée avec succès. Vous pouvez maintenant la modifier.');
    }

    /**
     * Exporter en Excel
     * Permission: actualites.exporter
     */
    public function exportExcel()
    {
        if ($response = $this->authorizePermission('actualites.exporter')) {
            return $response;
        }

        return Excel::download(new \App\Exports\ActualitesExport(), 'actualites-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Exporter en PDF
     * Permission: actualites.exporter
     */
    public function exportPdf()
    {
        if ($response = $this->authorizePermission('actualites.exporter')) {
            return $response;
        }

        $actualites = Actualite::orderBy('date_publication', 'desc')->get();
        
        $stats = [
            'total' => $actualites->count(),
            'publiees' => $actualites->where('est_publie', true)->count(),
            'brouillons' => $actualites->where('est_publie', false)->count(),
        ];

        $pdf = Pdf::loadView('admin.exports.actualites-pdf', compact('actualites', 'stats'));
        
        return $pdf->download('actualites-' . date('Y-m-d') . '.pdf');
    }

    /**
     * AJOUTÉ - Envoyer une actualité existante aux abonnés
     * Permission: actualites.modifier
     */
    public function sendToNewsletter($id)
    {
        if ($response = $this->authorizePermission('actualites.modifier')) {
            return $response;
        }

        $actualite = Actualite::findOrFail($id);
        
        if (!$actualite->est_publie) {
            return redirect()->back()
                ->with('error', 'Cette actualité n\'est pas encore publiée. Veuillez d\'abord la publier.');
        }
        
        $result = $this->sendActualiteToSubscribers($actualite);
        
        if ($result['success']) {
            return redirect()->back()
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }
}