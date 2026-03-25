<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actualite;
use App\Models\User;

class ActualiteSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('is_admin', true)->first();

        $actualites = [
            [
                'titre' => 'Nouvelle campagne de plantation 2026',
                'description_courte' => 'La mairie d\'Aného lance une nouvelle campagne de plantation de 50 arbres dans la zone LOLAN pour augmenter la couverture végétale de la ville.',
                'contenu' => "<p>La Commune des Lacs 1, en collaboration avec les associations environnementales locales, lance une ambitieuse campagne de plantation visant à enrichir la forêt urbaine d'Aného de 50 nouveaux arbres.</p>
                             <h3>Objectifs de la campagne</h3>
                             <p>Cette campagne, qui se déroulera du 25 au 30 janvier 2026, a pour objectifs principaux :</p>
                             <ul>
                                 <li>Planter 50 arbres dans la zone LOLAN, principalement des espèces locales adaptées</li>
                                 <li>Sensibiliser la population à l'importance des arbres en milieu urbain</li>
                                 <li>Impliquer les écoles et les associations dans une action concrète pour l'environnement</li>
                                 <li>Créer un nouveau 'sentier pédagogique' dans la zone LOLAN</li>
                             </ul>
                             <h3>Espèces sélectionnées</h3>
                             <p>Les espèces choisies pour cette campagne ont été sélectionnées pour leur adaptation au climat local et leur valeur écologique :</p>
                             <ul>
                                 <li>Manguier (15 arbres) - Arbre fruitier apprécié, croissance rapide</li>
                                 <li>Acajou d'Afrique (10 arbres) - Bois précieux, ombrage dense</li>
                                 <li>Neem (10 arbres) - Propriétés médicinales, résistant à la sécheresse</li>
                                 <li>Flamboyant (15 arbres) - Valeur ornementale, floraison spectaculaire</li>
                             </ul>
                             <h3>Comment participer ?</h3>
                             <p>Les citoyens souhaitant participer à cette campagne peuvent :</p>
                             <ol>
                                 <li>S'inscrire comme bénévole via le formulaire en ligne ou à la mairie</li>
                                 <li>Faire un don pour financer les plants et le matériel</li>
                                 <li>Parrainer un arbre pour contribuer à son entretien</li>
                                 <li>Partager l'information sur les réseaux sociaux</li>
                             </ol>",
                'categorie' => 'plantation',
                'image_principale' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=1200&h=800&fit=crop',
                'user_id' => $admin->id,
                'auteur_nom' => 'Service Environnement',
                'tags' => json_encode(['plantation', 'environnement', 'LOLAN', 'bénévolat']),
                'vues' => 245,
                'date_publication' => '2026-01-20',
                'est_publie' => true,
                'meta_title' => 'Campagne de plantation 2026 - Forêt d\'Aného',
                'meta_description' => 'Participez à la plantation de 50 arbres dans la zone LOLAN du 25 au 30 janvier 2026.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'titre' => 'Visite pédagogique des écoles',
                'description_courte' => 'Plus de 200 élèves des écoles locales ont visité la forêt urbaine dans le cadre d\'un projet éducatif sur la biodiversité.',
                'contenu' => "<p>Cette semaine, la forêt urbaine d'Aného a accueilli plus de 200 élèves des écoles primaires de la commune pour des visites pédagogiques.</p>
                             <h3>Au programme</h3>
                             <ul>
                                 <li>Découverte des espèces d'arbres locales</li>
                                 <li>Ateliers de reconnaissance des feuilles et des fruits</li>
                                 <li>Activités de plantation symbolique</li>
                                 <li>Jeux éducatifs sur la biodiversité</li>
                             </ul>
                             <p>Les enfants ont pu apprendre l'importance des arbres en milieu urbain et sont repartis avec un livret pédagogique et un petit plant à faire grandir chez eux.</p>",
                'categorie' => 'education',
                'image_principale' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=800&fit=crop',
                'user_id' => $admin->id,
                'auteur_nom' => 'Service Éducation',
                'tags' => json_encode(['éducation', 'écoles', 'enfants', 'ateliers']),
                'vues' => 189,
                'date_publication' => '2026-01-15',
                'est_publie' => true,
                'meta_title' => 'Visite scolaire de la forêt - Forêt d\'Aného',
                'meta_description' => 'Découvrez comment les écoles découvrent la forêt urbaine.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'titre' => 'Installation de nouveaux panneaux pédagogiques',
                'description_courte' => 'Des panneaux informatifs ont été installés dans les 4 zones de la forêt pour mieux informer les visiteurs sur les espèces d\'arbres.',
                'contenu' => "<p>La forêt urbaine d'Aného s'enrichit de nouveaux panneaux pédagogiques dans ses quatre zones. Ces panneaux, bilingues français-ewe, présentent :</p>
                             <ul>
                                 <li>Les espèces d'arbres présentes dans chaque zone</li>
                                 <li>Leurs caractéristiques botaniques</li>
                                 <li>Leurs utilisations traditionnelles</li>
                                 <li>Leur importance écologique</li>
                             </ul>
                             <p>Des QR codes sur chaque panneau permettent d'accéder à des fiches détaillées sur le site internet.</p>",
                'categorie' => 'infrastructure',
                'image_principale' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200&h=800&fit=crop',
                'user_id' => $admin->id,
                'auteur_nom' => 'Service Environnement',
                'tags' => json_encode(['panneaux', 'signalétique', 'information', 'bilingue']),
                'vues' => 134,
                'date_publication' => '2026-01-10',
                'est_publie' => true,
                'meta_title' => 'Nouveaux panneaux pédagogiques - Forêt d\'Aného',
                'meta_description' => 'Découvrez les nouveaux panneaux d\'information installés dans la forêt.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($actualites as $actualite) {
            Actualite::create($actualite);
        }

        $this->command->info('✅ ' . count($actualites) . ' actualités créées avec succès !');
    }
}