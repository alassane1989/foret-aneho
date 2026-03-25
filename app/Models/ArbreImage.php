<?php
// app/Models/ArbreImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArbreImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'arbre_id',
        'url',
        'thumbnail_url',
        'titre',
        'description',
        'type',
        'ordre'
    ];

    protected $casts = [
        'ordre' => 'integer'
    ];

    /**
     * Relation avec l'arbre
     */
    public function arbre()
    {
        return $this->belongsTo(Arbre::class);
    }

    /**
     * Types d'images possibles
     */
    public static $types = [
        'vue_ensemble' => 'Vue d\'ensemble',
        'feuilles' => 'Feuilles',
        'fleurs' => 'Fleurs',
        'fruits' => 'Fruits',
        'ecorce' => 'Écorce',
        'racines' => 'Racines',
        'port' => 'Port de l\'arbre',
        'detail' => 'Détail',
        'saison' => 'Photo saisonnière',
        'autre' => 'Autre'
    ];

    /**
     * Obtenir le libellé du type
     */
    public function getTypeLibelleAttribute()
    {
        return self::$types[$this->type] ?? $this->type;
    }

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getFullUrlAttribute()
    {
        if ($this->url) {
            if (str_starts_with($this->url, 'storage/')) {
                return asset($this->url);
            }
            return asset('storage/' . $this->url);
        }
        return asset('images/default-tree.jpg');
    }

    /**
     * Obtenir l'URL complète de la miniature
     */
    public function getFullThumbnailUrlAttribute()
    {
        if ($this->thumbnail_url) {
            if (str_starts_with($this->thumbnail_url, 'storage/')) {
                return asset($this->thumbnail_url);
            }
            return asset('storage/' . $this->thumbnail_url);
        }
        return $this->full_url; // Fallback sur l'image principale
    }

    /**
     * Icône selon le type
     */
    public function getIconeAttribute()
    {
        $icones = [
            'vue_ensemble' => 'fa-tree',
            'feuilles' => 'fa-leaf',
            'fleurs' => 'fa-seedling',
            'fruits' => 'fa-apple-alt',
            'ecorce' => 'fa-border-all',
            'racines' => 'fa-tree',
            'port' => 'fa-shapes',
            'detail' => 'fa-search-plus',
            'saison' => 'fa-calendar-alt',
            'autre' => 'fa-image'
        ];
        return $icones[$this->type] ?? 'fa-image';
    }

    /**
 * Réordonner les images
 */
public static function reordonner(array $ordre)
{
    foreach ($ordre as $position => $id) {
        self::where('id', $id)->update(['ordre' => $position]);
    }
}
}