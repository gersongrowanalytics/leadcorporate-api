<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pappasosproductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pappasosproductos', function (Blueprint $table) {
            $table->increments('papid');
            $table->unsignedInteger('dsuid')->nullable();
            $table->unsignedInteger('prsid')->nullable();
            $table->unsignedInteger('proid')->nullable();
            $table->string('papcantidad')->nullable();
            $table->boolean('paprecepcion')->nullable();
            $table->boolean('papquiebre')->nullable();
            $table->string('papstock')->nullable();
            $table->string('papcodigo')->nullable();
            $table->unsignedInteger('mecid')->nullable();
            $table->string('papnombre')->nullable();
            $table->string('papean')->nullable();
            $table->string('papprecioregular')->nullable();
            $table->string('papmarca')->nullable();
            $table->boolean('pappromocion')->nullable();
            $table->string('papdescripcion')->nullable();
            $table->string('pappreciopromocion')->nullable();
            $table->string('papfechainicio')->nullable();
            $table->string('papfechafin')->nullable();
            $table->unsignedInteger('tpeid')->nullable();
            $table->boolean('papestatus')->nullable();
            $table->boolean('papestado')->default(false);
            $table->unsignedInteger('arrid')->nullable();
            $table->timestamps();

            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('prsid')->references('prsid')->on('prsproductossucursales');
            $table->foreign('proid')->references('proid')->on('proproductos');
            $table->foreign('mecid')->references('mecid')->on('mecmecanicas');
            $table->foreign('tpeid')->references('tpeid')->on('tpetiposexhibicion');
            $table->foreign('arrid')->references('arrid')->on('arrarrendados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pappasosproductos');
    }
}
