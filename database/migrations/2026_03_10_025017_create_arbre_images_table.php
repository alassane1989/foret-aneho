<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_arbre_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arbre_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arbre_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('thumbnail_url')->nullable();
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('autre');
            $table->integer('ordre')->default(0);
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['arbre_id', 'ordre']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('arbre_images');
    }
};