<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dsudatossubpasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsudatossubpasos', function (Blueprint $table) {
            $table->increments('dsuid');
            $table->unsignedInteger('pauid');
            $table->unsignedInteger('resid');
            $table->unsignedInteger('spaid');
            $table->unsignedInteger('pasid');
            $table->boolean('dsuordenado')->nullable();
            $table->boolean('dsulimpio')->nullable();
            $table->boolean('dsuplanogramacategoria')->nullable();
            $table->boolean('dsuexisteprogramacion')->nullable();
            $table->boolean('dsullegomercaderia')->nullable();
            $table->boolean('dsumercaderiatiempo')->nullable();
            $table->boolean('desupedidocompleto')->nullable();
            $table->boolean('dsucheckout')->nullable();
            $table->boolean('dsuestado')->nullable();
            $table->timestamps();

            $table->foreign('pauid')->references('pauid')->on('paupasosusuarios');
            $table->foreign('resid')->references('resid')->on('resregistrossucursales');
            $table->foreign('spaid')->references('spaid')->on('spasubpasos');
            $table->foreign('pasid')->references('pasid')->on('paspasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dsudatossubpasos');
    }
}
