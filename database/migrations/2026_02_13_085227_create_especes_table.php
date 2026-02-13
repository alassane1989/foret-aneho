<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('especes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_scientifique');
            $table->string('nom_local');
            $table->string('slug')->unique();
            $table->string('famille')->nullable();
            $table->string('genre')->nullable();
            $table->string('origine')->nullable();
            $table->string('type')->nullable(); // caduque, persistant
            $table->string('hauteur_max')->nullable();
            $table->string('longevite')->nullable();
            $table->string('categorie'); // fruitier, ornemental, foret, sacre, medicinal
            $table->text('description_generale');
            $table->text('description_botanique')->nullable();
            $table->text('utilisation')->nullable();
            $table->text('importance_culturelle')->nullable();
            $table->json('conseils_plantation')->nullable();
            $table->string('statut_conservation')->nullable();
            $table->string('image_principale')->nullable();
            $table->json('galerie')->nullable();
            $table->boolean('est_locale')->default(true);
            $table->json('periodes')->nullable(); // floraison, fructification, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('especes');
    }
};