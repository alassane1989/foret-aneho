<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Ajout pour le type hinting

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérification de connexion (inchangé)
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter');
        }

        /** @var User $user */
        $user = Auth::user();

        // ✅ CAS 1 : SUPER ADMIN (NOUVEAU)
        // Si l'utilisateur est super_admin, il a tous les droits
        if (isset($user->is_super_admin) && $user->is_super_admin) {
            return $next($request);
        }

        // ✅ CAS 2 : ADMIN (ANCIEN SYSTÈME - VOTRE CODE ACTUEL)
        // Votre vérification existante avec is_admin
        if (isset($user->is_admin) && $user->is_admin) {
            return $next($request);
        }

        // ✅ CAS 3 : UTILISATEUR AVEC RÔLES (NOUVEAU SYSTÈME)
        // Si l'utilisateur a des rôles (via la table roles)
        if (method_exists($user, 'roles') && $user->roles()->exists()) {
       
        return $next($request);
        }

        // ❌ AUCUN DROIT - Déconnexion et redirection (comme votre code)
        Auth::logout();
        return redirect()->route('admin.login')->with('error', 'Accès non autorisé');
    }
}