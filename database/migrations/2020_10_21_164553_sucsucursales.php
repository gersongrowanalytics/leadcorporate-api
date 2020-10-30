<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sucsucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucsucursales', function (Blueprint $table) {
            $table->increments('sucid');
            $table->string('sucnombre');
            $table->string('sucdiaatencion');
            $table->string('suchoraatencion');
            $table->string('sucdiadespacho')->nullable();
            $table->string('suchoradespacho')->nullable();
            $table->string('suclatitud');
            $table->string('suclongitud');
            $table->string('sucdireccion')->nullable();
            $table->boolean('sucestado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucsucursales');
    }
}
