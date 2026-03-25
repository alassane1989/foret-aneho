<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Arbres
            ['nom' => 'Voir les arbres', 'slug' => 'arbres.voir', 'groupe' => 'arbres'],
            ['nom' => 'Créer un arbre', 'slug' => 'arbres.creer', 'groupe' => 'arbres'],
            ['nom' => 'Modifier un arbre', 'slug' => 'arbres.modifier', 'groupe' => 'arbres'],
            ['nom' => 'Supprimer un arbre', 'slug' => 'arbres.supprimer', 'groupe' => 'arbres'],
            ['nom' => 'Exporter les arbres', 'slug' => 'arbres.exporter', 'groupe' => 'arbres'],
            
            // Zones
            ['nom' => 'Voir les zones', 'slug' => 'zones.voir', 'groupe' => 'zones'],
            ['nom' => 'Créer une zone', 'slug' => 'zones.creer', 'groupe' => 'zones'],
            ['nom' => 'Modifier une zone', 'slug' => 'zones.modifier', 'groupe' => 'zones'],
            ['nom' => 'Supprimer une zone', 'slug' => 'zones.supprimer', 'groupe' => 'zones'],
            
            // Espèces
            ['nom' => 'Voir les espèces', 'slug' => 'especes.voir', 'groupe' => 'especes'],
            ['nom' => 'Créer une espèce', 'slug' => 'especes.creer', 'groupe' => 'especes'],
            ['nom' => 'Modifier une espèce', 'slug' => 'especes.modifier', 'groupe' => 'especes'],
            ['nom' => 'Supprimer une espèce', 'slug' => 'especes.supprimer', 'groupe' => 'especes'],
            
            // Actualités
            ['nom' => 'Voir les actualités', 'slug' => 'actualites.voir', 'groupe' => 'actualites'],
            ['nom' => 'Créer une actualité', 'slug' => 'actualites.creer', 'groupe' => 'actualites'],
            ['nom' => 'Modifier une actualité', 'slug' => 'actualites.modifier', 'groupe' => 'actualites'],
            ['nom' => 'Publier une actualité', 'slug' => 'actualites.publier', 'groupe' => 'actualites'],
            ['nom' => 'Supprimer une actualité', 'slug' => 'actualites.supprimer', 'groupe' => 'actualites'],
            
            // Contacts
            ['nom' => 'Voir les contacts', 'slug' => 'contacts.voir', 'groupe' => 'contacts'],
            ['nom' => 'Répondre aux contacts', 'slug' => 'contacts.repondre', 'groupe' => 'contacts'],
            ['nom' => 'Supprimer un contact', 'slug' => 'contacts.supprimer', 'groupe' => 'contacts'],
            ['nom' => 'Exporter les contacts', 'slug' => 'contacts.exporter', 'groupe' => 'contacts'],
            
            // Newsletter
            ['nom' => 'Voir la newsletter', 'slug' => 'newsletter.voir', 'groupe' => 'newsletter'],
            ['nom' => 'Exporter la newsletter', 'slug' => 'newsletter.exporter', 'groupe' => 'newsletter'],
            ['nom' => 'Envoyer une campagne', 'slug' => 'newsletter.envoyer', 'groupe' => 'newsletter'],
            
            // Utilisateurs
            ['nom' => 'Voir les utilisateurs', 'slug' => 'users.voir', 'groupe' => 'users'],
            ['nom' => 'Créer un utilisateur', 'slug' => 'users.creer', 'groupe' => 'users'],
            ['nom' => 'Modifier un utilisateur', 'slug' => 'users.modifier', 'groupe' => 'users'],
            ['nom' => 'Désactiver un utilisateur', 'slug' => 'users.desactiver', 'groupe' => 'users'],
            
            // Rôles
            ['nom' => 'Voir les rôles', 'slug' => 'roles.voir', 'groupe' => 'roles'],
            ['nom' => 'Créer un rôle', 'slug' => 'roles.creer', 'groupe' => 'roles'],
            ['nom' => 'Modifier un rôle', 'slug' => 'roles.modifier', 'groupe' => 'roles'],
            ['nom' => 'Supprimer un rôle', 'slug' => 'roles.supprimer', 'groupe' => 'roles'],
            ['nom' => 'Attribuer un rôle', 'slug' => 'roles.attribuer', 'groupe' => 'roles'],
            
            // Système
            ['nom' => 'Voir les logs', 'slug' => 'systeme.logs', 'groupe' => 'systeme'],
            ['nom' => 'Configuration système', 'slug' => 'systeme.config', 'groupe' => 'systeme'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}