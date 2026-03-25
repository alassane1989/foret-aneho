<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'nom', 'slug', 'groupe', 'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}