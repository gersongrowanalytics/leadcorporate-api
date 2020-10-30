<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fopfotospasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fopfotospasos', function (Blueprint $table) {
            $table->increments('fopid');
            $table->unsignedInteger('dsuid');
            $table->string('fopimagen');
            $table->timestamps();

            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fopfotospasos');
    }
}
