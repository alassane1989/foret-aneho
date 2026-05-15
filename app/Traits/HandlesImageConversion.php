<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Log;

trait HandlesImageConversion
{
    /**
     * Convertit une image uploadée en WebP
     */
    protected function convertToWebP(UploadedFile $file, string $path, int $quality = 85): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return $this->storeOriginal($file, $path);
        }

        // Nom du fichier
        $webpName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        $webpPath = $path . '/' . $webpName;

        // Manager V3
        $manager = new ImageManager(new Driver());

        // Lire et convertir
        $image = $manager->read($file)->toWebp($quality);

        // Sauvegarde
        Storage::disk('public')->put($webpPath, (string) $image);

        // Optimisation
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize(Storage::disk('public')->path($webpPath));

        return $webpPath;
    }

    /**
     * Stocke l'image originale
     */
    protected function storeOriginal(UploadedFile $file, string $path): string
    {
        $name = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs($path, $name, 'public');
    }

    /**
     * Convertit une image existante en WebP
     */
    protected function convertExistingToWebP(string $existingPath, int $quality = 85): ?string
    {
        if (!Storage::disk('public')->exists($existingPath)) {
            return null;
        }

        $extension = strtolower(pathinfo($existingPath, PATHINFO_EXTENSION));

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return $existingPath;
        }

        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $existingPath);

        // Déjà convertie ?
        if (Storage::disk('public')->exists($webpPath)) {
            return $webpPath;
        }

        try {
            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read(Storage::disk('public')->path($existingPath))
                ->toWebp($quality);

            Storage::disk('public')->put($webpPath, (string) $image);

            // Optimisation
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(Storage::disk('public')->path($webpPath));

            return $webpPath;

        } catch (\Exception $e) {
           Log::error('Erreur conversion WebP: ' . $e->getMessage());
            return $existingPath;
        }
    }

    /**
     * Supprime les images originales
     */
    protected function deleteOriginalImages(string $webpPath): void
    {
        $directory = dirname($webpPath);
        $filename = pathinfo($webpPath, PATHINFO_FILENAME);

        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $original = $directory . '/' . $filename . '.' . $ext;

            if (Storage::disk('public')->exists($original)) {
                Storage::disk('public')->delete($original);
            }
        }
    }

    /**
     * Convertit tout un dossier en WebP
     */
    protected function convertDirectoryToWebP(string $directory, bool $deleteOriginal = false): array
    {
        $converted = [];
        $files = Storage::disk('public')->files($directory);

        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {

                $webpPath = $this->convertExistingToWebP($file);

                if ($webpPath && $deleteOriginal) {
                    $this->deleteOriginalImages($webpPath);

                    $converted[] = [
                        'original' => $file,
                        'webp' => $webpPath,
                        'status' => 'converted_and_deleted'
                    ];
                } elseif ($webpPath) {

                    $converted[] = [
                        'original' => $file,
                        'webp' => $webpPath,
                        'status' => 'converted'
                    ];
                }
            }
        }

        return $converted;
    }
}