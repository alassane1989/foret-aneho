<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Espece;

class EspeceSeeder extends Seeder
{
    public function run()
    {
        $especes = [
            // BAOBAB
            [
                'nom_scientifique' => 'Adansonia digitata',
                'nom_local' => 'Baobab',
                'famille' => 'Malvaceae',
                'genre' => 'Adansonia',
                'origine' => 'Afrique subsaharienne',
                'type' => 'caduques',
                'hauteur_max' => '25 mètres',
                'longevite' => 'Jusqu\'à 2000 ans',
                'categorie' => 'sacre',
                'description_generale' => 'Le baobab est un arbre massif au tronc ventru pouvant atteindre 25 mètres de hauteur et 15 mètres de diamètre. Son écorce est lisse et grise. Les feuilles sont composées palmées avec 5 à 7 folioles. Les fleurs sont blanches, pendantes et pollinisées par les chauves-souris. Le fruit, appelé "pain de singe", est une capsule oblongue contenant une pulpe farineuse comestible.',
                'description_botanique' => 'Le baobab est parfaitement adapté aux climats arides grâce à son tronc spongieux qui stocke l\'eau (jusqu\'à 120 000 litres), ses feuilles caduques qui réduisent l\'évapotranspiration pendant la saison sèche, et son système racinaire profond qui puise l\'eau en profondeur.',
                'utilisation' => "• Alimentaire : Pulpe du fruit riche en vitamine C, feuilles consommées comme légumes, graines grillées\n• Médicinal : Traitement de la fièvre, diarrhée, dysenterie, vitamine C naturelle\n• Artisanal : Écorce pour cordes et textiles",
                'importance_culturelle' => 'Le baobab occupe une place centrale dans la culture africaine. Il est souvent considéré comme l\'"arbre à palabres" où les anciens se réunissent pour prendre des décisions. De nombreux mythes et légendes lui sont associés, le décrivant souvent comme un arbre planté à l\'envers par les dieux.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Faible besoin une fois établi',
                    'sol' => 'Bien drainé, sablonneux',
                    'temperature' => 'Minimum 15°C',
                    'espace' => 'Minimum 10m de diamètre'
                ]),
                'statut_conservation' => 'Préoccupation mineure (UICN)',
                'image_principale' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop',
                'est_locale' => true,
                'periodes' => json_encode([
                    'floraison' => 'Octobre à décembre',
                    'fructification' => 'Janvier à mars',
                    'chute_feuilles' => 'Saison sèche',
                    'repousse' => 'Début de la saison des pluies'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // MANGUIER
            [
                'nom_scientifique' => 'Mangifera indica',
                'nom_local' => 'Manguier',
                'famille' => 'Anacardiaceae',
                'genre' => 'Mangifera',
                'origine' => 'Asie du Sud',
                'type' => 'persistant',
                'hauteur_max' => '15-30 mètres',
                'longevite' => '100-200 ans',
                'categorie' => 'fruitier',
                'description_generale' => 'Arbre fruitier tropical produisant des mangues, très apprécié pour ses fruits savoureux. Feuillage dense et persistant, croissance rapide. Le manguier est largement cultivé dans les régions tropicales et subtropicales du monde entier.',
                'description_botanique' => 'Le manguier a un port étalé avec un tronc robuste et une écorce grise. Ses feuilles sont alternes, simples, oblongues-lancéolées de 15-30 cm de long. Les fleurs sont petites, blanchâtres, en panicules terminales. Les fruits sont des drupes charnues de couleur verte à jaune-orangé à maturité.',
                'utilisation' => "• Alimentaire : Fruits consommés frais, en jus, confitures, fruits séchés\n• Médicinal : Feuilles utilisées contre le diabète, écorce astringente\n• Artisanal : Bois utilisé en menuiserie",
                'importance_culturelle' => 'Arbre très présent dans les concessions familiales en Afrique, symbole de générosité et d\'hospitalité. L\'ombre du manguier est un lieu de rassemblement et de partage.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Arrosage régulier les premières années',
                    'sol' => 'Riche, profond, bien drainé',
                    'temperature' => 'Minimum 10°C',
                    'espace' => '8-10m entre les arbres'
                ]),
                'statut_conservation' => 'Préoccupation mineure (UICN)',
                'image_principale' => 'https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=800&h=600&fit=crop',
                'est_locale' => false,
                'periodes' => json_encode([
                    'floraison' => 'Décembre à mars',
                    'fructification' => 'Avril à juillet'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ACAJOU D'AFRIQUE
            [
                'nom_scientifique' => 'Khaya senegalensis',
                'nom_local' => 'Acajou d\'Afrique',
                'famille' => 'Meliaceae',
                'genre' => 'Khaya',
                'origine' => 'Afrique de l\'Ouest',
                'type' => 'caduques',
                'hauteur_max' => '30 mètres',
                'longevite' => '200-300 ans',
                'categorie' => 'foret',
                'description_generale' => 'Grand arbre au bois précieux, très utilisé en ébénisterie. Ombre dense et port majestueux. L\'Acajou d\'Afrique est l\'une des essences les plus prisées du continent africain pour la qualité de son bois.',
                'description_botanique' => 'Tronc cylindrique pouvant atteindre 1,5 m de diamètre, écorce grise à brunâtre écailleuse. Feuilles composées pennées de 20-40 cm. Fleurs blanches parfumées en panicules. Fruits capsulaires ligneux de 5-8 cm.',
                'utilisation' => "• Bois d'ébénisterie et de menuiserie de luxe\n• Écorce utilisée en médecine traditionnelle (antipaludique, fébrifuge)\n• Arbre d'ombrage dans les plantations",
                'importance_culturelle' => 'Arbre souvent planté comme arbre d\'ombrage dans les villages et les marchés. Son bois est très prisé pour la fabrication de meubles de valeur et d\'instruments de musique.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Arrosage modéré',
                    'sol' => 'Profond, bien drainé',
                    'temperature' => 'Minimum 12°C',
                    'espace' => '10-12m entre les arbres'
                ]),
                'statut_conservation' => 'Vulnérable (UICN)',
                'image_principale' => 'https://images.unsplash.com/photo-1462143338528-eca9936a4d09?w=800&h=600&fit=crop',
                'est_locale' => true,
                'periodes' => json_encode([
                    'floraison' => 'Avril à juin',
                    'fructification' => 'Août à octobre'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // FROMAGER
            [
                'nom_scientifique' => 'Ceiba pentandra',
                'nom_local' => 'Fromager',
                'famille' => 'Malvaceae',
                'genre' => 'Ceiba',
                'origine' => 'Amérique tropicale, Afrique',
                'type' => 'caduques',
                'hauteur_max' => '60-70 mètres',
                'longevite' => '200-300 ans',
                'categorie' => 'foret',
                'description_generale' => 'Arbre majestueux au tronc épineux et au port imposant. Le fromager est l\'un des plus grands arbres d\'Afrique tropicale. Son bois léger est utilisé pour la fabrication de pirogues et d\'instruments de musique.',
                'description_botanique' => 'Tronc cylindrique avec des contreforts à la base, écorce grise couverte d\'épines coniques. Feuilles digitées composées de 5-9 folioles. Fleurs blanc-crème. Fruits capsulaires contenant des graines entourées d\'un kapok soyeux.',
                'utilisation' => "• Bois léger pour pirogues, tambours, sculptures\n• Kapok pour rembourrage\n• Médecine traditionnelle",
                'importance_culturelle' => 'Arbre sacré dans plusieurs cultures africaines. Souvent associé aux lieux de culte et aux cimetières. Son imposante stature inspire le respect et la vénération.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Arrosage régulier',
                    'sol' => 'Profond, humifère',
                    'temperature' => 'Minimum 15°C',
                    'espace' => '15-20m entre les arbres'
                ]),
                'statut_conservation' => 'Préoccupation mineure',
                'image_principale' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800&h=600&fit=crop',
                'est_locale' => true,
                'periodes' => json_encode([
                    'floraison' => 'Décembre à février',
                    'fructification' => 'Mars à mai'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // NEEM
            [
                'nom_scientifique' => 'Azadirachta indica',
                'nom_local' => 'Neem',
                'famille' => 'Meliaceae',
                'genre' => 'Azadirachta',
                'origine' => 'Inde, Asie du Sud',
                'type' => 'persistant',
                'hauteur_max' => '15-20 mètres',
                'longevite' => '150-200 ans',
                'categorie' => 'medicinal',
                'description_generale' => 'Arbre aux nombreuses propriétés médicinales, résistant à la sécheresse. Le neem est considéré comme "l\'arbre miracle" de la pharmacopée traditionnelle indienne. Toutes ses parties (feuilles, écorce, graines) ont des vertus thérapeutiques.',
                'description_botanique' => 'Tronc court, écorce brun-gris crevassée. Feuilles composées pennées. Fleurs blanches parfumées en panicules. Fruits oblongs jaune-vert à maturité.',
                'utilisation' => "• Médecine : antipaludique, antifongique, antibactérien\n• Agriculture : insecticide naturel\n• Hygiène : bâtonnets frotte-dents",
                'importance_culturelle' => 'Arbre sacré en Inde, associé à la déesse Durga. Introduit en Afrique pour ses propriétés médicinales et son ombrage.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Faible besoin',
                    'sol' => 'Tous types, même pauvres',
                    'temperature' => 'Minimum 10°C',
                    'espace' => '5-7m entre les arbres'
                ]),
                'statut_conservation' => 'Préoccupation mineure',
                'image_principale' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=800&h=600&fit=crop',
                'est_locale' => false,
                'periodes' => json_encode([
                    'floraison' => 'Mars à mai',
                    'fructification' => 'Juin à août'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // FLAMBOYANT
            [
                'nom_scientifique' => 'Delonix regia',
                'nom_local' => 'Flamboyant',
                'famille' => 'Fabaceae',
                'genre' => 'Delonix',
                'origine' => 'Madagascar',
                'type' => 'caduques',
                'hauteur_max' => '10-15 mètres',
                'longevite' => '50-100 ans',
                'categorie' => 'ornemental',
                'description_generale' => 'Arbre ornemental spectaculaire par sa floraison rouge vif en été. Le flamboyant est l\'un des arbres les plus décoratifs des régions tropicales. Son port étalé en parasol offre une ombre légère très appréciée.',
                'description_botanique' => 'Tronc court, écorce lisse gris-brun. Feuilles bipennées très fines (2-3 paires de pinnules). Fleurs rouge écarlate à orangé, grandes (8-15 cm). Gousses ligneuses de 30-60 cm.',
                'utilisation' => "• Ornemental : parc, avenue, jardin\n• Ombrage léger\n• Bois pour petits objets",
                'importance_culturelle' => 'Symbole de la beauté tropicale. Très présent dans les villes d\'Afrique de l\'Ouest. Sa floraison annonce souvent la saison des pluies ou les grandes vacances.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Arrosage modéré',
                    'sol' => 'Bien drainé, fertile',
                    'temperature' => 'Minimum 12°C',
                    'espace' => '8-10m entre les arbres'
                ]),
                'statut_conservation' => 'Préoccupation mineure',
                'image_principale' => 'https://images.unsplash.com/photo-1566140967404-b8b3932483f5?w=800&h=600&fit=crop',
                'est_locale' => false,
                'periodes' => json_encode([
                    'floraison' => 'Avril à juillet',
                    'fructification' => 'Août à octobre'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // KARITÉ
            [
                'nom_scientifique' => 'Vitellaria paradoxa',
                'nom_local' => 'Karité',
                'famille' => 'Sapotaceae',
                'genre' => 'Vitellaria',
                'origine' => 'Afrique de l\'Ouest',
                'type' => 'caduques',
                'hauteur_max' => '10-15 mètres',
                'longevite' => '200-300 ans',
                'categorie' => 'fruitier',
                'description_generale' => 'Arbre emblématique des savanes d\'Afrique de l\'Ouest, connu pour son beurre végétal aux multiples vertus. Le karité est un arbre protégé car il ne peut être cultivé et pousse uniquement à l\'état sauvage.',
                'description_botanique' => 'Tronc court, écorce épaisse et crevassée. Feuilles groupées aux extrémités des rameaux. Fleurs blanc-crème en bouquets. Fruits charnus contenant une amande riche en matière grasse.',
                'utilisation' => "• Cosmétique : beurre de karité hydratant\n• Alimentaire : huile de cuisson\n• Médicinal : cicatrisant, anti-inflammatoire",
                'importance_culturelle' => 'Arbre sacré dans plusieurs cultures. Le karité est considéré comme un arbre-femme, sa gestion et la transformation des noix sont traditionnellement réservées aux femmes. Source de revenus pour des millions de femmes rurales.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Faible besoin',
                    'sol' => 'Sablo-argileux, bien drainé',
                    'temperature' => 'Minimum 15°C',
                    'espace' => '10-12m entre les arbres'
                ]),
                'statut_conservation' => 'Vulnérable',
                'image_principale' => 'https://images.unsplash.com/photo-1462143338528-eca9936a4d09?w=800&h=600&fit=crop',
                'est_locale' => true,
                'periodes' => json_encode([
                    'floraison' => 'Novembre à janvier',
                    'fructification' => 'Avril à juin'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // NÉRÉ
            [
                'nom_scientifique' => 'Parkia biglobosa',
                'nom_local' => 'Néré',
                'famille' => 'Fabaceae',
                'genre' => 'Parkia',
                'origine' => 'Afrique de l\'Ouest',
                'type' => 'caduques',
                'hauteur_max' => '20 mètres',
                'longevite' => '100-150 ans',
                'categorie' => 'fruitier',
                'description_generale' => 'Arbre des savanes et forêts claires, très apprécié pour ses fruits transformés en épice et condiment. Le néré est un arbre multi-usages qui joue un rôle important dans la sécurité alimentaire.',
                'description_botanique' => 'Port étalé, écorce grise épaisse. Feuilles bipennées. Inflorescences en capitules globuleux pendants de couleur rouge. Gousses longues contenant des graines dans une pulpe jaune farineuse.',
                'utilisation' => "• Alimentaire : pulpe sucrée consommée crue, graines fermentées (soumbala)\n• Médicinal : écorce contre les morsures de serpent\n• Artisanal : teinture, bois",
                'importance_culturelle' => 'Le soumbala (condiment issu des graines fermentées) est un ingrédient essentiel de la cuisine ouest-africaine. L\'arbre est souvent conservé dans les champs pour son ombre et ses fruits.',
                'conseils_plantation' => json_encode([
                    'soleil' => 'Plein soleil',
                    'eau' => 'Arrosage modéré',
                    'sol' => 'Profond, bien drainé',
                    'temperature' => 'Minimum 15°C',
                    'espace' => '8-10m entre les arbres'
                ]),
                'statut_conservation' => 'Préoccupation mineure',
                'image_principale' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=600&fit=crop',
                'est_locale' => true,
                'periodes' => json_encode([
                    'floraison' => 'Décembre à février',
                    'fructification' => 'Mars à mai'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($especes as $espece) {
            Espece::create($espece);
        }

        $this->command->info('✅ ' . count($especes) . ' espèces créées avec succès !');
    }
}