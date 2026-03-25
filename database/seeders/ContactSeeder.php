<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 50 messages avec différents statuts
        Contact::factory(30)->create(); // Messages aléatoires
        
        // Créer 10 messages non lus spécifiques
        Contact::factory(10)->nonLu()->create([
            'created_at' => now()->subDays(rand(1, 5))
        ]);
        
        // Créer 5 messages avec réponse
        Contact::factory(5)->avecReponse()->create();
        
        // Créer des messages pour chaque type de sujet
        foreach (array_keys(Contact::SUJETS) as $sujet) {
            Contact::factory(3)->create([
                'sujet' => $sujet,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}