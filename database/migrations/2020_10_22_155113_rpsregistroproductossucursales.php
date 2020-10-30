<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rpsregistroproductossucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpsregistroproductossucursales', function (Blueprint $table) {
            $table->increments('rpsid');
            $table->unsignedInteger('prsid');
            $table->unsignedInteger('fecid');
            $table->string('rpsstock');
            $table->timestamps();

            $table->foreign('prsid')->references('prsid')->on('prsproductossucursales');
            $table->foreign('fecid')->references('fecid')->on('fecfechas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpsregistroproductossucursales');
    }
}
