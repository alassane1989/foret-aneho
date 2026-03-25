<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'est_actif',
        'date_inscription',
        'date_desinscription'
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'date_inscription' => 'datetime',
        'date_desinscription' => 'datetime'
    ];

    /**
     * Boot method pour définir la date d'inscription
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($newsletter) {
            $newsletter->date_inscription = now();
        });
    }

    /**
     * Désinscrire un abonné
     */
    public function desinscrire()
    {
        $this->est_actif = false;
        $this->date_desinscription = now();
        $this->save();
    }

    /**
     * Réactiver un abonné
     */
    public function reactiver()
    {
        $this->est_actif = true;
        $this->date_desinscription = null;
        $this->save();
    }

    /**
     * Scope pour les abonnés actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('est_actif', true);
    }

    /**
     * Scope pour les abonnés inactifs
     */
    public function scopeInactifs($query)
    {
        return $query->where('est_actif', false);
    }
}