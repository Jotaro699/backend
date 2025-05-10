<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('profession')->nullable();
            $table->string('cin')->unique();
            $table->string('etudiant_cne')->nullable(); // سنربطه لاحقاً
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parents');
    }
};
