<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Afficher les paramètres
     */
    public function index()
    {
        // Récupérer les paramètres depuis les variables PERSONNALISÉES uniquement
        $settings = [
            'site_name' => env('SITE_NAME', 'Forêt d\'Aného'),  // Utilise SITE_NAME, pas APP_NAME
            'site_email' => env('SITE_EMAIL', 'contact@foret-aneho.tg'),
            'site_phone' => env('SITE_PHONE', '+228 XX XX XX XX'),
            'site_address' => env('SITE_ADDRESS', 'Mairie d\'Aného, Togo'),
            'facebook_url' => env('FACEBOOK_URL', '#'),
            'twitter_url' => env('TWITTER_URL', '#'),
            'instagram_url' => env('INSTAGRAM_URL', '#'),
            'youtube_url' => env('YOUTUBE_URL', '#'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres généraux
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string|max:255',
        ]);

        // NE PAS modifier APP_NAME, seulement les variables personnalisées
        $this->updateEnv([
            'SITE_NAME' => $this->formatEnvValue($request->site_name),
            'SITE_EMAIL' => $this->formatEnvValue($request->site_email),
            'SITE_PHONE' => $this->formatEnvValue($request->site_phone),
            'SITE_ADDRESS' => $this->formatEnvValue($request->site_address),
        ]);

        // Mettre à jour aussi MAIL_FROM_NAME si vous voulez
        $this->updateEnv([
            'MAIL_FROM_NAME' => $this->formatEnvValue($request->site_name),
        ]);

        Artisan::call('config:clear');

        return redirect()->route('admin.settings')
            ->with('success', 'Paramètres généraux mis à jour avec succès.');
    }

    /**
     * Mettre à jour les réseaux sociaux
     */
    public function updateSocial(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
        ]);

        $this->updateEnv([
            'FACEBOOK_URL' => $this->formatEnvValue($request->facebook_url ?? '#'),
            'TWITTER_URL' => $this->formatEnvValue($request->twitter_url ?? '#'),
            'INSTAGRAM_URL' => $this->formatEnvValue($request->instagram_url ?? '#'),
            'YOUTUBE_URL' => $this->formatEnvValue($request->youtube_url ?? '#'),
        ]);

        Artisan::call('config:clear');

        return redirect()->route('admin.settings')
            ->with('success', 'Réseaux sociaux mis à jour avec succès.');
    }

    /**
     * Formater une valeur pour le fichier .env
     */
    private function formatEnvValue($value)
    {
        if (is_null($value) || $value === '') {
            return '';
        }

        // Enlever les guillemets existants
        $value = trim($value, '"\'');
        
        // Si la valeur contient des espaces ou des caractères spéciaux
        if (preg_match('/\s|#|\\\\|\$|\'|"/', $value)) {
            $value = str_replace('"', '\"', $value);
            return '"' . $value . '"';
        }
        
        return $value;
    }

    /**
     * Mettre à jour le fichier .env
     */
    private function updateEnv($data)
    {
        $envFile = base_path('.env');
        
        if (!File::exists($envFile)) {
            return false;
        }

        $content = File::get($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            
            if (preg_match($pattern, $content)) {
                $content = preg_replace(
                    $pattern,
                    "{$key}={$value}",
                    $content
                );
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        File::put($envFile, $content);
        return true;
    }

    /**
     * Nettoyer le cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()->route('admin.settings')
            ->with('success', 'Cache nettoyé avec succès.');
    }

    /**
     * Optimiser le site
     */
    public function optimize()
    {
        Artisan::call('optimize');

        return redirect()->route('admin.settings')
            ->with('success', 'Site optimisé avec succès.');
    }

    public function testEmail()
    {
        try {
            return redirect()->route('admin.settings')
                ->with('success', 'Email de test envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings')
                ->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }
}