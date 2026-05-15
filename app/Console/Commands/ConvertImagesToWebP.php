<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\HandlesImageConversion;

class ConvertImagesToWebP extends Command
{
    use HandlesImageConversion;
    
    protected $signature = 'images:convert-to-webp {--delete-original : Supprimer les images originales après conversion} {--directory= : Dossier spécifique à convertir}';
    
    protected $description = 'Convertit toutes les images JPG, JPEG, PNG en WebP';
    
    public function handle()
    {
        $this->info('🔄 Démarrage de la conversion des images en WebP...');
        
        $directories = $this->getDirectoriesToConvert();
        
        foreach ($directories as $directory) {
            $this->info("📁 Conversion du dossier: {$directory}");
            
            $converted = $this->convertDirectoryToWebP(
                $directory, 
                $this->option('delete-original')
            );
            
            $this->info("✅ {$directory}: " . count($converted) . " image(s) convertie(s)");
            
            foreach ($converted as $item) {
                $this->line("   - {$item['original']} → {$item['webp']}");
            }
        }
        
        $this->info('✨ Conversion terminée avec succès!');
    }
    
    protected function getDirectoriesToConvert(): array
    {
        if ($this->option('directory')) {
            return [$this->option('directory')];
        }
        
        // Liste des dossiers à convertir par défaut
        return [
            'images',
            'zones',
            'arbres',
            'uploads',
            'sliders'
        ];
    }
}