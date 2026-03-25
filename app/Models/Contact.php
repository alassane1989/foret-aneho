<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * Liste des sujets disponibles
     */
    public const SUJETS = [
        'info' => 'Demande d\'information',
        'visite' => 'Organiser une visite',
        'participation' => 'Participer aux plantations',
        'projet' => 'Projet scolaire/universitaire',
        'partenariat' => 'Proposition de partenariat',
        'autre' => 'Autre demande'
    ];

    /**
     * Champs remplissables
     */
    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'lu',
        'date_traitement'
    ];

    /**
     * Casts
     */
    protected $casts = [
        'lu' => 'boolean',
        'date_traitement' => 'datetime'
    ];

    /**
     * Marquer le message comme lu
     */
    public function marquerCommeLu()
    {
        $this->update([
            'lu' => true,
            'date_traitement' => now()
        ]);
    }

    /**
     * Accessor : sujet formaté
     * Utilisation : $contact->sujet_formate
     */
    public function getSujetFormateAttribute()
    {
        return self::SUJETS[$this->sujet] ?? $this->sujet;
    }

    /**
     * Scope : messages non lus
     */
    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }

    /**
     * Scope : messages lus
     */
    public function scopeLus($query)
    {
        return $query->where('lu', true);
    }
}