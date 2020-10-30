<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Proproductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proproductos', function (Blueprint $table) {
            $table->increments('proid');
            $table->unsignedInteger('catid');
            $table->unsignedInteger('marid')->nullable();
            $table->string('prosku');
            $table->string('pronombre');
            $table->timestamps();

            $table->foreign('catid')->references('catid')->on('catcategorias');
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
        Schema::dropIfExists('proproductos');
    }
}
