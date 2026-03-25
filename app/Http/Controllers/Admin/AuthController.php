<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Vérifier si le compte est actif
            if (!$user->actif) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte est désactivé. Contactez l\'administrateur.'
                ]);
            }

            // REDIRECTION SELON LE RÔLE
            return $this->redirectUserBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas.',
        ])->onlyInput('email');
    }

    /**
     * Rediriger l'utilisateur selon son rôle
     */
    private function redirectUserBasedOnRole($user)
    {
        // Cas 1 : Super Admin ou Admin → Dashboard complet
        if ($user->is_super_admin || $user->is_admin) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue ' . $user->name . ' !');
        }

        // Cas 2 : Éditeur → Dashboard (mais menu restreint)
        if ($user->roles()->where('slug', 'editeur')->exists()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue dans votre espace d\'édition.');
        }

        // Cas 3 : Modérateur → Directement vers les contacts
        if ($user->roles()->where('slug', 'moderateur')->exists()) {
            return redirect()->intended(route('admin.contacts.index'))
                ->with('success', 'Bienvenue dans votre espace de modération.');
        }

        // Cas 4 : Utilisateur avec d'autres rôles → Dashboard par défaut
        if ($user->roles()->exists()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Connexion réussie.');
        }

        // Cas 5 : Aucun rôle spécifique → Page d'accueil du site
        return redirect()->route('home')
            ->with('info', 'Vous n\'avez pas accès à l\'administration.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Vous avez été déconnecté.');
    }
}