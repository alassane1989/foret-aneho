<?php
// database/migrations/2026_03_01_000002_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('niveau')->default(0);
            $table->boolean('est_defaut')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};