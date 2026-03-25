<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arbre;
use App\Models\Zone;
use App\Models\Espece;
use App\Models\Actualite;
use App\Models\Contact;
use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Vérifier si les tables existent avant de faire les requêtes
        $stats = [
            'arbres' => $this->safeCount(Arbre::class),
            'zones' => $this->safeCount(Zone::class),
            'especes' => $this->safeCount(Espece::class),
            'actualites' => $this->safeCount(Actualite::class),
            'messages_non_lus' => $this->safeCount(Contact::class, 'lu', false),
            'newsletters' => $this->safeCount(Newsletter::class, 'est_actif', true),
        ];

        // Répartition des arbres par zone (avec gestion d'erreur)
        try {
            $arbresParZone = Zone::withCount('arbres')
                ->orderBy('arbres_count', 'desc')
                ->get()
                ->map(function($zone) {
                    return [
                        'nom' => $zone->nom,
                        'count' => $zone->arbres_count,
                        'couleur' => $zone->couleur ?? '#4CAF50'
                    ];
                });
        } catch (\Exception $e) {
            $arbresParZone = collect([]);
        }

        // Répartition des arbres par espèce (top 5) avec gestion d'erreur
        try {
            $arbresParEspece = Espece::withCount('arbres')
                ->orderBy('arbres_count', 'desc')
                ->take(5)
                ->get()
                ->map(function($espece) {
                    return [
                        'nom' => $espece->nom_local ?? $espece->nom,
                        'count' => $espece->arbres_count,
                    ];
                });
        } catch (\Exception $e) {
            $arbresParEspece = collect([]);
        }

        // Plantations par mois (12 derniers mois) avec gestion d'erreur
        $plantationsParMois = [];
        try {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $count = Arbre::whereYear('date_plantation', $date->year)
                    ->whereMonth('date_plantation', $date->month)
                    ->count();
                
                $plantationsParMois[] = [
                    'mois' => $date->format('M Y'),
                    'count' => $count
                ];
            }
        } catch (\Exception $e) {
            $plantationsParMois = [];
        }

        // Derniers arbres ajoutés avec gestion d'erreur
        try {
            $derniersArbres = Arbre::with(['zone', 'espece'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $derniersArbres = collect([]);
        }

        // Derniers messages avec gestion d'erreur
        try {
            $derniersMessages = Contact::orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $derniersMessages = collect([]);
        }

        // Dernières actualités avec gestion d'erreur
        try {
            $dernieresActualites = Actualite::orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $dernieresActualites = collect([]);
        }

        // Statistiques de santé des arbres avec gestion d'erreur
        $santeArbres = [
            'excellent' => 0,
            'bon' => 0,
            'moyen' => 0,
            'surveille' => 0,
        ];
        
        try {
            $santeArbres = [
                'excellent' => Arbre::where('etat_sante', 'excellent')->count(),
                'bon' => Arbre::where('etat_sante', 'bon')->count(),
                'moyen' => Arbre::where('etat_sante', 'moyen')->count(),
                'surveille' => Arbre::where('etat_sante', 'surveille')->count(),
            ];
        } catch (\Exception $e) {
            // Garder les valeurs par défaut
        }

        // Total des vues des actualités avec gestion d'erreur
        try {
            $actualitesVues = Actualite::sum('vues');
        } catch (\Exception $e) {
            $actualitesVues = 0;
        }
           

        return view('admin.dashboard.index', compact(
            'stats',
            'arbresParZone',
            'arbresParEspece',
            'plantationsParMois',
            'derniersArbres',
            'derniersMessages',
            'dernieresActualites',
            'santeArbres',
            'actualitesVues'
        ));
      

    }

    /**
     * Compte le nombre d'enregistrements dans un modèle avec gestion d'erreur
     */
    private function safeCount($modelClass, $whereField = null, $whereValue = null)
    {
        try {
            $query = $modelClass::query();
            
            if ($whereField && $whereValue !== null) {
                $query->where($whereField, $whereValue);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}