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
        Schema::create('nilai_alt', function (Blueprint $table) {
            $table->id();
            $table->integer('kode_krit'); 
            $table->integer('kode_alt');
            $table->foreign('kode_alt')->references('kode_alternatif')->on('alternatif');
            $table->foreign('kode_krit')->references('kode_kriteria')->on('kriteria');
            $table->float('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_alt');
    }
};
