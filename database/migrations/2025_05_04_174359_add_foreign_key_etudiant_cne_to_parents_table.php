<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->foreign('etudiant_cne')
                  ->references('cne')
                  ->on('etudiants')
                  ->nullOnDelete(); // إذا تحيد الولد، null فال parent
        });
    }

    public function down()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropForeign(['etudiant_cne']);
        });
    }
};
