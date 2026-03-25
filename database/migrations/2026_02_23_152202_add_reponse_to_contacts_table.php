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
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'reponse')) {
                $table->text('reponse')->nullable()->after('message');
            }
            if (!Schema::hasColumn('contacts', 'date_reponse')) {
                $table->timestamp('date_reponse')->nullable()->after('date_traitement');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['reponse', 'date_reponse']);
        });
    }
};