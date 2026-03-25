<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Espece extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'nom_scientifique',
        'nom_local',
        'slug',
        'famille',
        'genre',
        'origine',
        'type',
        'hauteur_max',
        'longevite',
        'categorie',
        'description_generale',
        'description_botanique',
        'utilisation',
        'importance_culturelle',
        'conseils_plantation',
        'statut_conservation',
        'image_principale',
        'galerie',
        'est_locale',
        'periodes'
    ];

    protected $casts = [
        'conseils_plantation' => 'array',
        'galerie' => 'array',
        'periodes' => 'array',
        'est_locale' => 'boolean'
    ];

    /**
     * Configuration des slugs automatiques
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nom_scientifique'
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
     * Récupère le nombre d'arbres de cette espèce
     */
    public function getNombreArbresAttribute()
    {
        return $this->arbres()->count();
    }


    /**
 * Obtenir l'URL complète de l'image principale
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
    return asset('images/default-espece.jpg');
}

/**
 * Obtenir l'URL complète des images de la galerie
 */
public function getGalerieUrlsAttribute()
{
    $urls = [];
    if ($this->galerie) {
        $galerie = is_string($this->galerie) ? json_decode($this->galerie, true) : $this->galerie;
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
     * Récupère les zones où cette espèce est présente
     */
    public function getZonesAttribute()
    {
        return $this->arbres()
            ->with('zone')
            ->get()
            ->pluck('zone')
            ->unique('id')
            ->values();
    }

    /**
     * Récupère la catégorie formatée
     */
    public function getCategorieFormateeAttribute()
    {
        $categories = [
            'fruitier' => 'Arbre fruitier',
            'ornemental' => 'Arbre ornemental',
            'foret' => 'Arbre de forêt',
            'sacre' => 'Arbre sacré',
            'medicinal' => 'Arbre médicinal'
        ];
        return $categories[$this->categorie] ?? $this->categorie;
    }

    /**
     * Scope pour les espèces locales
     */
    public function scopeLocales($query)
    {
        return $query->where('est_locale', true);
    }

    /**
     * Scope pour les espèces introduites
     */
    public function scopeIntroduites($query)
    {
        return $query->where('est_locale', false);
    }

    /**
     * Scope par catégorie
     */
    public function scopeCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }
}