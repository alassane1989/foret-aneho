<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'lu',
        'date_traitement',
        'reponse',
        'date_reponse'
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lu' => 'boolean',
        'date_traitement' => 'datetime',
        'date_reponse' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Liste des sujets possibles
     */
    const SUJETS = [
        'info' => 'Demande d\'information',
        'visite' => 'Demande de visite',
        'participation' => 'Participation à un événement',
        'projet' => 'Proposition de projet',
        'partenariat' => 'Proposition de partenariat',
        'autre' => 'Autre'
    ];

    /**
     * Obtenir le libellé du sujet
     */
    public function getSujetLabelAttribute(): string
    {
        return self::SUJETS[$this->sujet] ?? 'Sujet inconnu';
    }

    /**
     * Obtenir le statut en texte
     */
    public function getStatutLabelAttribute(): string
    {
        return $this->lu ? 'Lu' : 'Non lu';
    }

    /**
     * Obtenir la couleur du statut pour les badges
     */
    public function getStatutColorAttribute(): string
    {
        return $this->lu ? 'success' : 'warning';
    }

    /**
     * Vérifier si le message a une réponse
     */
    public function getAReponseAttribute(): bool
    {
        return !is_null($this->reponse);
    }

    /**
     * Marquer le message comme lu
     */
    public function marquerCommeLu(): self
    {
        if (!$this->lu) {
            $this->update([
                'lu' => true,
                'date_traitement' => now()
            ]);
        }
        
        return $this;
    }

    /**
     * Marquer le message comme non lu
     */
    public function marquerCommeNonLu(): self
    {
        if ($this->lu) {
            $this->update([
                'lu' => false,
                'date_traitement' => null
            ]);
        }
        
        return $this;
    }

    /**
     * Ajouter une réponse au message
     */
    public function ajouterReponse(string $reponse): self
    {
        $this->update([
            'reponse' => $reponse,
            'date_reponse' => now(),
            'lu' => true,
            'date_traitement' => $this->date_traitement ?? now()
        ]);
        
        return $this;
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeNonLu($query)
    {
        return $query->where('lu', false);
    }

    /**
     * Scope pour les messages lus
     */
    public function scopeLu($query)
    {
        return $query->where('lu', true);
    }

    /**
     * Scope pour les messages avec réponse
     */
    public function scopeAvecReponse($query)
    {
        return $query->whereNotNull('reponse');
    }

    /**
     * Scope pour les messages sans réponse
     */
    public function scopeSansReponse($query)
    {
        return $query->whereNull('reponse');
    }

    /**
     * Scope pour filtrer par sujet
     */
    public function scopeParSujet($query, string $sujet)
    {
        return $query->where('sujet', $sujet);
    }

    /**
     * Scope pour la recherche
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'like', "%{$terme}%")
              ->orWhere('email', 'like', "%{$terme}%")
              ->orWhere('message', 'like', "%{$terme}%")
              ->orWhere('reponse', 'like', "%{$terme}%");
        });
    }
}