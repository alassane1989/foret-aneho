<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'administrateur
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
        ]);

        // Mise à jour avec chemin absolu
        User::where('id', $userId)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Mise à jour avec chemin absolu
        User::where('id', $userId)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Mot de passe changé avec succès.');
    }

    /**
     * Mettre à jour l'avatar
     */
    public function updateAvatar(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Récupérer l'utilisateur pour l'ancien avatar
        $user = User::find($userId);

        // Supprimer l'ancien avatar
        if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Upload du nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Mise à jour avec chemin absolu
        User::where('id', $userId)->update([
            'avatar' => $path
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Avatar mis à jour avec succès.');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $userId = Auth::id();

        // Récupérer l'utilisateur pour l'avatar
        $user = User::find($userId);

        if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Mise à jour avec chemin absolu
        User::where('id', $userId)->update([
            'avatar' => null
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Avatar supprimé avec succès.');
    }
}