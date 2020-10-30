<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mapmarcasproveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapmarcasproveedores', function (Blueprint $table) {
            $table->increments('mapid');
            $table->unsignedInteger('prvid');
            $table->unsignedInteger('marid');
            $table->timestamps();

            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
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
        Schema::dropIfExists('mapmarcasproveedores');
    }
}
