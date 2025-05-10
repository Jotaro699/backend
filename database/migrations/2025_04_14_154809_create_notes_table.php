<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etudiant_id'); // Assurez-vous que cette colonne existe dans la table users
            $table->unsignedBigInteger('matiere_id'); // Doit correspondre à la clé primaire de matieres
            $table->float('note');
            $table->timestamps();

            $table->foreign('etudiant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
