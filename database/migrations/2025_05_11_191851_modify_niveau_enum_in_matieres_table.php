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
        DB::statement("ALTER TABLE matieres MODIFY niveau ENUM('primaire', 'college', 'lycée') NOT NULL");
    }
    
    public function down()
    {
        DB::statement("ALTER TABLE matieres MODIFY niveau ENUM('ابتدائي', 'اعدادي', 'ثانوي') NOT NULL");
    }
    
};
