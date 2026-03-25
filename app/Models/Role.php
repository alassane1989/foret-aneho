<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom', 'slug', 'description', 'niveau', 'est_defaut', 'created_by'
    ];

    protected $casts = [
        'est_defaut' => 'boolean',
        'niveau' => 'integer'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function aPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }
}