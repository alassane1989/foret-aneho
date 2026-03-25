<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Role;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_admin',
        'is_super_admin',
        'actif',
        'derniere_ip',
        'derniere_connexion',
        'preferences'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_super_admin' => 'boolean',
        'actif' => 'boolean',
        'derniere_connexion' => 'datetime',
        'preferences' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */

    public function aRole(string $roleSlug): bool
    {
        return $this->roles()
            ->where('slug', $roleSlug)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION CHECK (VERSION OPTIMISÉE)
    |--------------------------------------------------------------------------
    */

    public function aPermission(string $permissionSlug): bool
    {
        // Super Admin a tous les droits
        if ($this->is_super_admin) {
            return true;
        }

        // Admin temporaire (ancien système)
        if ($this->is_admin) {
            return true;
        }

        // Vérification via relation (1 seule requête SQL)
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    public function getRolesListeAttribute(): string
    {
        return $this->roles->pluck('nom')->implode(', ') ?: 'Aucun rôle';
    }

    public function getRolesBadgesAttribute(): string
    {
        return $this->roles->map(function ($role) {
            return '<span class="badge bg-info me-1">' . e($role->nom) . '</span>';
        })->implode('') ?: '<span class="badge bg-secondary">Aucun rôle</span>';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActif(Builder $query)
    {
        return $query->where('actif', true);
    }

    public function scopeRecherche(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}