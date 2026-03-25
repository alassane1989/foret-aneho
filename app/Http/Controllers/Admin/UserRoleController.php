<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
{
    /**
     * Vérifier que l'utilisateur peut gérer les rôles des utilisateurs
     */
    private function authorizeUserRoleManagement()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login')
                ->with('error', 'Veuillez vous connecter.');
        }
        
        // Seuls Super Admin et Admin peuvent gérer les utilisateurs
        if (!$user->is_super_admin && !$user->is_admin) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Seuls les Super Admin et Admin peuvent gérer les utilisateurs.');
        }
        
        return null;
    }

    /**
     * Vérifier si l'utilisateur connecté peut modifier un utilisateur cible
     */
    private function canModifyUser(User $targetUser)
    {
        $currentUser = Auth::user();
        
        // Super Admin peut tout modifier
        if ($currentUser->is_super_admin) {
            return true;
        }
        
        // Admin ne peut pas modifier un Super Admin
        if ($targetUser->is_super_admin) {
            return false;
        }
        
        // Admin ne peut pas modifier son propre rôle pour devenir Super Admin
        if ($currentUser->id === $targetUser->id) {
            // On autorise mais avec des restrictions dans update()
            return true;
        }
        
        return true;
    }

    /**
     * Vérifier si l'utilisateur connecté peut attribuer un rôle spécifique
     */
    private function canAssignRole(Role $role, User $targetUser)
    {
        $currentUser = Auth::user();
        
        // Super Admin peut tout faire
        if ($currentUser->is_super_admin) {
            return true;
        }
        
        // Admin ne peut pas attribuer le rôle Super Admin
        if ($role->slug === 'super-admin') {
            return false;
        }
        
        return true;
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        // VÉRIFICATION AJOUTÉE
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $query = User::with('roles');

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $query->recherche($request->search);
        }

        // Filtre par statut
        if ($request->has('statut') && $request->statut != '') {
            if ($request->statut === 'actif') {
                $query->where('actif', true);
            } elseif ($request->statut === 'inactif') {
                $query->where('actif', false);
            }
        }

        // Filtre par rôle
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Afficher le formulaire d'assignation des rôles
     */
    public function edit(User $user)
    {
        // VÉRIFICATION AJOUTÉE
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        // Vérifier si on peut modifier cet utilisateur
        if (!$this->canModifyUser($user)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas le droit de modifier cet utilisateur.');
        }

        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Mettre à jour les rôles de l'utilisateur
     */
    public function update(Request $request, User $user)
    {
        // VÉRIFICATION AJOUTÉE
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        // Vérifier si on peut modifier cet utilisateur
        if (!$this->canModifyUser($user)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous n\'avez pas le droit de modifier cet utilisateur.');
        }

        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $currentUser = Auth::user();
        $selectedRoles = $request->roles ?? [];

        // Vérification spéciale : un Admin ne peut pas s'attribuer le rôle Super Admin
        if (!$currentUser->is_super_admin && $currentUser->id === $user->id) {
            foreach ($selectedRoles as $roleId) {
                $role = Role::find($roleId);
                if ($role && $role->slug === 'super-admin') {
                    return redirect()->back()
                        ->with('error', 'Vous ne pouvez pas vous attribuer le rôle Super Admin.');
                }
            }
        }

        // Vérifier chaque rôle sélectionné
        foreach ($selectedRoles as $roleId) {
            $role = Role::find($roleId);
            if ($role && !$this->canAssignRole($role, $user)) {
                return redirect()->back()
                    ->with('error', 'Vous n\'avez pas le droit d\'attribuer le rôle "' . $role->nom . '".');
            }
        }

        // Synchroniser les rôles
        $user->roles()->sync($selectedRoles);

        // Mettre à jour is_super_admin si le rôle super-admin est attribué
        if ($user->roles()->where('slug', 'super-admin')->exists()) {
            $user->update(['is_super_admin' => true]);
        } else {
            // Ne pas enlever is_super_admin si c'est l'utilisateur lui-même (sauf si Super Admin)
            if (!$currentUser->is_super_admin || $currentUser->id !== $user->id) {
                $user->update(['is_super_admin' => false]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Rôles de ' . $user->name . ' mis à jour avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus(User $user)
    {
        // VÉRIFICATION AJOUTÉE
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $currentUser = Auth::user();

        // Empêcher la désactivation de soi-même
        if ($currentUser->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        // Empêcher un Admin de désactiver un Super Admin
        if (!$currentUser->is_super_admin && $user->is_super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas désactiver un Super Admin.');
        }

        $user->update(['actif' => !$user->actif]);

        $message = $user->actif 
            ? 'Compte de ' . $user->name . ' activé avec succès.' 
            : 'Compte de ' . $user->name . ' désactivé avec succès.';

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * API pour la recherche rapide (AJAX)
     */
    public function search(Request $request)
    {
        // VÉRIFICATION AJOUTÉE
        if ($response = $this->authorizeUserRoleManagement()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $search = $request->get('q', '');
        
        $users = User::recherche($search)
            ->with('roles')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                    'roles' => $user->roles->pluck('nom')
                ];
            });

        return response()->json($users);
    }


        /**
     * Afficher le formulaire de création d'utilisateur
     */
    public function create()
    {
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'actif' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'actif' => $request->has('actif'),
            'is_admin' => false,
            'is_super_admin' => false
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
            
            if ($user->roles()->where('slug', 'super-admin')->exists()) {
                $user->update(['is_super_admin' => true]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur "' . $user->name . '" créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        if ($response = $this->authorizeUserRoleManagement()) {
            return $response;
        }

        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if (!$currentUser->is_super_admin && $user->is_super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer un Super Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur "' . $user->name . '" supprimé avec succès.');
    }
}