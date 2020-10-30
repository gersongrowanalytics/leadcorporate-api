<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Spasubpasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spasubpasos', function (Blueprint $table) {
            $table->increments('spaid');
            $table->unsignedInteger('pasid');
            $table->string('spanombre'); // NUEVO
            $table->timestamps();

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
        Schema::dropIfExists('spasubpasos');
    }
}
