<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['enseignant_id']);
            $table->foreign('enseignant_id')->references('id')->on('enseignants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['enseignant_id']);
            $table->foreign('enseignant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
