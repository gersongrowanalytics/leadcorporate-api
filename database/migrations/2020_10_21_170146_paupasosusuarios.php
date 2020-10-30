<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Paupasosusuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paupasosusuarios', function (Blueprint $table) {
            $table->increments('pauid');
            $table->unsignedInteger('pasid');
            $table->unsignedInteger('resid');
            $table->unsignedInteger('usuid');
            $table->boolean('pauestado');
            $table->timestamps();

            $table->foreign('pasid')->references('pasid')->on('paspasos');
            $table->foreign('resid')->references('resid')->on('resregistrossucursales');
            $table->foreign('usuid')->references('usuid')->on('usuusuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paupasosusuarios');
    }
}
