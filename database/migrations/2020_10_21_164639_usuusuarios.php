<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Usuusuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuusuarios', function (Blueprint $table) {
            $table->increments('usuid');
            $table->unsignedInteger('tpuid');
            $table->string('usuusuario');
            $table->string('usucontrasenia');
            $table->string('usutoken');
            $table->timestamps();

            $table->foreign('tpuid')->references('tpuid')->on('tputiposusuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuusuarios');
    }
}
