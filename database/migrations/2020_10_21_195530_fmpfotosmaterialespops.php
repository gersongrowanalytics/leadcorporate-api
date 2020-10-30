<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fmpfotosmaterialespops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fmpfotosmaterialespops', function (Blueprint $table) {
            $table->increments('fmpid');
            $table->unsignedInteger('mppid');
            $table->string('fmpimagen');
            $table->timestamps();

            $table->foreign('mppid')->references('mppid')->on('mppmaterialespopspasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fmpfotosmaterialespops');
    }
}
