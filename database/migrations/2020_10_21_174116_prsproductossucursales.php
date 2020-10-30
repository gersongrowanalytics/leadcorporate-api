<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Prsproductossucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prsproductossucursales', function (Blueprint $table) {
            $table->increments('prsid');
            $table->unsignedInteger('sucid')->nullable();
            $table->unsignedInteger('prvid')->nullable();
            $table->unsignedInteger('proid');
            $table->unsignedInteger('catid');
            $table->unsignedInteger('marid')->nullable();
            $table->unsignedInteger('fecid');
            $table->string('prsstock');
            $table->timestamps();

            $table->foreign('sucid')->references('sucid')->on('sucsucursales');
            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
            $table->foreign('proid')->references('proid')->on('proproductos');
            $table->foreign('catid')->references('catid')->on('catcategorias');
            $table->foreign('marid')->references('marid')->on('marmarcas');
            $table->foreign('fecid')->references('fecid')->on('fecfechas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prsproductossucursales');
    }
}