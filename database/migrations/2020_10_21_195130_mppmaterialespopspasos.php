<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mppmaterialespopspasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mppmaterialespopspasos', function (Blueprint $table) {
            $table->increments('mppid');
            $table->unsignedInteger('mtpid');
            $table->unsignedInteger('dsuid');
            $table->unsignedInteger('pauid');
            $table->unsignedInteger('spaid');
            $table->unsignedInteger('pasid');
            $table->unsignedInteger('prvid');
            $table->unsignedInteger('mapid')->nullable();
            $table->unsignedInteger('marid')->nullable();
            $table->string('mppmarca')->nullable();
            $table->string('mppproducto')->nullable();
            $table->string('mppdescripcion')->nullable();
            $table->timestamps();

            $table->foreign('mtpid')->references('mtpid')->on('mtpmaterialespops');
            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('pauid')->references('pauid')->on('paupasosusuarios');
            $table->foreign('spaid')->references('spaid')->on('spasubpasos');
            $table->foreign('pasid')->references('pasid')->on('paspasos');
            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
            $table->foreign('mapid')->references('mapid')->on('mapmarcasproveedores');
            $table->foreign('marid')->references('marid')->on('marmarcas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mppmaterialespopspasos');
    }
}
