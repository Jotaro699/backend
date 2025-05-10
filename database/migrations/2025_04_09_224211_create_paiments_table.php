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
public function up(): void
{
    Schema::create('paiements', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('parent_id');
        $table->string('mode'); // carte, virement, etc.
        $table->float('montant');
        $table->timestamps();

        $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paiments');
    }
};
