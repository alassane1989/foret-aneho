<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\HandlesImageConversion;

class ConvertUploadedImagesToWebP
{
    use HandlesImageConversion;
    
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
    
    protected function isImageFile($file): bool
    {
        if (!$file) return false;
        
        $extensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $extensions);
    }
    
    protected function getUploadPath(string $field): string
    {
        $paths = [
            'image' => 'images',
            'photo' => 'photos',
            'zone_image' => 'zones',
            'arbre_image' => 'arbres',
            'avatar' => 'avatars',
            'slider_image' => 'sliders',
        ];
        
        return $paths[$field] ?? 'uploads';
    }
}