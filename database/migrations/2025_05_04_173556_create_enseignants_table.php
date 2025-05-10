<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('specialite');
            $table->string('grade');
            $table->string('cin')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enseignants');
    }
};
