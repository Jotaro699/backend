<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Supprimer la contrainte FK actuelle
            $table->dropForeign(['etudiant_id']);

            // Ajouter la nouvelle FK vers la table `etudiants`
            $table->foreign('etudiant_id')
                ->references('id')
                ->on('etudiants')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Revenir à l'ancienne FK vers `users`
            $table->dropForeign(['etudiant_id']);

            $table->foreign('etudiant_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
