<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // GLIDJI, LOLAN, NLESS, YVELINES
            $table->string('slug')->unique();
            $table->text('description_courte');
            $table->text('description_longue')->nullable();
            $table->string('image_principale')->nullable();
            $table->json('galerie')->nullable();
            
            // Statistiques
            $table->integer('nombre_arbres')->default(0);
            $table->integer('nombre_especes')->default(0);
            $table->string('superficie')->nullable();
            
            // Localisation (optionnel pour ajout ultérieur)
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->json('polygone_coordonnees')->nullable();
            $table->string('adresse_acces')->nullable();
            
            // Caractéristiques
            $table->json('especes_principales')->nullable();
            $table->json('activites')->nullable();
            $table->string('couleur')->default('#4CAF50');
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Ordre d'affichage
            $table->integer('ordre')->default(0);
            $table->boolean('est_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zones');
    }
};