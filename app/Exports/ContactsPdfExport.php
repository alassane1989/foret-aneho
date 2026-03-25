<?php

namespace App\Exports;

use App\Models\Contact;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactsPdfExport
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function generate()
    {
        $query = Contact::query();

        // Appliquer les mêmes filtres que dans l'index
        if ($this->request->has('statut') && $this->request->statut != '') {
            $estLu = $this->request->statut == 'lu';
            $query->where('lu', $estLu);
        }

        if ($this->request->has('sujet') && $this->request->sujet != '') {
            $query->where('sujet', $this->request->sujet);
        }

        if ($this->request->has('search') && $this->request->search != '') {
            $searchTerm = $this->request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        // Tri par date
        if ($this->request->has('tri') && $this->request->tri == 'ancien') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $contacts = $query->get();

        // Compter les statistiques
        $stats = [
            'total' => $contacts->count(),
            'non_lus' => $contacts->where('lu', false)->count(),
            'lus' => $contacts->where('lu', true)->count(),
            'repondu' => $contacts->whereNotNull('reponse')->count()
        ];

        // Statistiques par sujet
        $statsParSujet = [];
        foreach (Contact::SUJETS as $key => $label) {
            $statsParSujet[$key] = $contacts->where('sujet', $key)->count();
        }

        // Récupérer l'utilisateur connecté de manière sécurisée
        $utilisateur = Auth::check() ? Auth::user() : null;
        
        // Ou si vous voulez des informations par défaut
        $nomUtilisateur = $utilisateur ? $utilisateur->name : 'Système';
        $emailUtilisateur = $utilisateur ? $utilisateur->email : 'admin@observatoire.fr';

        $data = [
            'contacts' => $contacts,
            'stats' => $stats,
            'statsParSujet' => $statsParSujet,
            'date_export' => now()->format('d/m/Y H:i:s'),
            'filtres' => $this->getFiltresLabels(),
            'utilisateur' => $utilisateur,
            'nom_utilisateur' => $nomUtilisateur, // Ajout d'une variable sécurisée
            'email_utilisateur' => $emailUtilisateur, // Ajout d'une variable sécurisée
            'sujets' => Contact::SUJETS
        ];

        $pdf = Pdf::loadView('admin.exports.contacts-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        // Configuration supplémentaire pour DomPDF
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'fontHeightRatio' => 0.9,
            'enable_php' => true,
            'enable_javascript' => false,
            'enable_remote' => true
        ]);

        return $pdf;
    }

    protected function getFiltresLabels()
    {
        $filtres = [];

        if ($this->request->has('statut') && $this->request->statut != '') {
            $filtres[] = 'Statut : ' . ($this->request->statut == 'lu' ? 'Lu' : 'Non lu');
        }

        if ($this->request->has('sujet') && $this->request->sujet != '') {
            $sujet = $this->request->sujet;
            $filtres[] = 'Sujet : ' . (Contact::SUJETS[$sujet] ?? $sujet);
        }

        if ($this->request->has('search') && $this->request->search != '') {
            $filtres[] = 'Recherche : "' . $this->request->search . '"';
        }

        if ($this->request->has('tri') && $this->request->tri != '') {
            $filtres[] = 'Tri : ' . ($this->request->tri == 'ancien' ? 'Plus ancien' : 'Plus récent');
        }

        return $filtres;
    }

    /**
     * Obtenir le libellé du sujet
     */
    protected function getSujetLabel($sujet): string
    {
        return Contact::SUJETS[$sujet] ?? $sujet;
    }
}