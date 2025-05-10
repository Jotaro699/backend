<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('cours', function (Blueprint $table) {
    $table->id(); // => BIGINT UNSIGNED automatiquement
    $table->string('titre');
    $table->unsignedBigInteger('matiere_id');
    $table->unsignedBigInteger('enseignant_id');
    $table->timestamps();

    $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
    $table->foreign('enseignant_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cours');
    }
};
