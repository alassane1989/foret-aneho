<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer l'administrateur unique
        User::create([
            'name' => 'Administrateur Forêt d\'Aného',
            'email' => 'admin@foret-aneho.tg',
            'password' => Hash::make('Admin@2026'),
            'is_admin' => true,
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('✅ Administrateur créé avec succès !');
        $this->command->info('   Email : admin@foret-aneho.tg');
        $this->command->info('   Mot de passe : Admin@2026');
    }
}\App\Models\Arbre::truncate();\App\Models\Arbre::truncate();\App\Models\Arbre::truncate();