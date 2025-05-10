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
    Schema::table('users', function (Blueprint $table) {
        $table->string('prenom')->nullable();
        $table->string('nom')->nullable();
        $table->date('date_naissance')->nullable();
        $table->enum('genre', ['homme', 'femme', 'autre'])->nullable();
        $table->string('telephone')->nullable();
        $table->string('adresse')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'prenom',
            'nom',
            'date_naissance',
            'genre',
            'telephone',
            'adresse',
        ]);
    });
}

};
