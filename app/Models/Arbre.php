<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class Arbre extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'nom',
        'slug',
        'zone_id',
        'espece_id',
        'date_plantation',
        'planteur_nom',
        'planteur_photo',
        'photo_arbre',
        'description',
        'latitude',
        'longitude',
        'etat_sante',
        'qr_code',
        'vues'
    ];

    protected $casts = [
        'date_plantation' => 'date',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6'
    ];


/*noveau code  */
public function images()
    {
        return $this->hasMany(ArbreImage::class)->orderBy('ordre');
    }

    /**
     * Récupère l'image principale (première image ou photo_arbre)
     */
    public function getImagePrincipaleAttribute()
    {
        $premiereImage = $this->images()->first();
        if ($premiereImage) {
            return $premiereImage->full_url;
        }
        return $this->photo_url;
    }

    /**
     * Récupère toutes les images pour la galerie (inclut photo_arbre comme première)
     */
    public function getImagesGalerieAttribute()
    {
        $galerie = collect();
        
        // Ajouter la photo principale en première position
        $galerie->push((object)[
            'url' => $this->photo_url,
            'thumbnail_url' => $this->photo_url,
            'titre' => $this->nom,
            'description' => 'Vue principale de l\'arbre',
            'type' => 'vue_ensemble',
            'icone' => 'fa-tree',
            'type_libelle' => 'Vue d\'ensemble'
        ]);
        
        // Ajouter les images supplémentaires
        foreach ($this->images as $image) {
            $galerie->push($image);
        }
        
        return $galerie;
    }

    /**
     * Récupère les images groupées par type
     */
    public function getImagesParTypeAttribute()
    {
        return $this->images->groupBy('type');
    }













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
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function espece()
    {
        return $this->belongsTo(Espece::class);
    }

    /**
     * Boot method pour générer le QR code à la création
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($arbre) {
            $arbre->generateQrCode();
        });

        static::created(function ($arbre) {
            // Mettre à jour les statistiques de la zone
            if ($arbre->zone) {
                $arbre->zone->updateStatistiques();
            }
        });

        static::deleted(function ($arbre) {
            // Mettre à jour les statistiques de la zone
            if ($arbre->zone) {
                $arbre->zone->updateStatistiques();
            }
            // Supprimer le QR code
            if ($arbre->qr_code) {
                Storage::disk('public')->delete('qrcodes/' . $arbre->qr_code . '.svg');
            }
        });
    }

    /**
     * Génère un QR code unique pour l'arbre
     */
    public function generateQrCode()
    {
        // Générer un identifiant unique pour le QR code
        $uniqueId = 'arbre-' . Str::uuid()->toString();
        $this->qr_code = $uniqueId;

        // Générer l'URL complète de la fiche arbre
        $url = URL::to('/arbres/' . $this->slug);

        // Générer le QR code au format SVG
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('M')
            ->generate($url);

        // Définir le chemin de stockage
        $filename = 'qrcodes/' . $uniqueId . '.svg';

        // Sauvegarder le QR code sur le disque public
        Storage::disk('public')->put($filename, $qrCodeSvg);

        return $uniqueId;
    }

    /**
     * Récupère l'URL du QR code
     */
    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code) {
            return asset('storage/qrcodes/' . $this->qr_code . '.svg');
        }
        return null;
    }

    /**
     * Obtenir l'URL complète de la photo de l'arbre
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_arbre) {
            // Vérifier si le chemin commence déjà par 'storage/'
            if (str_starts_with($this->photo_arbre, 'storage/')) {
                return asset($this->photo_arbre);
            }
            return asset('storage/' . $this->photo_arbre);
        }
        return asset('images/default-tree.jpg');
    }

    /**
     * Obtenir l'URL complète de la photo du planteur
     */
    public function getPlanteurPhotoUrlAttribute()
    {
        if ($this->planteur_photo) {
            // Vérifier si le chemin commence déjà par 'storage/'
            if (str_starts_with($this->planteur_photo, 'storage/')) {
                return asset($this->planteur_photo);
            }
            return asset('storage/' . $this->planteur_photo);
        }
        return asset('images/default-avatar.jpg');
    }

    /**
     * Incrémente le nombre de vues
     */
    public function incrementerVues()
    {
        $this->increment('vues');
    }

    /**
     * Calcule l'âge de l'arbre
     */
    public function getAgeAttribute()
    {
        return $this->date_plantation->diffInYears(now()) . ' an' . 
               ($this->date_plantation->diffInYears(now()) > 1 ? 's' : '');
    }

    /**
     * Récupère les coordonnées formatées
     */
    public function getCoordonneesAttribute()
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude
        ];
    }

    /**
     * Récupère l'état de santé formaté
     */
    public function getEtatSanteFormateAttribute()
    {
        $etats = [
            'excellent' => 'Excellent',
            'bon' => 'Bon',
            'moyen' => 'Moyen',
            'surveille' => 'Surveillé'
        ];
        return $etats[$this->etat_sante] ?? $this->etat_sante;
    }

    /**
     * Récupère la couleur selon l'état de santé
     */
    public function getEtatSanteCouleurAttribute()
    {
        $couleurs = [
            'excellent' => 'success',
            'bon' => 'primary',
            'moyen' => 'warning',
            'surveille' => 'danger'
        ];
        return $couleurs[$this->etat_sante] ?? 'secondary';
    }

    /**
     * Scope pour filtrer par zone
     */
    public function scopeDansZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    /**
     * Scope pour filtrer par espèce
     */
    public function scopeDeEspece($query, $especeId)
    {
        return $query->where('espece_id', $especeId);
    }

    /**
     * Scope pour les arbres les plus vus
     */
    public function scopePlusVus($query)
    {
        return $query->orderBy('vues', 'desc');
    }

    /**
     * Scope pour les arbres récents
     */
    public function scopeRecents($query)
    {
        return $query->orderBy('date_plantation', 'desc');
    }
}