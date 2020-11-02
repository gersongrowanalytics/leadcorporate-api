<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Resregistrossucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resregistrossucursales', function (Blueprint $table) {
            $table->increments('resid');
            $table->unsignedInteger('usuid');
            $table->unsignedInteger('sucid');
            $table->string('resestado');
            $table->string('resfechaingresotienda')->nullable();
            $table->string('reshoraingresotienda')->nullable();
            $table->string('resfechasalidatienda')->nullable();
            $table->string('reshorasalidatienda')->nullable();
            $table->timestamps();

            $table->foreign('usuid')->references('usuid')->on('usuusuarios');
            $table->foreign('sucid')->references('sucid')->on('sucsucursales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resregistrossucursales');
    }
}
