<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use Illuminate\Support\Str;

class ZoneSeeder extends Seeder
{
    public function run()
    {
        $zones = [
            [
                'nom' => 'GLIDJI',
                'description_courte' => 'Zone sacrée historique avec des arbres centenaires et une biodiversité préservée.',
                'description_longue' => 'La zone GLIDJI est la zone historique et sacrée de la forêt urbaine d\'Aného. Elle abrite des arbres centenaires dont certains sont considérés comme sacrés par la communauté locale. Cette zone représente un patrimoine naturel et culturel inestimable. Les sentiers serpentent entre des arbres majestueux, offrant aux visiteurs une expérience unique de communion avec la nature et l\'histoire locale. Des panneaux explicatifs présentent l\'histoire et l\'importance de chaque arbre remarquable.',
                'superficie' => '2.5 ha',
                'couleur' => '#4CAF50',
                'adresse_acces' => 'Rue des Baobabs, Aného',
                'ordre' => 1,
                'est_active' => true,
                'activites' => json_encode([
                    ['icone' => 'hiking', 'nom' => 'Visite historique guidée'],
                    ['icone' => 'camera', 'nom' => 'Photographie nature'],
                    ['icone' => 'book', 'nom' => 'Ateliers traditionnels'],
                    ['icone' => 'meditation', 'nom' => 'Méditation en nature']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'LOLAN',
                'description_courte' => 'Zone éducative avec des sentiers pédagogiques et des espèces fruitières.',
                'description_longue' => 'La zone LOLAN est dédiée à l\'éducation et à la sensibilisation environnementale. Conçue spécialement pour les visites scolaires et familiales, elle propose des sentiers pédagogiques adaptés à tous les âges. Des ateliers éducatifs sont régulièrement organisés dans cette zone, permettant aux visiteurs d\'apprendre à reconnaître les différentes espèces d\'arbres, comprendre leur rôle dans l\'écosystème et participer à des activités de plantation.',
                'superficie' => '1.8 ha',
                'couleur' => '#2196F3',
                'adresse_acces' => 'Avenue des Manguiers, Aného',
                'ordre' => 2,
                'est_active' => true,
                'activites' => json_encode([
                    ['icone' => 'graduation-cap', 'nom' => 'Visites scolaires'],
                    ['icone' => 'seedling', 'nom' => 'Ateliers de plantation'],
                    ['icone' => 'utensils', 'nom' => 'Dégustation de fruits'],
                    ['icone' => 'gamepad', 'nom' => 'Jeux éducatifs']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'NLESS',
                'description_courte' => 'Zone de réhabilitation écologique avec des espèces locales adaptées.',
                'description_longue' => 'La zone NLESS est une zone de réhabilitation écologique où des techniques innovantes sont employées pour restaurer des écosystèmes dégradés. Elle sert de laboratoire à ciel ouvert pour la recherche en écologie urbaine. Cette zone abrite principalement des espèces locales adaptées aux conditions difficiles, démontrant comment la nature peut reprendre ses droits même en milieu urbain avec un peu d\'aide et d\'attention.',
                'superficie' => '3.2 ha',
                'couleur' => '#FF9800',
                'adresse_acces' => 'Chemin des Acacias, Aného',
                'ordre' => 3,
                'est_active' => true,
                'activites' => json_encode([
                    ['icone' => 'tools', 'nom' => 'Ateliers de réhabilitation'],
                    ['icone' => 'binoculars', 'nom' => 'Observation écologique'],
                    ['icone' => 'recycle', 'nom' => 'Compostage démonstratif'],
                    ['icone' => 'chart-line', 'nom' => 'Suivi scientifique']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'YVELINES',
                'description_courte' => 'Zone expérimentale avec des espèces introduites et des techniques innovantes.',
                'description_longue' => 'La zone YVELINES est la zone expérimentale de la forêt urbaine. Elle accueille des espèces d\'arbres introduites et des techniques de culture innovantes testées pour leur adaptation au climat local. Cette zone permet d\'étudier le comportement de différentes espèces dans un environnement urbain et d\'évaluer leur potentiel pour les futures campagnes de reboisement en milieu urbain.',
                'superficie' => '2.1 ha',
                'couleur' => '#9C27B0',
                'adresse_acces' => 'Boulevard des Flamboyants, Aného',
                'ordre' => 4,
                'est_active' => true,
                'activites' => json_encode([
                    ['icone' => 'flask', 'nom' => 'Visites expérimentales'],
                    ['icone' => 'clipboard-check', 'nom' => 'Tests d\'adaptation'],
                    ['icone' => 'lightbulb', 'nom' => 'Ateliers innovation'],
                    ['icone' => 'chart-bar', 'nom' => 'Collecte de données']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }

        $this->command->info('✅ 4 zones créées avec succès !');
    }
}