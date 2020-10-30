<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mcpmarcascategoriasproveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcpmarcascategoriasproveedores', function (Blueprint $table) {
            $table->increments('mcpid');
            $table->unsignedInteger('capid');
            $table->unsignedInteger('catid');
            $table->unsignedInteger('mapid');
            $table->unsignedInteger('marid');
            $table->unsignedInteger('prvid');
            $table->timestamps();

            $table->foreign('capid')->references('capid')->on('capcategoriasproveedores');
            $table->foreign('catid')->references('catid')->on('catcategorias');
            $table->foreign('mapid')->references('mapid')->on('mapmarcasproveedores');
            $table->foreign('marid')->references('marid')->on('marmarcas');
            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcpmarcascategoriasproveedores');
    }
}
