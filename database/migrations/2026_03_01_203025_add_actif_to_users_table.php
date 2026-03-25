<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'actif')) {
                $table->boolean('actif')->default(true)->after('is_super_admin');
            }
            if (!Schema::hasColumn('users', 'derniere_ip')) {
                $table->string('derniere_ip')->nullable()->after('actif');
            }
            if (!Schema::hasColumn('users', 'derniere_connexion')) {
                $table->timestamp('derniere_connexion')->nullable()->after('derniere_ip');
            }
            if (!Schema::hasColumn('users', 'preferences')) {
                $table->json('preferences')->nullable()->after('derniere_connexion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['actif', 'derniere_ip', 'derniere_connexion', 'preferences']);
        });
    }
};