<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mpamarcaspasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpamarcaspasos', function (Blueprint $table) {
            $table->increments('mpaid');
            $table->unsignedInteger('mapid');
            $table->unsignedInteger('prvid');
            $table->unsignedInteger('marid');
            $table->unsignedInteger('dsuid');

            $table->unsignedInteger('cpaid');
            $table->unsignedInteger('capid');
            $table->unsignedInteger('catid');

            $table->boolean('mpaestado');
            $table->timestamps();

            $table->foreign('mapid')->references('mapid')->on('mapmarcasproveedores');
            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
            $table->foreign('marid')->references('marid')->on('marmarcas');
            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');

            $table->foreign('cpaid')->references('cpaid')->on('cpacategoriaspasos');
            $table->foreign('capid')->references('capid')->on('capcategoriasproveedores');
            $table->foreign('catid')->references('catid')->on('catcategorias');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpamarcaspasos');
    }
}
