<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Actualite extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'titre',
        'slug',
        'description_courte',
        'contenu',
        'categorie',
        'image_principale',
        'galerie',
        'user_id',
        'auteur_nom',
        'tags',
        'vues',
        'date_publication',
        'est_publie',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'galerie' => 'array',
        'tags' => 'array',
        'date_publication' => 'date',
        'est_publie' => 'boolean'
    ];

    /**
     * Configuration des slugs automatiques
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'titre'
            ]
        ];
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Incrémente le nombre de vues
     */
    public function incrementerVues()
    {
        $this->increment('vues');
    }

    /**
     * Récupère la catégorie formatée
     */
    public function getCategorieFormateeAttribute()
    {
        $categories = [
            'plantation' => 'Plantation',
            'education' => 'Éducation',
            'infrastructure' => 'Infrastructure',
            'partenariat' => 'Partenariat',
            'evenement' => 'Événement'
        ];
        return $categories[$this->categorie] ?? $this->categorie;
    }

    /**
     * Récupère la date formatée
     */
    public function getDateFormateeAttribute()
    {
        return $this->date_publication->format('d/m/Y');
    }

    /**
     * Scope pour les actualités publiées
     */
    public function scopePublies($query)
    {
        return $query->where('est_publie', true)
                     ->where('date_publication', '<=', now());
    }

    /**
     * Scope pour les actualités récentes
     */
    public function scopeRecents($query)
    {
        return $query->orderBy('date_publication', 'desc');
    }

    /**
     * Scope par catégorie
     */
    public function scopeCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
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
    return asset('images/default-actualite.jpg');
}

/**
 * Obtenir les URLs complètes des images de la galerie
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
}