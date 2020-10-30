<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fppfotosproductospasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fppfotosproductospasos', function (Blueprint $table) {
            $table->increments('fppid');
            $table->unsignedInteger('papid');
            $table->unsignedInteger('dsuid');
            $table->unsignedInteger('prsid');
            $table->unsignedInteger('proid');
            $table->string('fppimagen');
            $table->timestamps();

            $table->foreign('papid')->references('papid')->on('pappasosproductos');
            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('prsid')->references('prsid')->on('prsproductossucursales');
            $table->foreign('proid')->references('proid')->on('proproductos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fppfotosproductospasos');
    }
}
