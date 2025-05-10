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
Schema::create('rapports', function (Blueprint $table) {
    $table->id(); // BIGINT UNSIGNED
    $table->unsignedBigInteger('cours_id'); // Correspond à `cours.id`
    $table->string('contenu');
    $table->timestamps();

    $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapports');
    }
};
