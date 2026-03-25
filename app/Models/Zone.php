<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Sluggable;

class Zone extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'nom',
        'slug',
        'description_courte',
        'description_longue',
        'image_principale',
        'galerie',
        'nombre_arbres',
        'nombre_especes',
        'superficie',
        'latitude',
        'longitude',
        'polygone_coordonnees',
        'adresse_acces',
        'especes_principales',
        'activites',
        'couleur',
        'meta_title',
        'meta_description',
        'ordre',
        'est_active'
    ];

    protected $casts = [
        'galerie' => 'array',
        'polygone_coordonnees' => 'array',
        'especes_principales' => 'array',
        'activites' => 'array',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'est_active' => 'boolean'
    ];

    /**
     * Configuration des slugs automatiques
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nom'
            ]
        ];
    }

    /**
     * Relations
     */
    public function arbres()
    {
        return $this->hasMany(Arbre::class);
    }

    /**
     * Met à jour les statistiques de la zone
     */
    public function updateStatistiques()
    {
        $this->nombre_arbres = $this->arbres()->count();
        $this->nombre_especes = $this->arbres()
            ->join('especes', 'arbres.espece_id', '=', 'especes.id')
            ->distinct('especes.id')
            ->count('especes.id');
        $this->saveQuietly();
    }

    /**
     * Récupère les espèces uniques de la zone
     */
    public function getEspecesUniquesAttribute()
    {
        return $this->arbres()
            ->with('espece')
            ->get()
            ->pluck('espece')
            ->unique('id')
            ->values();
    }

    /**
     * Récupère les coordonnées sous forme de tableau
     */
    public function getCoordonneesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }
        return null;
    }

    /**
     * Obtenir l'URL complète de l'image principale de la zone
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_principale) {
            // Si c'est déjà une URL complète (commence par http)
            if (str_starts_with($this->image_principale, 'http')) {
                return $this->image_principale;
            }
            // Si c'est un chemin local
            return asset('storage/' . $this->image_principale);
        }
        return asset('images/zone-default.jpg');
    }

    /**
     * Obtenir les URLs complètes des images de la galerie
     */
    public function getGalerieUrlsAttribute()
    {
        $urls = [];
        if ($this->galerie) {
            $galerie = is_array($this->galerie) ? $this->galerie : 
                      (is_string($this->galerie) ? json_decode($this->galerie, true) : []);
            
            if (is_array($galerie)) {
                foreach ($galerie as $image) {
                    if (str_starts_with($image, 'http')) {
                        $urls[] = $image;
                    } else {
                        $urls[] = asset('storage/' . $image);
                    }
                }
            }
        }
        return $urls;
    }

    /**
     * Scope pour les zones actives
     */
    public function scopeActifs($query)
    {
        return $query->where('est_active', true);
    }

    /**
     * Scope pour l'ordre d'affichage
     */
    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }
}