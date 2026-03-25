<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arbres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            
            // Relations
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('espece_id')->constrained()->onDelete('cascade');
            
            // Informations
            $table->date('date_plantation');
            $table->string('planteur_nom');
            $table->string('planteur_photo')->nullable();
            $table->string('photo_arbre');
            $table->text('description');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            
            // Admin uniquement (caché aux visiteurs)
            $table->string('etat_sante')->default('bon'); // excellent, bon, moyen, surveillé
            $table->string('qr_code')->unique()->nullable();
            
            // Statistiques
            $table->integer('vues')->default(0);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arbres');
    }
};