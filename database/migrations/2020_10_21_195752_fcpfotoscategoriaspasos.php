<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fcpfotoscategoriaspasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcpfotoscategoriaspasos', function (Blueprint $table) {
            $table->increments('fcpid');
            $table->unsignedInteger('dcpid');
            $table->string('fcpimagen');
            $table->timestamps();

            $table->foreign('dcpid')->references('dcpid')->on('dcpdatoscategoriaspasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fcpfotoscategoriaspasos');
    }
}
