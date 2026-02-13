<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description_courte');
            $table->longText('contenu');
            $table->string('categorie'); // plantation, education, infrastructure, partenariat, evenement
            $table->string('image_principale')->nullable();
            $table->json('galerie')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('auteur_nom');
            $table->json('tags')->nullable();
            $table->integer('vues')->default(0);
            $table->date('date_publication');
            $table->boolean('est_publie')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actualites');
    }
};