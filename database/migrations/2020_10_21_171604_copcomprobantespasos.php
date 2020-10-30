<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Copcomprobantespasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copcomprobantespasos', function (Blueprint $table) {
            $table->increments('copid');
            $table->unsignedInteger('tpcid');
            $table->unsignedInteger('dsuid');
            $table->unsignedInteger('pasid');
            $table->string('copnumero');
            $table->timestamps();

            $table->foreign('tpcid')->references('tpcid')->on('tpctiposcomprobantes');
            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('pasid')->references('pasid')->on('paspasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copcomprobantespasos');
    }
}
