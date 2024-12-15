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
        Schema::table('ruangs', function (Blueprint $table) {
            $table->string("penanggung_jawab", 255)->nullable()->after("nama");
            $table->string("pengurus", 255)->nullable()->after("penanggung_jawab");
            $table->string("kepala_kantor", 255)->nullable()->after("pengurus");
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down()
    {
        Schema::table('ruangs', function (Blueprint $table) {
            $table->dropColumn(["penanggung_jawab", "pengurus", "kepala_kantor"]);
        });
    }
};
