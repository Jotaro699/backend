<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            // $table->text('description')->nullable(); // ❌ déjà existante
            // $table->integer('coefficient')->default(1); // ✅ int au lieu de decimal
            // $table->enum('niveau', ['primaire', 'college', 'lycée']);
            // $table->string('classe');
            // $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropColumn(['coefficient', 'niveau', 'classe']);
            $table->dropForeign(['enseignant_id']);
            $table->dropColumn('enseignant_id');
        });
    }
};
