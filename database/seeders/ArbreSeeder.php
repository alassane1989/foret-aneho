<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Arbre;
use App\Models\Zone;
use App\Models\Espece;

class ArbreSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les zones
        $zoneGlidji = Zone::where('nom', 'GLIDJI')->first();
        $zoneLolan = Zone::where('nom', 'LOLAN')->first();
        $zoneNless = Zone::where('nom', 'NLESS')->first();
        $zoneYvelines = Zone::where('nom', 'YVELINES')->first();

        // Récupérer les espèces
        $baobab = Espece::where('nom_scientifique', 'Adansonia digitata')->first();
        $manguier = Espece::where('nom_scientifique', 'Mangifera indica')->first();
        $acajou = Espece::where('nom_scientifique', 'Khaya senegalensis')->first();
        $fromager = Espece::where('nom_scientifique', 'Ceiba pentandra')->first();
        $neem = Espece::where('nom_scientifique', 'Azadirachta indica')->first();
        $flamboyant = Espece::where('nom_scientifique', 'Delonix regia')->first();
        $karite = Espece::where('nom_scientifique', 'Vitellaria paradoxa')->first();
        $nere = Espece::where('nom_scientifique', 'Parkia biglobosa')->first();

        $arbres = [
            // ZONE GLIDJI
            [
                'nom' => 'Baobab Sacré de GLIDJI',
                'zone_id' => $zoneGlidji->id,
                'espece_id' => $baobab->id,
                'date_plantation' => '2020-06-15',
                'planteur_nom' => 'Koffi Adjo',
                'planteur_photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop',
                'description' => 'Ce baobab centenaire est considéré comme sacré par la communauté locale. Il mesure environ 15 mètres de circonférence et abrite une biodiversité riche dans son écorce et ses branches. Planté en 2020, il fait partie des premiers arbres de la zone GLIDJI. Les anciens du village s\'y réunissent encore pour les cérémonies traditionnelles.',
                'latitude' => 6.230123,
                'longitude' => 1.592456,
                'etat_sante' => 'excellent',
                'vues' => 245,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Fromager des Ancêtres',
                'zone_id' => $zoneGlidji->id,
                'espece_id' => $fromager->id,
                'date_plantation' => '2019-11-10',
                'planteur_nom' => 'Afi Mensah',
                'planteur_photo' => 'https://images.unsplash.com/photo-1494790108755-998931785fa8?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800&h=600&fit=crop',
                'description' => 'Fromager historique de la zone GLIDJI, cet arbre imposant domine le paysage de la forêt. Ses contreforts massifs et ses racines aériennes créent un espace mystérieux. Il abrite une colonie de chauves-souris frugivores qui participent à la pollinisation et à la dispersion des graines.',
                'latitude' => 6.231567,
                'longitude' => 1.591234,
                'etat_sante' => 'excellent',
                'vues' => 189,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Acajou d\'Afrique',
                'zone_id' => $zoneGlidji->id,
                'espece_id' => $acajou->id,
                'date_plantation' => '2020-05-05',
                'planteur_nom' => 'Kossi Mensah',
                'planteur_photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1462143338528-eca9936a4d09?w=800&h=600&fit=crop',
                'description' => 'Jeune acajou d\'Afrique en pleine croissance. Son bois précieux est protégé et suivi attentivement par les services forestiers. Il fait partie d\'un programme de conservation de cette espèce vulnérable.',
                'latitude' => 6.232789,
                'longitude' => 1.590567,
                'etat_sante' => 'bon',
                'vues' => 78,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ZONE LOLAN
            [
                'nom' => 'Manguier Éducatif',
                'zone_id' => $zoneLolan->id,
                'espece_id' => $manguier->id,
                'date_plantation' => '2020-08-22',
                'planteur_nom' => 'École primaire d\'Aného',
                'planteur_photo' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=800&h=600&fit=crop',
                'description' => 'Manguier planté par les élèves de l\'école primaire lors d\'une journée pédagogique. Il est utilisé pour les ateliers éducatifs sur les arbres fruitiers et l\'alimentation saine. Chaque année, les enfants participent à la récolte des mangues.',
                'latitude' => 6.225678,
                'longitude' => 1.600123,
                'etat_sante' => 'excellent',
                'vues' => 156,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Baobab du Savoir',
                'zone_id' => $zoneLolan->id,
                'espece_id' => $baobab->id,
                'date_plantation' => '2021-03-22',
                'planteur_nom' => 'Service Environnement',
                'planteur_photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop',
                'description' => 'Baobab planté lors d\'une journée éducative avec les collégiens. Il est équipé d\'un panneau pédagogique expliquant le rôle du baobab dans l\'écosystème et son importance culturelle. Les élèves viennent régulièrement mesurer sa croissance.',
                'latitude' => 6.226789,
                'longitude' => 1.601234,
                'etat_sante' => 'bon',
                'vues' => 112,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Neem Médicinal',
                'zone_id' => $zoneLolan->id,
                'espece_id' => $neem->id,
                'date_plantation' => '2021-06-10',
                'planteur_nom' => 'Association des Femmes',
                'planteur_photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=800&h=600&fit=crop',
                'description' => 'Neem planté par l\'association des femmes d\'Aného. Elles utilisent ses feuilles pour des ateliers de démonstration des vertus médicinales du neem (traitement du paludisme, soins de la peau, etc.).',
                'latitude' => 6.227234,
                'longitude' => 1.602345,
                'etat_sante' => 'bon',
                'vues' => 89,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ZONE NLESS
            [
                'nom' => 'Karité Sauvage',
                'zone_id' => $zoneNless->id,
                'espece_id' => $karite->id,
                'date_plantation' => '2020-02-15',
                'planteur_nom' => 'Coopérative des productrices',
                'planteur_photo' => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1462143338528-eca9936a4d09?w=800&h=600&fit=crop',
                'description' => 'Karité sauvage protégé dans la zone de réhabilitation. Il est suivi par la coopérative des productrices de beurre de karité qui viennent récolter les fruits selon les méthodes traditionnelles. Ateliers de démonstration de la transformation des noix.',
                'latitude' => 6.240123,
                'longitude' => 1.577234,
                'etat_sante' => 'excellent',
                'vues' => 134,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Néré du Partage',
                'zone_id' => $zoneNless->id,
                'espece_id' => $nere->id,
                'date_plantation' => '2021-01-20',
                'planteur_nom' => 'Groupe villageois',
                'planteur_photo' => 'https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=600&fit=crop',
                'description' => 'Néré planté pour la production de soumbala (épice traditionnelle). Des ateliers de démonstration de la fermentation des graines sont organisés autour de cet arbre. Il symbolise le partage et la transmission des savoirs culinaires.',
                'latitude' => 6.241234,
                'longitude' => 1.578345,
                'etat_sante' => 'bon',
                'vues' => 67,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ZONE YVELINES
            [
                'nom' => 'Flamboyant Coloré',
                'zone_id' => $zoneYvelines->id,
                'espece_id' => $flamboyant->id,
                'date_plantation' => '2021-12-15',
                'planteur_nom' => 'Service des Espaces Verts',
                'planteur_photo' => 'https://images.unsplash.com/photo-1522529599102-193c0d76b5b6?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1566140967404-b8b3932483f5?w=800&h=600&fit=crop',
                'description' => 'Flamboyant planté dans la zone expérimentale pour étudier son adaptation au climat urbain. Sa floraison spectaculaire est un atout pour l\'embellissement de la forêt. Des boutures sont prélevées pour multiplication.',
                'latitude' => 6.218901,
                'longitude' => 1.603456,
                'etat_sante' => 'bon',
                'vues' => 145,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Neem Résistant',
                'zone_id' => $zoneYvelines->id,
                'espece_id' => $neem->id,
                'date_plantation' => '2022-04-30',
                'planteur_nom' => 'Étudiants en agroforesterie',
                'planteur_photo' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=400&fit=crop',
                'photo_arbre' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=800&h=600&fit=crop',
                'description' => 'Neem faisant partie d\'une étude sur la résistance des espèces introduites aux conditions urbaines. Des capteurs mesurent son absorption de CO2 et sa croissance comparée à d\'autres spécimens.',
                'latitude' => 6.219456,
                'longitude' => 1.604567,
                'etat_sante' => 'excellent',
                'vues' => 56,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($arbres as $arbre) {
            Arbre::create($arbre);
        }

        // Mettre à jour les statistiques des zones
        Zone::all()->each(function ($zone) {
            $zone->updateStatistiques();
        });

        $this->command->info('✅ ' . count($arbres) . ' arbres créés avec succès !');
        $this->command->info('✅ Statistiques des zones mises à jour !');
    }
}