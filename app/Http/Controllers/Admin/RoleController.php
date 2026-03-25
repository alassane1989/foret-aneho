<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Vérifier que l'utilisateur peut gérer les rôles
     * 
     * @return \Illuminate\Http\RedirectResponse|null
     */
    private function authorizeRoleManagement()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login')
                ->with('error', 'Veuillez vous connecter.');
        }
        
        if (!$user->is_super_admin && !$user->is_admin) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Seuls les Super Admin et Admin peuvent gérer les rôles.');
        }
        
        return null;
    }

    /**
     * Afficher la liste des rôles
     */
    public function index()
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        $permissions = Permission::all()->groupBy('groupe');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Enregistrer un nouveau rôle
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        $request->validate([
            'nom' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'niveau' => 'integer|min:0',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'description' => $request->description,
            'niveau' => $request->niveau ?? 0,
            'est_defaut' => $request->has('est_defaut'),
            'created_by' => Auth::id()
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle "' . $role->nom . '" créé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Role $role)
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        $permissions = Permission::all()->groupBy('groupe');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(Request $request, Role $role)
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom,' . $role->id,
            'description' => 'nullable|string',
            'niveau' => 'integer|min:0',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'description' => $request->description,
            'niveau' => $request->niveau ?? 0,
            'est_defaut' => $request->has('est_defaut')
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle "' . $role->nom . '" mis à jour avec succès.');
    }

    /**
     * Supprimer un rôle
     */
    public function destroy(Role $role)
    {
        if ($response = $this->authorizeRoleManagement()) {
            return $response;
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Impossible de supprimer ce rôle car il est attribué à ' . $role->users()->count() . ' utilisateur(s).');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle "' . $role->nom . '" supprimé avec succès.');
    }
}