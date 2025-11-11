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
        Schema::table('exercises', function (Blueprint $table) {
            // Ajoute la colonne user_id (unsignedBigInteger requis pour la clé étrangère)
            // L'ajout de 'after(\'id\')' est optionnel mais assure un placement propre.
            $table->foreignId('user_id')->constrained()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
           // Supprime d'abord la contrainte de clé étrangère
            $table->dropForeign(['user_id']);
            // Supprime ensuite la colonne
            $table->dropColumn('user_id');
        });
    }
};
