<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NewsletterController extends Controller
{
    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.newsletters.index')
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
     * Afficher la liste des abonnés
     * Permission: newsletter.voir
     */
    public function index(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.voir')) {
            return $response;
        }

        $query = Newsletter::query();

        // Filtre par statut (actif/inactif)
        if ($request->has('statut') && $request->statut != '') {
            $estActif = $request->statut == 'actif';
            $query->where('est_actif', $estActif);
        }

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        if ($request->has('tri') && $request->tri == 'ancien') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $abonnes = $query->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Newsletter::count(),
            'actifs' => Newsletter::where('est_actif', true)->count(),
            'inactifs' => Newsletter::where('est_actif', false)->count(),
            'ce_mois' => Newsletter::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count(),
            'ce_mois_actifs' => Newsletter::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->where('est_actif', true)
                                        ->count(),
        ];

        return view('admin.newsletters.index', compact('abonnes', 'stats'));
    }

    /**
     * Afficher le détail d'un abonné
     * Permission: newsletter.voir
     */
    public function show($id)
    {
        if ($response = $this->authorizePermission('newsletter.voir')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);
        
        return view('admin.newsletters.show', compact('abonne'));
    }

    /**
     * Ajouter un abonné manuellement
     * Permission: newsletter.creer
     */
    public function create()
    {
        if ($response = $this->authorizePermission('newsletter.creer')) {
            return $response;
        }

        return view('admin.newsletters.create');
    }

    /**
     * Enregistrer un nouvel abonné
     * Permission: newsletter.creer
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.creer')) {
            return $response;
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'est_actif' => true,
            'date_inscription' => now()
        ]);

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Abonné ajouté avec succès.');
    }

    /**
     * Modifier un abonné
     * Permission: newsletter.modifier
     */
    public function edit($id)
    {
        if ($response = $this->authorizePermission('newsletter.modifier')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);
        
        return view('admin.newsletters.edit', compact('abonne'));
    }

    /**
     * Mettre à jour un abonné
     * Permission: newsletter.modifier
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->authorizePermission('newsletter.modifier')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletters,email,' . $id,
        ]);

        $abonne->update([
            'nom' => $request->nom,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Abonné mis à jour avec succès.');
    }

    /**
     * Désinscrire un abonné
     * Permission: newsletter.modifier
     */
    public function unsubscribe($id)
    {
        if ($response = $this->authorizePermission('newsletter.modifier')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);
        $abonne->desinscrire();

        return redirect()->back()
            ->with('success', 'Abonné désinscrit avec succès.');
    }

    /**
     * Réactiver un abonné
     * Permission: newsletter.modifier
     */
    public function reactivate($id)
    {
        if ($response = $this->authorizePermission('newsletter.modifier')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);
        $abonne->reactiver();

        return redirect()->back()
            ->with('success', 'Abonné réactivé avec succès.');
    }

    /**
     * Supprimer un abonné
     * Permission: newsletter.supprimer
     */
    public function destroy($id)
    {
        if ($response = $this->authorizePermission('newsletter.supprimer')) {
            return $response;
        }

        $abonne = Newsletter::findOrFail($id);
        $abonne->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Abonné supprimé avec succès.');
    }

    /**
     * Supprimer plusieurs abonnés
     * Permission: newsletter.supprimer
     */
    public function massDestroy(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.supprimer')) {
            return $response;
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:newsletters,id'
        ]);

        Newsletter::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', count($request->ids) . ' abonné(s) supprimé(s) avec succès.');
    }

    /**
     * Désinscrire plusieurs abonnés
     * Permission: newsletter.modifier
     */
    public function massUnsubscribe(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.modifier')) {
            return $response;
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:newsletters,id'
        ]);

        $abonnes = Newsletter::whereIn('id', $request->ids)->get();
        foreach ($abonnes as $abonne) {
            $abonne->desinscrire();
        }

        return redirect()->route('admin.newsletters.index')
            ->with('success', count($request->ids) . ' abonné(s) désinscrit(s) avec succès.');
    }

    /**
     * Exporter en Excel
     * Permission: newsletter.exporter
     */
    public function exportExcel(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.exporter')) {
            return $response;
        }

        // À implémenter avec Laravel Excel
        return redirect()->back()->with('info', 'Export Excel à venir');
    }

    /**
     * Exporter en PDF
     * Permission: newsletter.exporter
     */
    public function exportPdf(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.exporter')) {
            return $response;
        }

        // À implémenter avec DomPDF
        return redirect()->back()->with('info', 'Export PDF à venir');
    }

    /**
     * Exporter en CSV
     * Permission: newsletter.exporter
     */
    public function exportCsv()
    {
        if ($response = $this->authorizePermission('newsletter.exporter')) {
            return $response;
        }

        $abonnes = Newsletter::where('est_actif', true)->get();
        
        $filename = "newsletter_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // En-têtes
        fputcsv($handle, ['Nom', 'Email', 'Date inscription', 'Statut']);
        
        // Données
        foreach ($abonnes as $abonne) {
            fputcsv($handle, [
                $abonne->nom,
                $abonne->email,
                $abonne->date_inscription->format('d/m/Y'),
                $abonne->est_actif ? 'Actif' : 'Inactif'
            ]);
        }
        
        fclose($handle);
        exit;
    }

    /**
     * Envoyer une newsletter (simulation)
     * Permission: newsletter.envoyer
     */
    public function sendNewsletter(Request $request)
    {
        if ($response = $this->authorizePermission('newsletter.envoyer')) {
            return $response;
        }

        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        // TODO: Implémenter l'envoi d'email à tous les abonnés actifs
        // Mail::to($abonnes)->send(new NewsletterEmail($request->sujet, $request->contenu));

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Newsletter envoyée avec succès à tous les abonnés actifs.');
    }
}