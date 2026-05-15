<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\HandlesImageConversion;

class ConvertUploadedImagesToWebP
{
    use HandlesImageConversion;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Traiter tous les fichiers uploadés
        $files = $request->allFiles();
        
        foreach ($files as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $index => $singleFile) {
                    if ($this->isImageFile($singleFile)) {
                        $path = $this->getUploadPath($key);
                        $convertedPath = $this->convertToWebP($singleFile, $path);
                        // Stocker le chemin converti dans la requête
                        $request->merge([$key . '_converted_' . $index => $convertedPath]);
                    }
                }
            } elseif ($this->isImageFile($file)) {
                $path = $this->getUploadPath($key);
                $convertedPath = $this->convertToWebP($file, $path);
                // Remplacer le fichier original par le chemin converti
                $request->merge([$key => $convertedPath]);
                $request->merge([$key . '_webp' => $convertedPath]);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Vérifie si le fichier est une image convertible
     *
     * @param mixed $file
     * @return bool
     */
    protected function isImageFile($file): bool
    {
        if (!$file) return false;
        
        $extensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $extensions);
    }
    
    /**
     * Détermine le chemin de stockage en fonction du champ
     *
     * @param string $field
     * @return string
     */
    protected function getUploadPath(string $field): string
    {
        $paths = [
            'image' => 'images',
            'photo' => 'photos',
            'zone_image' => 'zones',
            'arbre_image' => 'arbres',
            'espece_image' => 'especes',
            'actualite_image' => 'actualites',
            'avatar' => 'avatars',
            'slider_image' => 'sliders',
            'main_image' => 'images',
            'featured_image' => 'images',
        ];
        
        return $paths[$field] ?? 'uploads';
    }
}