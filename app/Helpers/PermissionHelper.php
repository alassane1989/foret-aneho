<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Vérifier si l'utilisateur connecté a une permission
     */
    public static function check(string $permissionSlug): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            return false;
        }

        return $user->aPermission($permissionSlug);
    }

    /**
     * Vérifier et abort si pas autorisé
     */
    public static function checkOrAbort(string $permissionSlug, string $message = 'Action non autorisée'): void
    {
        if (!self::check($permissionSlug)) {
            abort(403, $message);
        }
    }

    /**
     * Vérifier et rediriger si pas autorisé
     */
    public static function checkOrRedirect(
        string $permissionSlug,
        string $redirectRoute = 'admin.dashboard',
        string $message = 'Action non autorisée'
    ) {
        if (!self::check($permissionSlug)) {
            return redirect()
                ->route($redirectRoute)
                ->with('error', $message);
        }

        return null;
    }
}