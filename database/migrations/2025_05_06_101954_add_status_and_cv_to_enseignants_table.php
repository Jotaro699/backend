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
    Schema::table('enseignants', function (Blueprint $table) {
        $table->string('status')->default('en attente');
        $table->string('cv')->nullable();
    });
}

public function down()
{
    Schema::table('enseignants', function (Blueprint $table) {
        $table->dropColumn(['status', 'cv']);
    });
}

    };
