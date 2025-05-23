<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('notes', function (Blueprint $table) {
            $table->unsignedBigInteger('enseignant_id')->after('matiere_id');
            $table->integer('numero')->after('note');

            $table->foreign('enseignant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['enseignant_id']);
            $table->dropColumn('enseignant_id');
            $table->dropColumn('numero');
        });
    }
};
