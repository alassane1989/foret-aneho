<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ZoneSeeder::class,
            EspeceSeeder::class,
            ArbreSeeder::class,
            ActualiteSeeder::class,
        ]);

        $this->command->info('Toutes les données ont été initialisées avec succès !');
    }
}